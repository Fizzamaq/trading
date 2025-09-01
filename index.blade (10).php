@extends('layouts.owner')
@section('title', 'Reports & Analytics')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Reports & Analytics</h1>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <a href="{{ route('owner.reports.profit-loss') }}" class="block bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition duration-300 hover:shadow-2xl border-l-4 border-green-500">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-green-500 p-3 rounded-xl text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Profit & Loss</h3>
            </div>
            <p class="text-gray-600">Analyze business revenue and expenses over a period.</p>
        </a>

        <a href="{{ route('owner.reports.ar-aging') }}" class="block bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition duration-300 hover:shadow-2xl border-l-4 border-blue-500">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-blue-500 p-3 rounded-xl text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">AR Aging</h3>
            </div>
            <p class="text-gray-600">Track outstanding payments from customers.</p>
        </a>
        
        <a href="{{ route('owner.reports.ap-aging') }}" class="block bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition duration-300 hover:shadow-2xl border-l-4 border-orange-500">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-orange-500 p-3 rounded-xl text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">AP Aging</h3>
            </div>
            <p class="text-gray-600">Monitor outstanding payments owed to suppliers.</p>
        </a>

        <a href="{{ route('owner.investors.index') }}" class="block bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition duration-300 hover:shadow-2xl border-l-4 border-purple-500">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-purple-500 p-3 rounded-xl text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Investor P&L</h3>
            </div>
            <p class="text-gray-600">View performance reports for individual investors.</p>
        </a>
    </div>
</div>

@endsection