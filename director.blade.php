@extends('layouts.app')
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .hover\:scale-105:hover { transform: scale(1.05); }
        .transition { transition: all 0.3s ease; }
    </style>
</head>
<body>
    @section('navigation')
        <nav class="nav flex-column">
            <a class="nav-link text-white {{ request()->routeIs('director.dashboard') ? 'active bg-primary' : '' }}" href="{{ route('director.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a class="nav-link text-white {{ request()->routeIs('director.purchases.*') ? 'active bg-primary' : '' }}" href="{{ route('director.purchases.index') }}">
                <i class="bi bi-cart-plus me-2"></i>Purchases
            </a>
            <a class="nav-link text-white {{ request()->routeIs('director.sales.*') ? 'active bg-primary' : '' }}" href="{{ route('director.sales.index') }}">
                <i class="bi bi-cart-check me-2"></i>Sales
            </a>
            <a class="nav-link text-white {{ request()->routeIs('director.customers.*') ? 'active bg-primary' : '' }}" href="{{ route('director.customers.index') }}">
                <i class="bi bi-people me-2"></i>Customers
            </a>
            <a class="nav-link text-white {{ request()->routeIs('director.suppliers.*') ? 'active bg-primary' : '' }}" href="{{ route('director.suppliers.index') }}">
                <i class="bi bi-truck me-2"></i>Suppliers
            </a>
            <a class="nav-link text-white {{ request()->routeIs('director.inventory.*') ? 'active bg-primary' : '' }}" href="{{ route('director.inventory.index') }}">
                <i class="bi bi-box-seam me-2"></i>Inventory
            </a>
            <a class="nav-link text-white {{ request()->routeIs('director.payments.*') ? 'active bg-primary' : '' }}" href="{{ route('director.payments.index') }}">
                <i class="bi bi-cash-coin me-2"></i>Payments
            </a>
            <a class="nav-link text-white {{ request()->routeIs('director.expenses.*') ? 'active bg-primary' : '' }}" href="{{ route('director.expenses.index') }}">
                <i class="bi bi-receipt me-2"></i>Expenses
            </a>
        </nav>
    @endsection
</body>
</html>
