@extends('layouts.director')
@section('title', 'Record Payment for Invoice #' . $salesInvoice->invoice_number)
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Record Payment for Invoice #{{ $salesInvoice->invoice_number }}</h1>
        <a href="{{ route('director.sales-payments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
            Back to Invoices
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
        <div class="mb-6 border-b border-gray-200 pb-6">
            <h3 class="text-2xl font-bold text-gray-800">Invoice Details</h3>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                <div><strong>Customer:</strong> {{ $salesInvoice->customer->name ?? 'N/A' }}</div>
                <div><strong>Invoice Date:</strong> {{ optional($salesInvoice->invoice_date)->format('d M, Y') }}</div>
                <div><strong>Invoice Amount:</strong> PKR {{ number_format($salesInvoice->total_amount, 2) }}</div>
                <div><strong>Remaining Balance:</strong> <span class="font-bold text-red-600">PKR {{ number_format($salesInvoice->remaining_amount, 2) }}</span></div>
            </div>
        </div>

        <h3 class="text-2xl font-bold text-gray-800 mb-4">Payment Information</h3>
        <form action="{{ route('director.sales-payments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="sales_invoice_id" value="{{ $salesInvoice->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-2">Payment Amount</label>
                    <input type="number" name="payment_amount" id="payment_amount" value="{{ old('payment_amount', $salesInvoice->remaining_amount) }}" step="0.01" min="0.01" max="{{ $salesInvoice->remaining_amount }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="">Select a Method</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition duration-200 shadow-lg">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

@endsection