@extends('layouts.director')
@section('title', 'Sales Invoice: ' . $sale->invoice_number)
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Sales Invoice: {{ $sale->invoice_number }}</h1>
        <div class="flex space-x-4">
            <a href="{{ route('director.sales.edit', $sale->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Edit Invoice
            </a>
            <a href="{{ route('director.sales.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Invoice Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Invoice Number</p>
                <p class="text-lg font-bold text-gray-900">{{ $sale->invoice_number }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Customer</p>
                <p class="text-lg text-gray-900">{{ $sale->customer->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Invoice Date</p>
                <p class="text-lg text-gray-900">{{ $sale->invoice_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Due Date</p>
                <p class="text-lg text-gray-900">{{ $sale->due_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Total Amount</p>
                <p class="text-lg font-bold text-green-600">${{ number_format($sale->total_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Status</p>
                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                    @if($sale->status === 'paid') bg-green-100 text-green-800
                    @elseif($sale->status === 'partially_paid') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $sale->status)) }}
                </span>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">Invoice Items</h2>
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Quantity Sold</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Line Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($sale->items as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 text-gray-900">{{ $item->product_name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->quantity_sold }}</td>
                            <td class="px-6 py-4 text-gray-600">${{ number_format($item->unit_selling_price, 2) }}</td>
                            <td class="px-6 py-4 text-gray-900 font-bold">${{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400 italic">No items found for this invoice.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection