@extends('layouts.owner')
@section('title', 'Investor Management')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900">Investor Management</h1>
            <p class="text-gray-600 mt-2">Manage all investors and their investments</p>
        </div>
        <div class="flex items-center space-x-4">
            <input type="text" placeholder="Search investors..." class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
            <button class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-2 rounded-xl font-semibold transition duration-200 shadow-lg">
                Add Investor
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Active Investors</p>
                    <p class="text-3xl font-bold">{{ $investors->where('user.status', 'active')->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16,4C16,2.89 16.89,2 18,2A2,2 0 0,1 20,4A2,2 0 0,1 18,6C16.89,6 16,5.11 16,4M4,18V22H2V18H4M18,22V18H20V22H18M2,6V4H4V6H2M22,6V4H24V6H22Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Investment</p>
                    <p class="text-3xl font-bold">${{ number_format($investors->sum('investment_amount'), 2) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5,6H23V18H5V6M14,9A3,3 0 0,1 17,12A3,3 0 0,1 14,15A3,3 0 0,1 11,12A3,3 0 0,1 14,9M9,8A2,2 0 0,1 7,10V14A2,2 0 0,1 9,16H19A2,2 0 0,1 21,14V10A2,2 0 0,1 19,8H9Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Avg Investment</p>
                    <p class="text-3xl font-bold">${{ number_format($investors->avg('investment_amount'), 2) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">New This Month</p>
                    <p class="text-3xl font-bold">{{ $investors->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Investors Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800">All Investors</h3>
            <p class="text-gray-600 mt-1">Complete investor portfolio overview</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Investor</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Contact</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Investment</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">ROI</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Joined</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($investors as $investor)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($investor->user->name ?? 'N', 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-semibold text-gray-900">{{ $investor->user->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">ID: #{{ $investor->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $investor->user->email ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $investor->user->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-lg font-bold text-green-600">${{ number_format($investor->investment_amount ?? 0, 2) }}</div>
                                <div class="text-sm text-gray-500">{{ number_format($investor->ownership_percentage ?? 0, 2) }}% ownership</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-lg font-bold text-blue-600">12.5%</div>
                                <div class="text-sm text-gray-500">Annual return</div>
                            </td>
                            <td class="px-8 py-6">
                                @if($investor->user->status === 'active')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($investor->user->status === 'pending')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $investor->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $investor->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900 font-semibold transition duration-200">View</button>
                                    <button class="text-indigo-600 hover:text-indigo-900 font-semibold transition duration-200">Edit</button>
                                    @if($investor->user->status === 'pending')
                                        <form method="POST" action="{{ route('owner.investors.approve', $investor->user->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900 font-semibold transition duration-200">Approve</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center">
                                <div class="text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900">No investors found</p>
                                    <p class="text-gray-500">Add investors to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($investors->hasPages())
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                {{ $investors->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
