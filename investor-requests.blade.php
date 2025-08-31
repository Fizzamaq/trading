@extends('layouts.owner')
@section('title', 'Investor Requests')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900">Investor Requests</h1>
            <p class="text-gray-600 mt-2">Review and manage pending investor applications</p>
        </div>
        <div class="flex items-center space-x-4">
            <input type="text" placeholder="Search requests..." class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
            <button class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2 rounded-xl font-semibold transition duration-200 shadow-lg">
                Export List
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Requests</p>
                    <p class="text-3xl font-bold">{{ $investorRequests->total() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zM4 18v-4h3v4h2v-7.5c0-.83.67-1.5 1.5-1.5h2c.83 0 1.5.67 1.5 1.5V18h2v-4h3v4h2v2H2v-2h1v2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium mb-1">Pending Review</p>
                    <p class="text-3xl font-bold">{{ $investorRequests->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,6A1.5,1.5 0 0,1 13.5,7.5A1.5,1.5 0 0,1 12,9A1.5,1.5 0 0,1 10.5,7.5A1.5,1.5 0 0,1 12,6M12,20C10.5,20 9.13,19.5 8,18.68V17.5C8,16.12 10.5,15.5 12,15.5C13.5,15.5 16,16.12 16,17.5V18.68C14.87,19.5 13.5,20 12,20Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Approved Today</p>
                    <p class="text-3xl font-bold">{{ $investorRequests->where('status', 'approved')->where('updated_at', '>=', now()->startOfDay())->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Rejected</p>
                    <p class="text-3xl font-bold">{{ $investorRequests->where('status', 'rejected')->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800">Investor Applications</h3>
            <p class="text-gray-600 mt-1">Review and process investor requests</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Applicant</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Contact Info</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Investment Amount</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Request Date</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($investorRequests as $request)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($request->investor->user->name ?? 'N', 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-semibold text-gray-900">{{ $request->investor->user->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">ID: #{{ $request->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $request->investor->user->email ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $request->investor->user->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-lg font-bold text-green-600">${{ number_format($request->investment_amount ?? 0, 2) }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $request->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $request->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                @if($request->status === 'pending')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending Review
                                    </span>
                                @elseif($request->status === 'approved')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                @if($request->status === 'pending')
                                    <div class="flex space-x-2">
                                        <form method="POST" action="{{ route('owner.investor-requests.approve', $request->id) }}" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-200 shadow-md">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('owner.investor-requests.reject', $request->id) }}" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-200 shadow-md">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <button class="text-blue-600 hover:text-blue-900 font-semibold transition duration-200">View Details</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center">
                                <div class="text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900">No investor requests found</p>
                                    <p class="text-gray-500">New requests will appear here when submitted</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($investorRequests->hasPages())
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                {{ $investorRequests->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
