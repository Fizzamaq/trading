<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\InventoryLot;
use App\Models\PurchaseInvoice;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->middleware(['auth', 'role:director,owner']);
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $inventoryItems = InventoryLot::paginate(15);
        return view('director.inventory.index', compact('inventoryItems'));
    }

    public function create()
    {
        $purchaseInvoices = PurchaseInvoice::all();
        return view('director.inventory.create', compact('purchaseInvoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lot_number' => 'required|string|max:100|unique:inventory_lots,lot_number',
            'product_name' => 'required|string|max:255',
            'original_quantity' => 'required|numeric|min:0.001',
            'unit_cost' => 'required|numeric|min:0.01',
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_invoice_id' => 'required|exists:purchase_invoices,id',
        ]);
        
        $validated['remaining_quantity'] = $validated['original_quantity'];
        $validated['total_cost'] = $validated['original_quantity'] * $validated['unit_cost'];
        
        $inventoryItem = InventoryLot::create($validated);
        $this->activityLogService->logModelCreated($inventoryItem);
        
        return redirect()->route('director.inventory.index')->with('success', 'Inventory item added successfully!');
    }

    public function show(InventoryLot $inventory)
    {
        return view('director.inventory.show', compact('inventory'));
    }

    public function edit(InventoryLot $inventory)
    {
        $purchaseInvoices = PurchaseInvoice::all();
        return view('director.inventory.edit', compact('inventory', 'purchaseInvoices'));
    }

    public function update(Request $request, InventoryLot $inventory)
    {
        $validated = $request->validate([
            'lot_number' => ['required', 'string', 'max:100', Rule::unique('inventory_lots', 'lot_number')->ignore($inventory->id)],
            'product_name' => 'required|string|max:255',
            'original_quantity' => 'required|numeric|min:0.001',
            'remaining_quantity' => 'required|numeric|min:0.001|max:'.$request->original_quantity,
            'unit_cost' => 'required|numeric|min:0.01',
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_invoice_id' => 'required|exists:purchase_invoices,id',
        ]);
        
        $validated['total_cost'] = $validated['original_quantity'] * $validated['unit_cost'];
        
        $original = $inventory->getOriginal();
        $inventory->update($validated);
        $this->activityLogService->logModelUpdated($inventory, $original);
        
        return redirect()->route('director.inventory.index')->with('success', 'Inventory item updated successfully!');
    }
}