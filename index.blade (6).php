@extends('layouts.director')
@section('title', 'Sales Management')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="container mx-auto max-w-7xl p-8">
        <!-- Premium Header -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-5xl font-black text-gray-900 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Sales Command Center
                </h1>
                <p class="text-xl text-gray-600 mt-3 font-semibold">Revenue operations & customer transaction management</p>
                <div class="flex items-center mt-4 space-x-4">
                    <div class="flex items-center text-blue-600">
                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse mr-2"></div>
                        <span class="font-semibold">Live Sales Tracking</span>
                    </div>
                    <div class="text-gray-400">•</div>
                    <span class="text-gray-600 font-medium">{{ ($sales ?? collect())->count() }} total transactions</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search transactions..." 
                       class="px-6 py-3 bg-white/80 backdrop-blur-lg border-2 border-blue-200 rounded-2xl focus:ring-4 focus:ring-blue-500 focus:border-blue-500 transition duration-300 text-lg font-semibold shadow-lg placeholder-gray-500">
                <select class="px-6 py-3 bg-white/80 backdrop-blur-lg border-2 border-blue-200 rounded-2xl focus:ring-4 focus:ring-blue-500 focus:border-blue-500 transition duration-300 text-lg font-semibold shadow-lg">
                    <option>All Status</option>
                    <option>Paid</option>
                    <option>Pending</option>
                    <option>Overdue</option>
                </select>
                <button class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-800 text-white px-8 py-3 rounded-2xl font-bold text-lg transition duration-300 shadow-2xl transform hover:scale-105">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/>
                    </svg>
                    New Sale
                </button>
            </div>
        </div>

        <!-- Revenue Analytics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Total Revenue Card -->
            <div class="group relative bg-gradient-to-br from-emerald-500 via-green-600 to-teal-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-emerald-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2L13.09,8.26L22,9L14.74,13.74L17.18,22L12,17L6.82,22L9.26,13.74L2,9L10.91,8.26L12,2Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-emerald-200 text-sm font-bold uppercase tracking-wider">Total Revenue</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format(($sales ?? collect())->sum('total_amount'), 2) }}</div>
                        <div class="flex items-center text-emerald-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7,14L12,9L17,14H7Z"/>
                            </svg>
                            +{{ number_format((($sales ?? collect())->count() / max(1, 30)) * 100, 1) }}% growth
                        </div>
                        <div class="text-emerald-100 text-xs">{{ ($sales ?? collect())->count() }} total transactions</div>
                    </div>
                </div>
            </div>

            <!-- Paid Sales Card -->
            <div class="group relative bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-blue-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-blue-200 text-sm font-bold uppercase tracking-wider">Completed Sales</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format(($sales ?? collect())->where('status', 'paid')->sum('total_amount'), 2) }}</div>
                        <div class="flex items-center text-blue-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
                            </svg>
                            {{ ($sales ?? collect())->where('status', 'paid')->count() }} paid transactions
                        </div>
                        <div class="text-blue-100 text-xs">{{ number_format((($sales ?? collect())->where('status', 'paid')->count() / max(($sales ?? collect())->count(), 1)) * 100, 1) }}% completion rate</div>
                    </div>
                </div>
            </div>

            <!-- Pending Sales Card -->
            <div class="group relative bg-gradient-to-br from-amber-500 via-orange-600 to-yellow-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-amber-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,6A1.5,1.5 0 0,1 13.5,7.5A1.5,1.5 0 0,1 12,9A1.5,1.5 0 0,1 10.5,7.5A1.5,1.5 0 0,1 12,6M17,18H7V16.75C7,15.03 10.5,14.17 12,14.17C13.5,14.17 17,15.03 17,16.75V18Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-amber-200 text-sm font-bold uppercase tracking-wider">Pending Payment</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format(($sales ?? collect())->where('status', 'pending')->sum('total_amount'), 2) }}</div>
                        <div class="flex items-center text-amber-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                            </svg>
                            {{ ($sales ?? collect())->where('status', 'pending')->count() }} awaiting payment
                        </div>
                        <div class="text-amber-100 text-xs">Requires follow-up action</div>
                    </div>
                </div>
            </div>

            <!-- Average Transaction Card -->
            <div class="group relative bg-gradient-to-br from-violet-500 via-purple-600 to-fuchsia-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-violet-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-violet-200 text-sm font-bold uppercase tracking-wider">Avg Transaction</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format(($sales ?? collect())->avg('total_amount') ?? 0, 2) }}</div>
                        <div class="flex items-center text-violet-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2L13.09,8.26L22,9L14.74,13.74L17.18,22L12,17L6.82,22L9.26,13.74L2,9L10.91,8.26L12,2Z"/>
                            </svg>
                            Optimal pricing strategy
                        </div>
                        <div class="text-violet-100 text-xs">Per customer transaction</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Action Center -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <button class="group bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition duration-500 transform hover:scale-105 border border-blue-200/50 hover:border-blue-400/50 hover:bg-white text-left">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-5 mr-6 group-hover:scale-110 transition duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-2xl text-gray-900 mb-2 group-hover:text-blue-600 transition duration-300">Create Invoice</h4>
                        <p class="text-gray-600 font-semibold">New customer sale</p>
                        <div class="mt-2 text-sm text-blue-600 font-bold">→ Generate Invoice</div>
                    </div>
                </div>
            </button>

            <button class="group bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition duration-500 transform hover:scale-105 border border-green-200/50 hover:border-green-400/50 hover:bg-white text-left">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-5 mr-6 group-hover:scale-110 transition duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-2xl text-gray-900 mb-2 group-hover:text-green-600 transition duration-300">Record Payment</h4>
                        <p class="text-gray-600 font-semibold">Process transaction</p>
                        <div class="mt-2 text-sm text-green-600 font-bold">→ Update Payment</div>
                    </div>
                </div>
            </button>

            <button class="group bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition duration-500 transform hover:scale-105 border border-purple-200/50 hover:border-purple-400/50 hover:bg-white text-left">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-purple-500 to-violet-600 rounded-2xl p-5 mr-6 group-hover:scale-110 transition duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-2xl text-gray-900 mb-2 group-hover:text-purple-600 transition duration-300">Sales Analytics</h4>
                        <p class="text-gray-600 font-semibold">Performance insights</p>
                        <div class="mt-2 text-sm text-purple-600 font-bold">→ View Reports</div>
                    </div>
                </div>
            </button>

            <button class="group bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 hover:shadow-3xl transition duration-500 transform hover:scale-105 border border-orange-200/50 hover:border-orange-400/50 hover:bg-white text-left">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl p-5 mr-6 group-hover:scale-110 transition duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-2xl text-gray-900 mb-2 group-hover:text-orange-600 transition duration-300">Send Reminders</h4>
                        <p class="text-gray-600 font-semibold">Payment follow-ups</p>
                        <div class="mt-2 text-sm text-orange-600 font-bold">→ Notify Customers</div>
                    </div>
                </div>
            </button>
        </div>

        <!-- Premium Sales Transactions Table -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-gray-200/50">
            <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-black text-gray-800">Transaction Registry</h3>
                        <p class="text-gray-600 font-semibold mt-1">Complete sales transaction management system</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-bold">
                            {{ ($sales ?? collect())->count() }} Total Records
                        </span>
                        <button class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-2 rounded-2xl font-bold hover:from-blue-600 hover:to-indigo-700 transition duration-300">
                            Export Data
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                        <tr>
                            <th class="px-8 py-6 text-left text-sm font-black uppercase tracking-wider text-gray-700">Transaction ID</th>
                            <th class="px-8 py-6 text-left text-sm font-black uppercase tracking-wider text-gray-700">Customer Details</th>
                            <th class="px-8 py-6 text-left text-sm font-black uppercase tracking-wider text-gray-700">Transaction Date</th>
                            <th class="px-8 py-6 text-left text-sm font-black uppercase tracking-wider text-gray-700">Amount</th>
                            <th class="px-8 py-6 text-left text-sm font-black uppercase tracking-wider text-gray-700">Payment Status</th>
                            <th class="px-8 py-6 text-left text-sm font-black uppercase tracking-wider text-gray-700">Balance</th>
                            <th class="px-8 py-6 text-left text-sm font-black uppercase tracking-wider text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($sales ?? [] as $sale)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-lg shadow-lg group-hover:scale-110 transition duration-300">
                                            {{ substr($sale->id ?? '01', -2) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-lg font-black text-gray-900">INV-{{ $sale->id ?? '001' }}</div>
                                            <div class="text-sm font-semibold text-gray-500">{{ $sale->invoice_number ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
                                            {{ substr($sale->customer->name ?? 'W', 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-lg font-bold text-gray-900">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                                            <div class="text-sm font-semibold text-gray-500">{{ $sale->customer->email ?? 'No email provided' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-lg font-bold text-gray-900">{{ $sale->invoice_date ? $sale->invoice_date->format('M d, Y') : 'No date' }}</div>
                                    <div class="text-sm font-semibold text-gray-500">{{ $sale->invoice_date ? $sale->invoice_date->format('g:i A') : '' }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-2xl font-black text-emerald-600">${{ number_format($sale->total_amount ?? 0, 2) }}</div>
                                    <div class="text-sm font-semibold text-gray-500">{{ $sale->currency ?? 'USD' }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $status = $sale->status ?? 'pending';
                                        $statusConfig = [
                                            'paid' => ['bg-emerald-100 text-emerald-800 border-emerald-300', 'Fully Paid'],
                                            'pending' => ['bg-amber-100 text-amber-800 border-amber-300', 'Payment Pending'],
                                            'overdue' => ['bg-red-100 text-red-800 border-red-300', 'Payment Overdue'],
                                            'cancelled' => ['bg-gray-100 text-gray-800 border-gray-300', 'Cancelled']
                                        ];
                                        $config = $statusConfig[$status] ?? $statusConfig['pending'];
                                    @endphp
                                    <span class="inline-flex px-4 py-2 text-sm font-black rounded-full border-2 {{ $config[0] }}">
                                        {{ $config[1] }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $paidAmount = ($sale->total_amount ?? 0) - ($sale->remaining_amount ?? 0);
                                        $remainingAmount = $sale->remaining_amount ?? 0;
                                    @endphp
                                    <div class="text-lg font-bold text-blue-600">${{ number_format($paidAmount, 2) }}</div>
                                    <div class="text-sm font-semibold {{ $remainingAmount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        @if($remainingAmount > 0)
                                            ${{ number_format($remainingAmount, 2) }} due
                                        @else
                                            Balance cleared
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-3">
                                        <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-xl font-bold transition duration-300 shadow-md hover:shadow-lg">
                                            View
                                        </button>
                                        @if(($sale->remaining_amount ?? 0) > 0)
                                            <button class="bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-4 py-2 rounded-xl font-bold transition duration-300 shadow-md hover:shadow-lg">
                                                Pay
                                            </button>
                                        @endif
                                        <button class="bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-2 rounded-xl font-bold transition duration-300 shadow-md hover:shadow-lg">
                                            Print
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-16 text-center">
                                    <div class="text-gray-400 space-y-4">
                                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-2xl font-black text-gray-600">No Sales Transactions Found</p>
                                            <p class="text-lg text-gray-500 font-semibold mt-2">Create your first sales invoice to get started</p>
                                        </div>
                                        <button class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:from-blue-600 hover:to-indigo-700 transition duration-300 shadow-xl">
                                            Create First Sale
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(($sales ?? collect())->hasPages())
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-semibold text-gray-600">
                            Showing {{ ($sales ?? collect())->count() }} of {{ ($sales ?? collect())->total() ?? 0 }} transactions
                        </div>
                        <div class="flex space-x-2">
                            {{ $sales->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
