@extends('layouts.director')
@section('title', 'Expenses')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Expense Management</h1>
        <div class="flex space-x-4">
            <input type="text" placeholder="Search expenses..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            <a href="{{ route('director.expenses.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Add Expense
            </a>
        </div>
    </div>

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
                @forelse ($expenses as $expense)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">EXP-{{ $expense->id }}</td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $expense->expense_title }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $expense->category->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-semibold text-gray-900">${{ number_format($expense->amount, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $expense->expense_date->format('d M, Y') }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                                $statusClass = $statusColors[$expense->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $statusClass }}">
                                {{ ucfirst($expense->status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium space-x-2">
                            <a href="{{ route('director.expenses.show', $expense->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('director.expenses.edit', $expense->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
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

    <div class="mt-6 flex justify-center">
        {{ $expenses->links() }}
    </div>
</div>

@endsection
