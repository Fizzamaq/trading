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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function index(Request $request)
    {
        $query = PurchaseInvoice::with(['supplier', 'creator']);
        
        // Add search filtering logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%' . $search . '%');
                    });
            });
        }
        
        // Add sorting logic
        $sortBy = $request->get('sort_by', 'invoice_date');
        $sortDirection = $request->get('sort_direction', 'desc');

        $validSortColumns = ['invoice_number', 'supplier', 'invoice_date', 'total_amount'];
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'invoice_date';
        }

        if ($sortBy === 'supplier') {
            $query->join('suppliers', 'purchase_invoices.supplier_id', '=', 'suppliers.id')
                  ->orderBy('suppliers.name', $sortDirection)
                  ->select('purchase_invoices.*');
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $purchases = $query->paginate(15);
        $search = $request->input('search');

        return view('director.purchases.index', compact('purchases', 'search', 'sortBy', 'sortDirection'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $todayDatePrefix = Carbon::now()->format('ymd');
        
        $lastLot = InventoryLot::where('lot_number', 'like', $todayDatePrefix . '%')
            ->orderBy('lot_number', 'desc')
            ->first();

        $nextLotNumber = 1;
        if ($lastLot) {
            $lastNumber = (int) substr($lastLot->lot_number, -3);
            $nextLotNumber = $lastNumber + 1;
        }

        $initialLotNumber = $todayDatePrefix . str_pad($nextLotNumber, 3, '0', STR_PAD_LEFT);
        
        return view('director.purchases.create', compact('suppliers', 'initialLotNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_date' => 'required|date',
            'credit_days' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            $supplier = Supplier::find($validated['supplier_id']);
            $invoiceNumber = 'PI-' . now()->format('Ymd') . '-' . (PurchaseInvoice::count() + 1);
            
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            // Create purchase invoice
            $invoice = PurchaseInvoice::create([
                'invoice_number' => $invoiceNumber,
                'supplier_id' => $validated['supplier_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => now()->parse($validated['invoice_date'])->addDays($validated['credit_days']),
                'credit_days' => $validated['credit_days'],
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total_amount' => $subtotal,
                'remaining_amount' => $subtotal,
                'created_by' => auth()->id(),
            ]);

            // Create invoice items and inventory lots
            $todayDatePrefix = Carbon::parse($validated['invoice_date'])->format('ymd');
            $lastLot = InventoryLot::where('lot_number', 'like', $todayDatePrefix . '%')
                ->orderBy('lot_number', 'desc')
                ->first();

            $nextLotNumber = 1;
            if ($lastLot) {
                $lastNumber = (int) substr($lastLot->lot_number, -3);
                $nextLotNumber = $lastNumber + 1;
            }

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $lotNumber = $todayDatePrefix . str_pad($nextLotNumber, 3, '0', STR_PAD_LEFT);
                
                // Create invoice item
                PurchaseInvoiceItem::create([
                    'purchase_invoice_id' => $invoice->id,
                    'lot_number' => $lotNumber,
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                ]);

                // Create inventory lot
                InventoryLot::create([
                    'lot_number' => $lotNumber,
                    'product_name' => $item['product_name'],
                    'original_quantity' => $item['quantity'],
                    'remaining_quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_price'],
                    'total_cost' => $lineTotal,
                    'purchase_date' => $validated['invoice_date'],
                    'supplier_id' => $validated['supplier_id'],
                    'purchase_invoice_id' => $invoice->id,
                ]);

                $nextLotNumber++;
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

    public function show(PurchaseInvoice $purchase)
    {
        $purchase->load(['supplier', 'items']);
        return view('director.purchases.show', compact('purchase'));
    }

    public function edit(PurchaseInvoice $purchase)
    {
        $suppliers = Supplier::all();
        $purchase->load(['supplier', 'items']);
        return view('director.purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(Request $request, PurchaseInvoice $purchase)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_date' => 'required|date',
            'credit_days' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.lot_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('inventory_lots', 'lot_number')->ignore($purchase->id, 'purchase_invoice_id'),
            ],
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);
        
        $original = $purchase->getOriginal();
        
        DB::beginTransaction();
        try {
            // Update purchase invoice fields
            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => now()->parse($validated['invoice_date'])->addDays($validated['credit_days']),
                'credit_days' => $validated['credit_days'],
            ]);
            
            // Re-calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            $purchase->subtotal = $subtotal;
            $purchase->total_amount = $subtotal;
            $purchase->remaining_amount = $subtotal - $purchase->paid_amount;
            $purchase->save();

            // Handle items: clear existing and create new
            $purchase->items()->delete();
            $purchase->inventoryLots()->delete();

            foreach ($validated['items'] as $item) {
                PurchaseInvoiceItem::create([
                    'purchase_invoice_id' => $purchase->id,
                    'lot_number' => $item['lot_number'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['quantity'] * $item['unit_price'],
                ]);
                
                InventoryLot::create([
                    'purchase_invoice_id' => $purchase->id,
                    'lot_number' => $item['lot_number'],
                    'product_name' => $item['product_name'],
                    'original_quantity' => $item['quantity'],
                    'remaining_quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_price'],
                    'total_cost' => $item['quantity'] * $item['unit_price'],
                    'purchase_date' => $validated['invoice_date'],
                    'supplier_id' => $validated['supplier_id'],
                ]);
            }
            
            $this->activityLogService->logModelUpdated($purchase, $original);
            DB::commit();

            return redirect()->route('director.purchases.index')
                ->with('success', 'Purchase invoice updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update purchase invoice: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
