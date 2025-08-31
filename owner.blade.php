@extends('layouts.app')
<!DOCTYPE html>
<html>
<head>
    <!-- ... other head elements ... -->
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS for additional effects -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .hover\:scale-105:hover { transform: scale(1.05); }
        .transition { transition: all 0.3s ease; }
    </style>
</head>
<body>
    <!-- ... body content ... -->
</body>
</html>

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
    <a class="nav-link text-white {{ request()->routeIs('owner.investor-requests') ? 'active bg-primary' : '' }}" href="{{ route('owner.investor-requests') }}">
        <i class="bi bi-file-earmark-text me-2"></i>Requests
    </a>
</nav>


@endsection
