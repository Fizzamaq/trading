<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Supplier;
use App\Models\InventoryLot;
use App\Services\AccountingService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    protected $accountingService;
    protected $activityLogService;

    public function __construct(AccountingService $accountingService, ActivityLogService $activityLogService)
    {
        $this->middleware(['auth', 'role:director,owner']);
        $this->accountingService = $accountingService;
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $purchaseInvoices = PurchaseInvoice::with(['supplier', 'creator'])
            ->orderBy('invoice_date', 'desc')
            ->paginate(15);

        return view('director.purchases.index', compact('purchaseInvoices'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        
        return view('director.purchases.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_date' => 'required|date',
            'credit_days' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.lot_number' => 'required|string|max:100',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            $supplier = Supplier::find($request->supplier_id);
            $invoiceNumber = 'PI-' . now()->format('Ymd') . '-' . (PurchaseInvoice::count() + 1);
            
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            // Create purchase invoice
            $invoice = PurchaseInvoice::create([
                'invoice_number' => $invoiceNumber,
                'supplier_id' => $request->supplier_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => now()->parse($request->invoice_date)->addDays($request->credit_days),
                'credit_days' => $request->credit_days,
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total_amount' => $subtotal,
                'remaining_amount' => $subtotal,
                'created_by' => auth()->id(),
            ]);

            // Create invoice items and inventory lots
            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                
                // Create invoice item
                PurchaseInvoiceItem::create([
                    'purchase_invoice_id' => $invoice->id,
                    'lot_number' => $item['lot_number'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                ]);

                // Create inventory lot
                InventoryLot::create([
                    'lot_number' => $item['lot_number'],
                    'product_name' => $item['product_name'],
                    'original_quantity' => $item['quantity'],
                    'remaining_quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_price'],
                    'total_cost' => $lineTotal,
                    'purchase_date' => $request->invoice_date,
                    'supplier_id' => $request->supplier_id,
                    'purchase_invoice_id' => $invoice->id,
                ]);
            }

            // Create accounting transaction
            $this->accountingService->createPurchaseInvoiceTransaction($invoice);

            // Log activity
            $this->activityLogService->logModelCreated($invoice);

            DB::commit();

            return redirect()->route('director.purchases.index')
                ->with('success', 'Purchase invoice created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create purchase invoice: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
