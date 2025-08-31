<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Customer;
use App\Models\InventoryLot;
use App\Services\AccountingService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesInvoiceController extends Controller
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
        $sales = SalesInvoice::with(['customer', 'creator'])
            ->orderBy('invoice_date', 'desc')
            ->paginate(15);

        return view('director.sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $inventoryLots = InventoryLot::where('remaining_quantity', '>', 0)->get();

        return view('director.sales.create', compact('customers', 'inventoryLots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'credit_days' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.inventory_lot_id' => 'required|exists:inventory_lots,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_selling_price' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            $invoiceNumber = 'SI-' . now()->format('Ymd') . '-' . (SalesInvoice::count() + 1);
            $subtotal = 0;
            $costOfGoods = 0;

            foreach ($request->items as $item) {
                $lot = InventoryLot::find($item['inventory_lot_id']);
                if ($lot->remaining_quantity < $item['quantity']) {
                    throw new \Exception('Insufficient quantity for lot ' . $lot->lot_number);
                }

                $lineTotal = $item['quantity'] * $item['unit_selling_price'];
                $lineCost = $item['quantity'] * $lot->unit_cost;
                $subtotal += $lineTotal;
                $costOfGoods += $lineCost;
            }

            $grossProfit = $subtotal - $costOfGoods;

            // Create sales invoice
            $invoice = SalesInvoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $request->customer_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => now()->parse($request->invoice_date)->addDays($request->credit_days),
                'credit_days' => $request->credit_days,
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total_amount' => $subtotal,
                'cost_of_goods' => $costOfGoods,
                'gross_profit' => $grossProfit,
                'remaining_amount' => $subtotal,
                'created_by' => auth()->id(),
            ]);

            // Create invoice items and update inventory
            foreach ($request->items as $item) {
                $lot = InventoryLot::find($item['inventory_lot_id']);
                $lineTotal = $item['quantity'] * $item['unit_selling_price'];
                $lineCost = $item['quantity'] * $lot->unit_cost;

                SalesInvoiceItem::create([
                    'sales_invoice_id' => $invoice->id,
                    'inventory_lot_id' => $lot->id,
                    'product_name' => $lot->product_name,
                    'quantity_sold' => $item['quantity'],
                    'unit_selling_price' => $item['unit_selling_price'],
                    'unit_cost_price' => $lot->unit_cost,
                    'line_total' => $lineTotal,
                    'line_cost' => $lineCost,
                    'line_profit' => $lineTotal - $lineCost,
                ]);

                // Update remaining quantity in inventory
                $lot->remaining_quantity -= $item['quantity'];
                $lot->save();
            }

            // Create accounting transaction
            $this->accountingService->createSalesInvoiceTransaction($invoice);
            $this->activityLogService->logModelCreated($invoice);

            DB::commit();

            return redirect()->route('director.sales.index')
                ->with('success', 'Sales invoice created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create sales invoice: ' . $e->getMessage()])
                ->withInput();
        }
    }
}