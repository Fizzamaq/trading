@extends('layouts.director')
@section('title', 'Payment Details: ' . $payment->payment_reference)
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Payment Details</h1>
        <div class="flex space-x-4">
            <a href="{{ route('director.sales-payments.edit', $payment->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Edit Payment
            </a>
            <a href="{{ route('director.payments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                Back to Payments
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Payment Reference: {{ $payment->payment_reference }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Customer</p>
                <p class="text-lg font-bold text-gray-900">{{ $payment->customer->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Amount</p>
                <p class="text-lg font-bold text-green-600">${{ number_format($payment->payment_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Payment Date</p>
                <p class="text-lg text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Payment Method</p>
                <p class="text-lg text-gray-900">{{ $payment->payment_method }}</p>
            </div>
        </div>
    </div>
</div>

@endsection