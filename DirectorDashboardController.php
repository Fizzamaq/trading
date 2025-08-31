<?php
namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\InventoryLot;
use App\Models\SalesPayment;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class DirectorDashboardController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->middleware(['auth', 'role:director,owner']);
        $this->inventoryService = $inventoryService;
    }

public function index()
{
    // Get recent sales
    $recentSales = SalesInvoice::with('customer')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // Get recent purchases 
    $recentPurchases = PurchaseInvoice::with('supplier')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // Calculate customer metrics
    $activeCustomers = Customer::where('is_active', true)->count();
    $newCustomersCount = Customer::where('created_at', '>=', now()->startOfMonth())->count();

    // Calculate supplier metrics
    $activeSuppliers = Supplier::where('is_active', true)->count();

    // Get inventory data with null safety
    $inventoryItems = InventoryLot::where('remaining_quantity', '>', 0)->get();
    
    // Get low stock items (less than 10% of original quantity)
    $lowStockItems = InventoryLot::whereRaw('remaining_quantity < (original_quantity * 0.1)')
        ->where('remaining_quantity', '>', 0)
        ->limit(6)
        ->get();

    // If collections are empty, create empty collections to avoid errors
    if (!$recentSales) {
        $recentSales = collect();
    }
    if (!$recentPurchases) {
        $recentPurchases = collect();
    }
    if (!$inventoryItems) {
        $inventoryItems = collect();
    }
    if (!$lowStockItems) {
        $lowStockItems = collect();
    }

    return view('director.dashboard', compact(
        'recentSales',
        'recentPurchases',
        'activeCustomers', 
        'newCustomersCount',
        'activeSuppliers',
        'inventoryItems',
        'lowStockItems'
    ));
}

    public function purchases()
    {
        // Fetch paginated purchases with supplier relation
        $purchases = PurchaseInvoice::with('supplier')->paginate(15);

        // Return the purchases view with the data
        return view('director.purchases.index', compact('purchases'));
    }

public function sales()
{
    $sales = SalesInvoice::with('customer')->paginate(15);
    return view('director.sales.index', compact('sales'));
}

public function customers()
{
    $customers = Customer::where('is_active', true)->paginate(15);
    return view('director.customers.index', compact('customers'));
}

public function suppliers()
{
    $suppliers = Supplier::where('is_active', true)->paginate(15);
    return view('director.suppliers.index', compact('suppliers'));
}

public function inventory()
{
    $inventoryItems = InventoryLot::with('product')->paginate(15);
    return view('director.inventory.index', compact('inventoryItems'));
}

public function payments()
{
    // Fetch paginated sales payments with customer relation
    $payments = SalesPayment::with('customer')->paginate(15);
    return view('director.payments.index', compact('payments'));
}

public function expenses()
{
    // Add expense logic here
    $expenses = collect(); // Replace with actual expense model
    return view('director.expenses.index', compact('expenses'));
}

}
