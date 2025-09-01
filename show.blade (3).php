@extends('layouts.owner')
@section('title', 'Investor Profile: ' . $investor->user->name)
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900">Investor Profile</h1>
            <p class="text-gray-600 mt-2">Detailed overview for {{ $investor->user->name }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('owner.investors.edit', $investor->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Edit Investor
            </a>
            <a href="{{ route('owner.investors.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Personal & Investment Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Name</p>
                <p class="text-lg font-bold text-gray-900">{{ $investor->user->name }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Email</p>
                <p class="text-lg text-gray-900">{{ $investor->user->email }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Phone</p>
                <p class="text-lg text-gray-900">{{ $investor->user->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Account Status</p>
                @php
                    $statusColors = [
                        'pending_approval' => 'bg-yellow-100 text-yellow-800',
                        'active' => 'bg-green-100 text-green-800',
                        'inactive' => 'bg-red-100 text-red-800',
                    ];
                    $userStatus = $investor->user->status ?? '';
                @endphp
                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $statusColors[$userStatus] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ str_replace('_', ' ', ucfirst($userStatus)) }}
                </span>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Investment Amount</p>
                <p class="text-lg font-bold text-green-600">PKR {{ number_format($investor->investment_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Total Shares</p>
                <p class="text-lg font-bold text-blue-600">{{ $investor->total_shares }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Profit Percentage</p>
                <p class="text-lg font-bold text-purple-600">{{ $investor->profit_percentage }}%</p>
            </div>
        </div>
    </div>
</div>

@endsection