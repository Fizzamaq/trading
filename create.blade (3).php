@extends('layouts.owner')
@section('title', 'Add New Investor')
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Add New Investor</h1>
        <a href="{{ route('owner.investors.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
            Back to Investors
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
        <form action="{{ route('owner.investors.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="investment_amount" class="block text-sm font-medium text-gray-700 mb-2">Investment Amount (PKR)</label>
                    <input type="number" name="investment_amount" id="investment_amount" value="{{ old('investment_amount') }}" step="0.01" min="1000" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="profit_percentage" class="block text-sm font-medium text-gray-700 mb-2">Profit Percentage (%)</label>
                    <input type="number" name="profit_percentage" id="profit_percentage" value="{{ old('profit_percentage', 50) }}" step="0.01" min="0" max="100" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="grace_period_start" class="block text-sm font-medium text-gray-700 mb-2">Grace Period Start Date</label>
                    <input type="date" name="grace_period_start" id="grace_period_start" value="{{ old('grace_period_start', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition duration-200 shadow-lg">
                    Add Investor
                </button>
            </div>
        </form>
    </div>
</div>

@endsection