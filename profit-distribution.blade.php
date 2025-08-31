@extends('layouts.owner')
@section('title', 'Profit Distribution')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900">Profit Distribution</h1>
            <p class="text-gray-600 mt-2">Distribute monthly profits to investors</p>
        </div>
        <div class="flex items-center space-x-4">
            <select class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200">
                <option>All Months</option>
                <option>Pending Only</option>
                <option>Distributed Only</option>
            </select>
            <button class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-xl font-semibold transition duration-200 shadow-lg">
                Export Report
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Available Profit</p>
                    <p class="text-3xl font-bold">${{ number_format($totalProfit ?? 0, 2) }}</p>
                    <p class="text-green-200 text-xs mt-1">Ready for distribution</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,6A1.5,1.5 0 0,1 13.5,7.5A1.5,1.5 0 0,1 12,9A1.5,1.5 0 0,1 10.5,7.5A1.5,1.5 0 0,1 12,6M17,18H7V16.75C7,15.03 10.5,14.17 12,14.17C13.5,14.17 17,15.03 17,16.75V18Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Distributed</p>
                    <p class="text-3xl font-bold">${{ number_format($distributedAmount ?? 0, 2) }}</p>
                    <p class="text-blue-200 text-xs mt-1">All time</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5,6H23V18H5V6M14,9A3,3 0 0,1 17,12A3,3 0 0,1 14,15A3,3 0 0,1 11,12A3,3 0 0,1 14,9M9,8A2,2 0 0,1 7,10V14A2,2 0 0,1 9,16H19A2,2 0 0,1 21,14V10A2,2 0 0,1 19,8H9Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Active Investors</p>
                    <p class="text-3xl font-bold">{{ ($monthlyProfits->first() && $monthlyProfits->first()->investorDistributions) ? $monthlyProfits->first()->investorDistributions->count() : 0 }}</p>
                    <p class="text-purple-200 text-xs mt-1">Receiving distributions</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16,4C16,2.89 16.89,2 18,2A2,2 0 0,1 20,4A2,2 0 0,1 18,6C16.89,6 16,5.11 16,4M4,18V22H2V18H4M18,22V18H20V22H18M2,6V4H4V6H2M22,6V4H24V6H22Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">This Month</p>
                    <p class="text-3xl font-bold">${{ number_format($monthlyProfits->where('profit_month', now()->startOfMonth())->first()->net_profit ?? 0, 2) }}</p>
                    <p class="text-orange-200 text-xs mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Available Months -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Quick Distribution -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Quick Distribution</h3>
            <form method="POST" action="{{ route('owner.profit.distribute') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select Month to Distribute</label>
                    <select name="month" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 text-lg">
                        @forelse($availableMonths ?? [] as $month)
                            <option value="{{ $month['value'] }}" {{ $month['distributed'] ? 'disabled' : '' }}>
                                {{ $month['label'] }} 
                                @if($month['distributed']) 
                                    (Already Distributed) 
                                @else 
                                    - Available
                                @endif
                            </option>
                        @empty
                            <option disabled>No months available</option>
                        @endforelse
                    </select>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-4 rounded-xl font-bold text-lg transition duration-200 shadow-lg transform hover:scale-105">
                    Distribute Profits
                </button>
            </form>
        </div>

        <!-- Distribution Summary -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Distribution Overview</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-green-50 rounded-xl">
                    <span class="font-semibold text-gray-700">Available for Distribution</span>
                    <span class="text-2xl font-bold text-green-600">${{ number_format($totalProfit ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-xl">
                    <span class="font-semibold text-gray-700">Already Distributed</span>
                    <span class="text-2xl font-bold text-blue-600">${{ number_format($distributedAmount ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-purple-50 rounded-xl border-2 border-purple-200">
                    <span class="font-bold text-gray-800">Total Profit</span>
                    <span class="text-2xl font-bold text-purple-600">${{ number_format(($totalProfit ?? 0) + ($distributedAmount ?? 0), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Profits Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800">Monthly Profit History</h3>
            <p class="text-gray-600 mt-1">Track profit generation and distribution status</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Month</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Revenue</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Expenses</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Net Profit</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Distributed Date</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($monthlyProfits ?? [] as $profit)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6">
                                <div class="text-lg font-semibold text-gray-900">{{ $profit->profit_month->format('F Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $profit->profit_month->format('M d, Y') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-lg font-bold text-blue-600">${{ number_format($profit->total_revenue ?? 0, 2) }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-lg font-bold text-red-600">${{ number_format($profit->total_expenses ?? 0, 2) }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-lg font-bold text-green-600">${{ number_format($profit->net_profit ?? 0, 2) }}</div>
                            </td>
                            <td class="px-8 py-6">
                                @if($profit->distribution_completed)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Distributed
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">
                                    {{ $profit->distribution_completed ? $profit->updated_at->format('M d, Y') : 'Not distributed' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $profit->distribution_completed ? $profit->updated_at->format('h:i A') : '' }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if(!$profit->distribution_completed)
                                    <form method="POST" action="{{ route('owner.profit.distribute') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="month" value="{{ $profit->profit_month->format('Y-m-d') }}">
                                        <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-200 shadow-md">
                                            Distribute
                                        </button>
                                    </form>
                                @else
                                    <button class="text-blue-600 hover:text-blue-900 font-semibold transition duration-200">View Details</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center">
                                <div class="text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900">No profit data found</p>
                                    <p class="text-gray-500">Monthly profits will appear here once generated</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(($monthlyProfits ?? collect())->hasPages())
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                {{ $monthlyProfits->links() }}
            </div>
        @endif
    </div>

    <!-- Distribution History -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800">Distribution History</h3>
            <p class="text-gray-600 mt-1">Recent profit distribution transactions</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Distribution ID</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Month</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Total Amount</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Investors</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Processed By</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($distributionHistory ?? [] as $distribution)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6">
                                <div class="text-lg font-semibold text-gray-900">#{{ $distribution->id }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $distribution->distribution_month->format('F Y') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-lg font-bold text-green-600">${{ number_format($distribution->total_amount ?? 0, 2) }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $distribution->total_investors ?? 0 }} investors</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $distribution->adminUser->name ?? 'System' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $distribution->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $distribution->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <button class="text-blue-600 hover:text-blue-900 font-semibold transition duration-200">View Report</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center">
                                <div class="text-gray-400">
                                    <p class="text-lg font-medium text-gray-900">No distribution history</p>
                                    <p class="text-gray-500">Distribution records will appear here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(($distributionHistory ?? collect())->hasPages())
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                {{ $distributionHistory->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
