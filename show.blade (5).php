@extends('layouts.director')
@section('title', 'View Expense')
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Expense Details</h1>
        <div class="flex space-x-4">
            <a href="{{ route('director.expenses.edit', $expense->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Edit Expense
            </a>
            <a href="{{ route('director.expenses.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Expense Title</p>
                <p class="text-lg font-bold text-gray-900">{{ $expense->expense_title }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Amount</p>
                <p class="text-lg font-bold text-gray-900">${{ number_format($expense->amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Category</p>
                <p class="text-lg text-gray-900">{{ $expense->category->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Date</p>
                <p class="text-lg text-gray-900">{{ $expense->expense_date->format('M d, Y') }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-semibold uppercase text-gray-500">Description</p>
                <p class="text-lg text-gray-900">{{ $expense->description ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Recorded By</p>
                <p class="text-lg text-gray-900">{{ $expense->creator->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>

@endsection