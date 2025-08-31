@extends('layouts.owner')
@section('title', 'Owner Dashboard')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Owner Dashboard</h1>
        <div class="flex items-center space-x-4">
            @if($pendingRequests > 0)
                <a href="{{ route('owner.investor-requests') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                    {{ $pendingRequests }} Pending Request{{ $pendingRequests > 1 ? 's' : '' }}
                </a>
            @endif
        </div>
    </div>

    <!-- Original Attractive Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold">${{ number_format($totalSales ?? 0, 2) }}</p>
                    <p class="text-blue-200 text-xs mt-1">↗ +12.5% from last month</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 9.85 9 11 5.16-1.15 9-5.45 9-11V7l-10-5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Profit Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Net Profit</p>
                    <p class="text-3xl font-bold">${{ number_format(($totalSales ?? 0) - ($totalExpenses ?? 0), 2) }}</p>
                    <p class="text-green-200 text-xs mt-1">↗ +8.2% from last month</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Investment Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Total Investment</p>
                    <p class="text-3xl font-bold">${{ number_format($totalInvestment ?? 0, 2) }}</p>
                    <p class="text-purple-200 text-xs mt-1">{{ $totalInvestors ?? 0 }} active investors</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 5 23H19C20.11 23 21 22.11 21 21V11L15 5V9H21Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Expenses Card -->
        <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Total Expenses</p>
                    <p class="text-3xl font-bold">${{ number_format($totalExpenses ?? 0, 2) }}</p>
                    <p class="text-red-200 text-xs mt-1">↘ -3.1% from last month</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- P&L Overview -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Monthly Performance</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-xl">
                    <span class="font-semibold text-gray-700">Revenue</span>
                    <span class="text-2xl font-bold text-blue-600">${{ number_format($totalSales ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-red-50 rounded-xl">
                    <span class="font-semibold text-gray-700">Expenses</span>
                    <span class="text-2xl font-bold text-red-600">${{ number_format($totalExpenses ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-green-50 rounded-xl border-2 border-green-200">
                    <span class="font-bold text-gray-800">Net Profit</span>
                    <span class="text-2xl font-bold text-green-600">${{ number_format(($totalSales ?? 0) - ($totalExpenses ?? 0), 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Recent Activity</h3>
            <div class="space-y-4 max-h-80 overflow-y-auto">
                @forelse($recentSales ?? [] as $sale)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-500 rounded-full p-2">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $sale->customer->name ?? 'Sale' }}</p>
                                <p class="text-sm text-gray-500">{{ $sale->invoice_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <span class="font-bold text-green-600">+${{ number_format($sale->total_amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 italic text-center py-8">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
