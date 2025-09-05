@extends('layouts.director')
@section('title', 'Add Expense')
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Add New Expense</h1>
        <a href="{{ route('director.expenses.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
            Back to Expenses
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <form action="{{ route('director.expenses.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="expense_title" class="block text-sm font-medium text-gray-700 mb-2">Expense Title</label>
                    <input type="text" name="expense_title" id="expense_title" value="{{ old('expense_title') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
                        <option value="">Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">{{ old('description') }}</textarea>
                </div>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-8 py-4 rounded-xl font-bold text-lg transition duration-200 shadow-lg">
                    Record Expense
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
