@extends('layouts.director')
@section('title', 'Create Purchase Invoice')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Create New Purchase Invoice</h1>
        <a href="{{ route('director.purchases.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
            Back to Purchases
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
        <form action="{{ route('director.purchases.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <select name="supplier_id" id="supplier_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200">
                        <option value="">Select a Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Invoice Date</label>
                    <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="credit_days" class="block text-sm font-medium text-gray-700 mb-2">Credit Period (days)</label>
                    <input type="number" name="credit_days" id="credit_days" value="{{ old('credit_days', 30) }}" min="0" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200">
                </div>
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mb-4">Purchase Items</h3>
            <div id="items-container" class="space-y-4">
                @if(old('items'))
                    @foreach(old('items') as $key => $item)
                        <div class="item-row grid grid-cols-1 md:grid-cols-6 gap-4 items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Product Name</label>
                                <input type="text" name="items[{{ $key }}][product_name]" value="{{ $item['product_name'] }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg item-product">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Lot Number</label>
                                <input type="text" name="items[{{ $key }}][lot_number]" value="{{ $item['lot_number'] }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg item-lot-number">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Quantity</label>
                                <input type="number" name="items[{{ $key }}][quantity]" value="{{ $item['quantity'] }}" step="0.001" min="0.001" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg item-quantity">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Unit Price</label>
                                <input type="number" name="items[{{ $key }}][unit_price]" value="{{ $item['unit_price'] }}" step="0.01" min="0.01" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg item-price">
                            </div>
                            <div class="text-right">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Line Total</label>
                                <span class="text-lg font-bold text-gray-800 line-total">
                                    PKR {{ number_format($item['quantity'] * $item['unit_price'], 2) }}
                                </span>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12H6L5 7m14 0H5m14 0V5a2 2 0 00-2-2H7a2 2 0 00-2 2v2m7 4v6m-4-6v6m8-6v6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="item-row grid grid-cols-1 md:grid-cols-6 gap-4 items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Product Name</label>
                            <input type="text" name="items[0][product_name]" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg item-product">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Lot Number</label>
                            <input type="text" name="items[0][lot_number]" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg item-lot-number">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Quantity</label>
                            <input type="number" name="items[0][quantity]" step="0.001" min="0.001" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg item-quantity">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Unit Price</label>
                            <input type="number" name="items[0][unit_price]" step="0.01" min="0.01" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg item-price">
                        </div>
                        <div class="text-right">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Line Total</label>
                            <span class="text-lg font-bold text-gray-800 line-total">PKR 0.00</span>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12H6L5 7m14 0H5m14 0V5a2 2 0 00-2-2H7a2 2 0 00-2 2v2m7 4v6m-4-6v6m8-6v6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="button" id="add-item-btn" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                    Add Another Item
                </button>
                <div class="text-right text-2xl font-black text-gray-800">
                    Total Amount: <span id="total-amount">PKR 0.00</span>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition duration-200 shadow-lg">
                    Create Purchase
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let itemIndex = {{ count(old('items', [])) }};

        function calculateTotals() {
            let totalAmount = 0;
            $('.item-row').each(function() {
                const quantity = parseFloat($(this).find('.item-quantity').val()) || 0;
                const price = parseFloat($(this).find('.item-price').val()) || 0;
                const lineTotal = quantity * price;
                $(this).find('.line-total').text('PKR ' + lineTotal.toFixed(2));
                totalAmount += lineTotal;
            });
            $('#total-amount').text('PKR ' + totalAmount.toFixed(2));
        }

        function addItemRow() {
            const newItemRow = `
                <div class="item-row grid grid-cols-1 md:grid-cols-6 gap-4 items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Product Name</label>
                        <input type="text" name="items[${itemIndex}][product_name]" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg item-product">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Lot Number</label>
                        <input type="text" name="items[${itemIndex}][lot_number]" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg item-lot-number">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Quantity</label>
                        <input type="number" name="items[${itemIndex}][quantity]" step="0.001" min="0.001" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg item-quantity">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Unit Price</label>
                        <input type="number" name="items[${itemIndex}][unit_price]" step="0.01" min="0.01" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg item-price">
                    </div>
                    <div class="text-right">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Line Total</label>
                        <span class="text-lg font-bold text-gray-800 line-total">PKR 0.00</span>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12H6L5 7m14 0H5m14 0V5a2 2 0 00-2-2H7a2 2 0 00-2 2v2m7 4v6m-4-6v6m8-6v6" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            $('#items-container').append(newItemRow);
            itemIndex++;
            calculateTotals();
        }

        if (itemIndex === 0) {
            addItemRow();
        }

        $('#add-item-btn').on('click', addItemRow);

        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('.item-row').remove();
            calculateTotals();
        });

        $(document).on('input', '.item-quantity, .item-price', calculateTotals);

        calculateTotals();
    });
</script>

@endsection
