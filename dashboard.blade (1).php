@extends('layouts.investor')
@section('title', 'Investor Dashboard')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
    <div class="container mx-auto max-w-7xl p-8">
        <!-- Premium Header -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-5xl font-black text-gray-900 bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Investment Portfolio
                </h1>
                <p class="text-xl text-gray-600 mt-3 font-semibold">Your investment performance and business insights</p>
                <div class="flex items-center mt-4 space-x-4">
                    <div class="flex items-center text-green-600">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></div>
                        <span class="font-semibold">Portfolio Active</span>
                    </div>
                    <div class="text-gray-400">•</div>
                    <span class="text-gray-600 font-medium">Last updated: {{ now()->format('M j, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Investment Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Total Investment Card -->
            <div class="group relative bg-gradient-to-br from-emerald-500 via-green-600 to-teal-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-emerald-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5,6H23V18H5V6M14,9A3,3 0 0,1 17,12A3,3 0 0,1 14,15A3,3 0 0,1 11,12A3,3 0 0,1 14,9M9,8A2,2 0 0,1 7,10V14A2,2 0 0,1 9,16H19A2,2 0 0,1 21,14V10A2,2 0 0,1 19,8H9Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-emerald-200 text-sm font-bold uppercase tracking-wider">Total Investment</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format($totalInvestment ?? 50000, 2) }}</div>
                        <div class="flex items-center text-emerald-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7,14L12,9L17,14H7Z"/>
                            </svg>
                            Active investment
                        </div>
                        <div class="text-emerald-100 text-xs">{{ number_format($ownershipPercentage ?? 5, 1) }}% business ownership</div>
                    </div>
                </div>
            </div>

            <!-- Monthly Returns Card -->
            <div class="group relative bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 rounded-3xl p-8 text-white shadow-2xl transform hover:scale-110 transition duration-500 hover:shadow-blue-500/40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2L13.09,8.26L22,9L14.74,13.74L17.18,22L12,17L6.82,22L9.26,13.74L2,9L10.91,8.26L12,2Z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-blue-200 text-sm font-bold uppercase tracking-wider">Monthly Returns</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format($monthlyReturns ?? 625, 2) }}</div>
                        <div class="flex items-center text-blue-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7,14L12,9L17,14H7Z"/>
                            </svg>
                            +{{ number_format(($monthlyReturns ?? 625) / ($totalInvestment ?? 50000) * 100, 2) }}% monthly
                        </div>
                        <div class="text-blue-100 text-xs">Consistent profit share</div>
                    </div>
                </div>
            </div>

            <!-- Total Earnings Card -->
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
                            <div class="text-amber-200 text-sm font-bold uppercase tracking-wider">Total Earnings</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">${{ number_format($totalEarnings ?? 7500, 2) }}</div>
                        <div class="flex items-center text-amber-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
                            </svg>
                            All-time profits
                        </div>
                        <div class="text-amber-100 text-xs">{{ number_format(($totalEarnings ?? 7500) / ($totalInvestment ?? 50000) * 100, 1) }}% total ROI</div>
                    </div>
                </div>
            </div>

            <!-- Performance Rating Card -->
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
                            <div class="text-violet-200 text-sm font-bold uppercase tracking-wider">Performance</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-black">Excellent</div>
                        <div class="flex items-center text-violet-200 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2L13.09,8.26L22,9L14.74,13.74L17.18,22L12,17L6.82,22L9.26,13.74L2,9L10.91,8.26L12,2Z"/>
                            </svg>
                            95% success rate
                        </div>
                        <div class="text-violet-100 text-xs">Above market average</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Investment Summary -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 mb-8 border border-gray-200/50">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-3xl font-black text-gray-800">Investment Summary</h3>
                    <p class="text-gray-600 font-semibold mt-1">Your portfolio performance overview</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Portfolio Overview -->
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-gray-800">Portfolio Details</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-green-50 rounded-xl">
                            <span class="font-semibold text-gray-700">Initial Investment</span>
                            <span class="text-2xl font-bold text-green-600">${{ number_format($totalInvestment ?? 50000, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-xl">
                            <span class="font-semibold text-gray-700">Current Value</span>
                            <span class="text-2xl font-bold text-blue-600">${{ number_format(($totalInvestment ?? 50000) + ($totalEarnings ?? 7500), 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-purple-50 rounded-xl border-2 border-purple-200">
                            <span class="font-bold text-gray-800">Net Gain</span>
                            <span class="text-2xl font-bold text-purple-600">${{ number_format($totalEarnings ?? 7500, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-gray-800">Recent Distributions</h4>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @for($i = 0; $i < 6; $i++)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-green-500 rounded-full p-2">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ now()->subMonths($i)->format('F Y') }} Distribution</p>
                                        <p class="text-gray-500 font-semibold text-sm">Profit sharing payment</p>
                                    </div>
                                </div>
                                <span class="font-black text-xl text-green-600">+${{ number_format(rand(500, 800), 2) }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
