@extends('layouts.director')
@section('title', 'Inventory')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Inventory Management</h1>
        <div class="flex space-x-4">
            <input type="text" placeholder="Search inventory..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Add Item
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-purple-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-purple-200">Total Items</h3>
            <p class="text-3xl font-bold mt-2">{{ $inventoryItems->count() }}</p>
        </div>
        <div class="bg-green-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-green-200">In Stock</h3>
            <p class="text-3xl font-bold mt-2">{{ $inventoryItems->where('remaining_quantity', '>', 0)->count() }}</p>
        </div>
        <div class="bg-yellow-500 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-yellow-200">Low Stock</h3>
            <p class="text-3xl font-bold mt-2">{{ $inventoryItems->where('remaining_quantity', '<=', 10)->count() }}</p>
        </div>
        <div class="bg-red-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-red-200">Out of Stock</h3>
            <p class="text-3xl font-bold mt-2">{{ $inventoryItems->where('remaining_quantity', 0)->count() }}</p>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Product Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Original Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Remaining Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit Price</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($inventoryItems as $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $item->product_name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $item->sku ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $item->original_quantity }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-semibold 
                            @if($item->remaining_quantity == 0) text-red-600
                            @elseif($item->remaining_quantity <= 10) text-yellow-600
                            @else text-green-600 @endif">
                            {{ $item->remaining_quantity }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($item->remaining_quantity == 0)
                                <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">Out of Stock</span>
                            @elseif($item->remaining_quantity <= 10)
                                <span class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">Low Stock</span>
                            @else
                                <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">In Stock</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium space-x-2">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">No inventory items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $inventoryItems->links() }}
    </div>
</div>

@endsection
