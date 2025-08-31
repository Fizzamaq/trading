<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\InvestorManagementController;
use App\Http\Controllers\Director\DirectorDashboardController;
use App\Http\Controllers\Director\PurchaseInvoiceController;
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
Route::middleware(['auth', 'investor'])->group(function () {
    Route::get('/investor/onboarding', [OnboardingController::class, 'show'])->name('investor.onboarding');
    Route::post('/investor/onboarding/complete', [OnboardingController::class, 'complete'])->name('investor.onboarding.complete');
});

// Owner routes
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/investors', [OwnerDashboardController::class, 'investors'])->name('investors.index');
    Route::resource('investors', InvestorManagementController::class);
    
    // Investor Requests Routes
    Route::get('investor-requests', [InvestorManagementController::class, 'investorRequests'])->name('investor-requests');
    Route::post('investor-requests/{request}/approve', [InvestorManagementController::class, 'approveRequest'])->name('investor-requests.approve');
    Route::post('investor-requests/{request}/reject', [InvestorManagementController::class, 'rejectRequest'])->name('investor-requests.reject');
    
    // Profit Distribution
    Route::get('profit-distribution', [OwnerDashboardController::class, 'profitDistribution'])->name('profit.distribution');
    Route::post('profit-distribution', [OwnerDashboardController::class, 'distributeProfit'])->name('profit.distribute');
    
    // Investor Management
    Route::patch('investors/{user}/approve', [OwnerDashboardController::class, 'approveInvestor'])->name('investors.approve');
    Route::patch('investors/{user}/reject', [OwnerDashboardController::class, 'rejectInvestor'])->name('investors.reject');
    
    // Reports - commented out until controller is created
    // Route::get('reports', [App\Http\Controllers\Owner\ReportController::class, 'index'])->name('reports.index');
});

// Director routes - CLEAN VERSION
Route::middleware(['auth', 'role:director,owner'])->prefix('director')->name('director.')->group(function () {
    Route::get('/dashboard', [DirectorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/purchases', [DirectorDashboardController::class, 'purchases'])->name('purchases.index');
    Route::get('/sales', [DirectorDashboardController::class, 'sales'])->name('sales.index');
    Route::get('/customers', [DirectorDashboardController::class, 'customers'])->name('customers.index');
    Route::get('/suppliers', [DirectorDashboardController::class, 'suppliers'])->name('suppliers.index');
    Route::get('/inventory', [DirectorDashboardController::class, 'inventory'])->name('inventory.index');
    Route::get('/payments', [DirectorDashboardController::class, 'payments'])->name('payments.index');
    Route::get('/expenses', [DirectorDashboardController::class, 'expenses'])->name('expenses.index');
});

// Investor routes
Route::middleware(['auth', 'investor', 'investor.onboarding'])->prefix('investor')->name('investor.')->group(function () {
    Route::get('/dashboard', [InvestorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/requests/create', [InvestorDashboardController::class, 'createRequest'])->name('requests.create');
    Route::post('/requests', [InvestorDashboardController::class, 'storeRequest'])->name('requests.store');
    Route::get('/investors', [OwnerDashboardController::class, 'investors'])->name('owner.investors.index');
});

// Debug route - remove in production
Route::get('test-controller', function () {
    return class_exists(\App\Http\Controllers\Director\DirectorDashboardController::class)
        ? 'Class found'
        : 'Class NOT found';
});
