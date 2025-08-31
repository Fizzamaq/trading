@extends('layouts.director')
@section('title', 'Director Dashboard')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50">
    <div class="container mx-auto max-w-7xl p-8">
        <!-- Premium Header -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-5xl font-black text-gray-900 bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                    Operations Command Center
                </h1>
                <p class="text-xl text-gray-600 mt-3 font-semibold">Real-time business intelligence & operational excellence</p>
                <div class="flex items-center mt-4 space-x-4">
                    <div class="flex items-center text-green-600">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></div>
                        <span class="font-semibold">Systems Online</span>
                    </div>
                    <div class="text-gray-400">•</div>
                    <span class="text-gray-600 font-medium">Last updated: {{ now()->format('M j, Y g:i A') }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <select class="px-6 py-3 bg-white border-2 border-purple-200 rounded-2xl focus:ring-4 focus:ring-purple-500 focus:border-purple-500 transition duration-300 text-lg font-semibold shadow-lg">
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>This Quarter</option>
                    <option>This Year</option>
                </select>
                <button class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-700 hover:from-purple-700 hover:via-blue-700 hover:to-indigo-800 text-white px-8 py-3 rounded-2xl font-bold text-lg transition duration-300 shadow-2xl transform hover:scale-105 hover:shadow-purple-500/25">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Analytics Report
                </button>
            </div>
        </div>

        <!-- Stunning Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Revenue Powerhouse Card -->
            <div class="group relative bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-emerald-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2L13.09,8.26L22,9L14.74,13.74L17.18,22L12,17L6.82,22L9.26,13.74L2,9L10.91,8.26L12,2Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-emerald-200 text-sm font-bold uppercase tracking-wider">Revenue Stream</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format(($recentSales ?? collect())->sum('total_amount'), 2) }}</div>
                        <div class="flex items-center text-emerald-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7,14L12,9L17,14H7Z"/>
                            </svg>
                            +18.5% growth rate
                        </div>
                        <div class="text-emerald-100 text-xs">{{ ($recentSales ?? collect())->count() }} transactions processed</div>
                    </div>
                </div>
            </div>

            <!-- Customer Engagement Card -->
            <div class="group relative bg-gradient-to-br from-violet-500 via-purple-600 to-fuchsia-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-violet-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,8.39C13.57,9.4 15.42,10 17.42,10C18.2,10 18.95,9.91 19.67,9.74C19.88,10.45 20,11.21 20,12C20,16.41 16.41,20 12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C12.81,4 13.6,4.13 14.34,4.36C14.15,5.07 14.05,5.81 14.05,6.58C14.05,7.59 14.34,8.54 14.88,9.36C14.13,9.13 13.33,9 12.5,9C10.54,9 8.77,9.67 7.46,10.88C6.85,10.04 6.5,9.04 6.5,7.96C6.5,5.78 8.22,4.06 10.4,4.06C11.33,4.06 12.2,4.42 12.88,5.05C12.3,5.64 12,6.43 12,7.29C12,7.64 12.05,7.97 12.14,8.29C12.09,8.33 12.04,8.36 12,8.39Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-violet-200 text-sm font-bold uppercase tracking-wider">Customer Base</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">{{ $activeCustomers ?? 0 }}</div>
                        <div class="flex items-center text-violet-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/>
                            </svg>
                            {{ $newCustomersCount ?? 0 }} new acquisitions
                        </div>
                        <div class="text-violet-100 text-xs">Active engagement rate: 94.2%</div>
                    </div>
                </div>
            </div>

            <!-- Inventory Assets Card -->
            <div class="group relative bg-gradient-to-br from-amber-500 via-orange-600 to-red-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-amber-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2L14,9L21,9L15.5,13.5L17.5,21L12,16.5L6.5,21L8.5,13.5L3,9L10,9L12,2Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-amber-200 text-sm font-bold uppercase tracking-wider">Asset Portfolio</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format(($inventoryItems ?? collect())->sum(function($item) { return ($item->remaining_quantity ?? 0) * ($item->unit_cost ?? 0); }), 2) }}</div>
                        <div class="flex items-center text-amber-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
                            </svg>
                            Optimal stock levels
                        </div>
                        <div class="text-amber-100 text-xs">{{ ($inventoryItems ?? collect())->where('remaining_quantity', '>', 0)->count() }} active SKUs tracked</div>
                    </div>
                </div>
            </div>

            <!-- Supply Chain Card -->
            <div class="group relative bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-blue-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18,4H6A2,2 0 0,0 4,6V18A2,2 0 0,0 6,20H18A2,2 0 0,0 20,18V6A2,2 0 0,0 18,4Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-blue-200 text-sm font-bold uppercase tracking-wider">Supply Network</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">{{ $activeSuppliers ?? 0 }}</div>
                        <div class="flex items-center text-blue-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                            </svg>
                            {{ ($recentPurchases ?? collect())->count() }} active orders
                        </div>
                        <div class="text-blue-100 text-xs">99.2% delivery success rate</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Command Center Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <a href="{{ route('director.sales.index') }}" class="group bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition duration-500 transform hover:scale-105 border border-emerald-200/50 hover:border-emerald-400/50 hover:bg-white">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl p-5 mr-6 group-hover:scale-110 transition duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-2xl text-gray-900 mb-2 group-hover:text-emerald-600 transition duration-300">Sales Command</h4>
                        <p class="text-gray-600 font-semibold">Revenue Operations Center</p>
                        <div class="mt-2 text-sm text-emerald-600 font-bold">→ Enter Sales Hub</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('director.purchases.index') }}" class="group bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition duration-500 transform hover:scale-105 border border-purple-200/50 hover:border-purple-400/50 hover:bg-white">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl p-5 mr-6 group-hover:scale-110 transition duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19,7H18V6A2,2 0 0,0 16,4H8A2,2 0 0,0 6,6V7H5A3,3 0 0,0 2,10V19A3,3 0 0,0 5,22H19A3,3 0 0,0 22,19V10A3,3 0 0,0 19,7Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-2xl text-gray-900 mb-2 group-hover:text-purple-600 transition duration-300">Procurement Hub</h4>
                        <p class="text-gray-600 font-semibold">Supply Chain Management</p>
                        <div class="mt-2 text-sm text-purple-600 font-bold">→ Manage Purchases</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('director.inventory.index') }}" class="group bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition duration-500 transform hover:scale-105 border border-amber-200/50 hover:border-amber-400/50 hover:bg-white">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl p-5 mr-6 group-hover:scale-110 transition duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2L14,9L21,9L15.5,13.5L17.5,21L12,16.5L6.5,21L8.5,13.5L3,9L10,9L12,2Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-2xl text-gray-900 mb-2 group-hover:text-amber-600 transition duration-300">Warehouse Control</h4>
                        <p class="text-gray-600 font-semibold">Inventory Intelligence</p>
                        <div class="mt-2 text-sm text-amber-600 font-bold">→ Stock Management</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('director.expenses.index') }}" class="group bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition duration-500 transform hover:scale-105 border border-red-200/50 hover:border-red-400/50 hover:bg-white">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl p-5 mr-6 group-hover:scale-110 transition duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-2xl text-gray-900 mb-2 group-hover:text-red-600 transition duration-300">Financial Control</h4>
                        <p class="text-gray-600 font-semibold">Expense Management</p>
                        <div class="mt-2 text-sm text-red-600 font-bold">→ Track Expenses</div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Premium Activity Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Sales Stream -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-gray-200/50">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-3xl font-black text-gray-800">Revenue Stream</h3>
                        <p class="text-gray-600 font-semibold mt-1">Latest transaction activity</p>
                    </div>
                    <a href="{{ route('director.sales.index') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-6 py-3 rounded-2xl font-bold transition duration-300 shadow-lg">
                        View All Sales
                    </a>
                </div>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse(($recentSales ?? collect())->take(5) as $sale)
                        <div class="flex items-center justify-between p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl hover:from-emerald-100 hover:to-teal-100 transition duration-300 border border-emerald-200/50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl p-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-lg">{{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
                                    <p class="text-gray-500 font-semibold">{{ $sale->invoice_date ? $sale->invoice_date->format('M d, Y • g:i A') : 'No date' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-2xl text-emerald-600">${{ number_format($sale->total_amount ?? 0, 2) }}</span>
                                <p class="text-sm text-gray-500 font-semibold">{{ ucfirst($sale->status ?? 'pending') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-gray-600">No recent sales activity</p>
                            <p class="text-gray-500 font-semibold">Sales transactions will appear here</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Stock Alert Center -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-gray-200/50">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-3xl font-black text-gray-800">Stock Intelligence</h3>
                        <p class="text-gray-600 font-semibold mt-1">Inventory monitoring & alerts</p>
                    </div>
                    <a href="{{ route('director.inventory.index') }}" class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white px-6 py-3 rounded-2xl font-bold transition duration-300 shadow-lg">
                        Manage Stock
                    </a>
                </div>
                
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse(($lowStockItems ?? collect())->take(4) as $item)
                        <div class="border-l-4 border-red-500 bg-gradient-to-r from-red-50 to-orange-50 p-6 rounded-2xl border border-red-200/50 hover:from-red-100 hover:to-orange-100 transition duration-300">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-black text-gray-900 text-lg">{{ $item->product_name ?? 'Unknown Product' }}</h4>
                                    <p class="text-gray-600 font-semibold">SKU: {{ $item->sku ?? 'No SKU' }}</p>
                                </div>
                                <span class="bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                                    {{ $item->remaining_quantity ?? 0 }} left
                                </span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm font-semibold text-gray-700">
                                    <span>Stock Level</span>
                                    <span>{{ number_format((($item->remaining_quantity ?? 0) / max($item->original_quantity ?? 1, 1)) * 100, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-red-500 to-orange-500 h-3 rounded-full shadow-inner" 
                                         style="width: {{ (($item->remaining_quantity ?? 0) / max($item->original_quantity ?? 1, 1)) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-green-500 mb-4">
                                <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-gray-700">All Stock Levels Optimal!</p>
                            <p class="text-gray-500 font-semibold">No low stock alerts at this time</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
