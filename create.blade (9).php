@extends('layouts.director')
@section('title', 'Add New Inventory Item')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Add New Inventory Item</h1>
        <a href="{{ route('director.inventory.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
            Back to Inventory
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
        <form action="{{ route('director.inventory.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="lot_number" class="block text-sm font-medium text-gray-700 mb-2">Lot Number</label>
                    <input type="text" name="lot_number" id="lot_number" value="{{ old('lot_number') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="original_quantity" class="block text-sm font-medium text-gray-700 mb-2">Original Quantity</label>
                    <input type="number" name="original_quantity" id="original_quantity" value="{{ old('original_quantity') }}" step="0.001" min="0.001" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700 mb-2">Unit Cost</label>
                    <input type="number" name="unit_cost" id="unit_cost" value="{{ old('unit_cost') }}" step="0.01" min="0.01" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">Purchase Date</label>
                    <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <select name="supplier_id" id="supplier_id" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                        <option value="">Select a Supplier</option>
                        @foreach($purchaseInvoices as $invoice)
                            <option value="{{ $invoice->supplier_id }}" {{ old('supplier_id') == $invoice->supplier_id ? 'selected' : '' }}>
                                {{ $invoice->supplier->name ?? 'N/A' }} (Invoice #{{ $invoice->invoice_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="purchase_invoice_id" class="block text-sm font-medium text-gray-700 mb-2">Purchase Invoice</label>
                    <select name="purchase_invoice_id" id="purchase_invoice_id" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                        <option value="">Select an Invoice</option>
                        @foreach($purchaseInvoices as $invoice)
                            <option value="{{ $invoice->id }}" {{ old('purchase_invoice_id') == $invoice->id ? 'selected' : '' }}>
                                Invoice #{{ $invoice->invoice_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition duration-200 shadow-lg">
                    Add Item
                </button>
            </div>
        </form>
    </div>
</div>

@endsection