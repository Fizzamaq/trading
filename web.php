<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\InvestorManagementController;
use App\Http\Controllers\Director\DirectorDashboardController;
use App\Http\Controllers\Director\PurchaseInvoiceController;
use App\Http\Controllers\Director\SalesInvoiceController;
use App\Http\Controllers\Director\SalesPaymentController;
use App\Http\Controllers\Director\ExpenseController;
use App\Http\Controllers\Director\SupplierController;
use App\Http\Controllers\Director\InventoryController;
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\UserController;
use App\Http\Controllers\Owner\SettingController;
use App\Http\Controllers\Owner\TransactionController;
use App\Http\Controllers\Owner\AuditLogsController;
use App\Http\Controllers\Owner\HelpAndSupportController;
use App\Http\Controllers\Investor\InvestorDashboardController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home route accessible after login
Route::middleware(['auth'])->get('/home', [HomeController::class, 'index'])->name('home');

// Investor onboarding routes
Route::middleware(['auth', 'investor'])->prefix('investor')->name('investor.')->group(function () {
    // These routes handle the onboarding process and must be outside the 'investor.onboarding' middleware
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('/onboarding/complete/step1', [OnboardingController::class, 'completeStep1'])->name('onboarding.complete.step1');
    Route::post('/onboarding/complete/step2', [OnboardingController::class, 'completeStep2'])->name('onboarding.complete.step2');
    Route::post('/onboarding/complete/step3', [OnboardingController::class, 'completeStep3'])->name('onboarding.complete.step3');

    // The dashboard and other investor pages are only accessible after onboarding is complete
    Route::middleware(['investor.onboarding'])->group(function() {
        Route::get('/dashboard', [InvestorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests/create', [InvestorDashboardController::class, 'createRequest'])->name('requests.create');
        Route::post('/requests', [InvestorDashboardController::class, 'storeRequest'])->name('requests.store');
    });
});

// Owner routes
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('investors', InvestorManagementController::class);
    
    // Investor Requests Routes
    Route::get('investor-requests', [InvestorManagementController::class, 'investorRequests'])->name('investor-requests');
    Route::post('investor-requests/{request}/approve', [InvestorManagementController::class, 'approveRequest'])->name('investor-requests.approve');
    Route::post('investor-requests/{request}/reject', [InvestorManagementController::class, 'rejectRequest'])->name('investor-requests.reject');
    
    // Expense Approval Routes
    Route::get('expense-requests', [OwnerDashboardController::class, 'expenseRequests'])->name('expense.requests');
    Route::post('expense-requests/{expense}/approve', [OwnerDashboardController::class, 'approveExpense'])->name('expense.requests.approve');
    Route::post('expense-requests/{expense}/reject', [OwnerDashboardController::class, 'rejectExpense'])->name('expense.requests.reject');
    
    // Profit Distribution
    Route::get('profit-distribution', [OwnerDashboardController::class, 'profitDistribution'])->name('profit.distribution');
    Route::post('profit-distribution', [OwnerDashboardController::class, 'distributeProfit'])->name('profit.distribute');
    Route::get('profit-distribution/export', [OwnerDashboardController::class, 'exportProfitReport'])->name('profit.distribution.export');

    // Investor Management
    Route::patch('investors/{user}/approve', [OwnerDashboardController::class, 'approveInvestor'])->name('investors.approve');
    Route::patch('investors/{user}/reject', [OwnerDashboardController::class, 'rejectInvestor'])->name('investors.reject');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('profit-loss', [ReportController::class, 'profitAndLoss'])->name('profit-loss');
        Route::get('investor-pl/{investor}', [ReportController::class, 'investorProfitAndLoss'])->name('investor-pl');
        Route::get('ar-aging', [ReportController::class, 'arAging'])->name('ar-aging');
        Route::get('ap-aging', [ReportController::class, 'ap-aging'])->name('ap-aging');
    });
    
    // New Owner Routes from Sidebar
    Route::resource('users', UserController::class);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::resource('transactions', TransactionController::class)->except('show');
    Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::get('audit-logs', [AuditLogsController::class, 'index'])->name('audit-logs');
    Route::get('help-and-support', [HelpAndSupportController::class, 'index'])->name('help-and-support');
});

// Director routes
Route::middleware(['auth', 'role:director,owner'])->prefix('director')->name('director.')->group(function () {
    Route::get('/dashboard', [DirectorDashboardController::class, 'index'])->name('dashboard');
    Route::resource('purchases', PurchaseInvoiceController::class);
    Route::resource('sales', SalesInvoiceController::class);
    Route::get('sales/export', [SalesInvoiceController::class, 'export'])->name('sales.export');

    // Director Payments (Sales) Routes
    Route::get('sales-payments', [SalesPaymentController::class, 'index'])->name('sales-payments.index');
    Route::get('sales-payments/record/{salesInvoice}', [SalesPaymentController::class, 'recordPayment'])->name('sales-payments.record');
    Route::post('sales-payments/store', [SalesPaymentController::class, 'store'])->name('sales-payments.store');
    
    // Resource routes for sales-payments are now explicitly defined
    Route::get('sales-payments/{sales_payment}', [SalesPaymentController::class, 'show'])->name('sales-payments.show');
    Route::get('sales-payments/{sales_payment}/edit', [SalesPaymentController::class, 'edit'])->name('sales-payments.edit');
    Route::patch('sales-payments/{sales_payment}', [SalesPaymentController::class, 'update'])->name('sales-payments.update');
    Route::delete('sales-payments/{sales_payment}', [SalesPaymentController::class, 'destroy'])->name('sales-payments.destroy');

    Route::resource('expenses', ExpenseController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('inventory', InventoryController::class);
    Route::get('/customers', [DirectorDashboardController::class, 'customers'])->name('customers.index');
    Route::get('/payments', [SalesPaymentController::class, 'index'])->name('payments.index');
});

// Debug route - remove in production
Route::get('test-controller', function () {
    return class_exists(\App\Http\Controllers\Director\DirectorDashboardController::class)
        ? 'Class found'
        : 'Class NOT found';
});
