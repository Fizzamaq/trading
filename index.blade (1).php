@extends('layouts.director')
@section('title', 'Expenses')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Expense Management</h1>
        <div class="flex space-x-4">
            <input type="text" placeholder="Search expenses..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Add Expense
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-red-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-red-200">Total Expenses</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format(45000, 2) }}</p>
        </div>
        <div class="bg-blue-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-blue-200">This Month</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format(8500, 2) }}</p>
        </div>
        <div class="bg-yellow-500 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-yellow-200">Pending</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format(2500, 2) }}</p>
        </div>
        <div class="bg-green-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-green-200">Approved</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format(42500, 2) }}</p>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Expense ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse (range(1, 10) as $i)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">EXP-{{ sprintf('%04d', $i) }}</td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ ['Office Supplies', 'Marketing Campaign', 'Equipment Purchase', 'Travel Expenses', 'Utilities', 'Software License'][array_rand(['Office Supplies', 'Marketing Campaign', 'Equipment Purchase', 'Travel Expenses', 'Utilities', 'Software License'])] }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ ['Operations', 'Marketing', 'IT', 'Travel', 'Utilities', 'Software'][array_rand(['Operations', 'Marketing', 'IT', 'Travel', 'Utilities', 'Software'])] }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-semibold text-gray-900">${{ number_format(rand(500, 2500), 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ now()->subDays(rand(1, 30))->format('d M, Y') }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @php $status = ['approved', 'pending', 'rejected'][array_rand(['approved', 'pending', 'rejected'])] @endphp
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                                @if($status === 'approved') bg-green-100 text-green-800
                                @elseif($status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium space-x-2">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">No expenses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        <div class="flex space-x-1">
            <button class="px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100">Previous</button>
            <button class="px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">1</button>
            <button class="px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">2</button>
            <button class="px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100">Next</button>
        </div>
    </div>
</div>

@endsection
