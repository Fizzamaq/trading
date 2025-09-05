@extends('layouts.director')
@section('title', 'Inventory Item: ' . $inventory->lot_number)
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Inventory Item Details</h1>
        <div class="flex space-x-4">
            <a href="{{ route('director.inventory.edit', $inventory->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Edit Item
            </a>
            <a href="{{ route('director.inventory.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                Back to Inventory
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Lot: {{ $inventory->lot_number }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Product Name</p>
                <p class="text-lg font-bold text-gray-900">{{ $inventory->product_name }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Lot Number</p>
                <p class="text-lg text-gray-900">{{ $inventory->lot_number }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Original Quantity</p>
                <p class="text-lg text-gray-900">{{ $inventory->original_quantity }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Remaining Quantity</p>
                <p class="text-lg text-gray-900">{{ $inventory->remaining_quantity }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Unit Cost</p>
                <p class="text-lg text-gray-900">${{ number_format($inventory->unit_cost, 2) }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Total Cost</p>
                <p class="text-lg text-gray-900">${{ number_format($inventory->total_cost, 2) }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Purchase Date</p>
                <p class="text-lg text-gray-900">{{ $inventory->purchase_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Supplier</p>
                <p class="text-lg text-gray-900">{{ $inventory->supplier->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>

@endsection