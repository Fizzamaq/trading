@extends('layouts.app')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .hover\:scale-105:hover { transform: scale(1.05); }
        .transition { transition: all 0.3s ease; }
    </style>
@endpush

@section('navigation')
<nav class="nav flex-column">
    <a class="nav-link text-white {{ request()->routeIs('owner.dashboard') ? 'active bg-primary' : '' }}" href="{{ route('owner.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.investors.*') ? 'active bg-primary' : '' }}" href="{{ route('owner.investors.index') }}">
        <i class="bi bi-people me-2"></i>Investors
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.profit.*') ? 'active bg-primary' : '' }}" href="{{ route('owner.profit.distribution') }}">
        <i class="bi bi-currency-dollar me-2"></i>Profit Distribution
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.investor-requests') || request()->routeIs('owner.expense.requests') ? 'active bg-primary' : '' }}" href="{{ route('owner.investor-requests') }}">
        <i class="bi bi-file-earmark-text me-2"></i>Requests
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.reports.*') ? 'active bg-primary' : '' }}" href="{{ route('owner.reports.index') }}">
        <i class="bi bi-bar-chart-line me-2"></i>Reports & Analytics
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.users.*') ? 'active bg-primary' : '' }}" href="{{ route('owner.users.index') }}">
        <i class="bi bi-people-fill me-2"></i>User Management
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.settings.*') ? 'active bg-primary' : '' }}" href="{{ route('owner.settings.index') }}">
        <i class="bi bi-gear me-2"></i>Settings
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.transactions.*') ? 'active bg-primary' : '' }}" href="{{ route('owner.transactions.index') }}">
        <i class="bi bi-journal-text me-2"></i>Transactions
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.audit-logs') ? 'active bg-primary' : '' }}" href="{{ route('owner.audit-logs') }}">
        <i class="bi bi-list-check me-2"></i>Audit Logs
    </a>
    <a class="nav-link text-white {{ request()->routeIs('owner.help-and-support') ? 'active bg-primary' : '' }}" href="{{ route('owner.help-and-support') }}">
        <i class="bi bi-question-circle me-2"></i>Help & Support
    </a>
</nav>
@endsection
