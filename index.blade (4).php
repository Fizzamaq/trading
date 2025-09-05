@extends('layouts.director')
@section('title', 'Purchases')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">
            Purchases
        </h1>
        <div class="flex space-x-4">
            <form action="{{ route('director.purchases.index') }}" method="GET" class="flex items-center space-x-4">
                <input type="text" name="search" placeholder="Search by Invoice ID or Supplier..." 
                       value="{{ $search ?? '' }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    Search
                </button>
            </form>
            <a href="{{ route('director.purchases.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Create Purchase
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-indigo-50">
                <tr>
                    @php
                        $sortableColumns = [
                            'invoice_number' => 'Invoice ID',
                            'supplier' => 'Supplier',
                            'invoice_date' => 'Date',
                            'total_amount' => 'Amount',
                        ];
                    @endphp
                    @foreach ($sortableColumns as $column => $label)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-indigo-700 cursor-pointer">
                            <a href="{{ route('director.purchases.index', array_merge(request()->except(['page']), ['sort_by' => $column, 'sort_direction' => ($sortBy === $column && $sortDirection === 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center">
                                {{ $label }}
                                @if ($sortBy === $column)
                                    @if ($sortDirection === 'asc')
                                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                    @endforeach
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">View</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($purchases as $purchase)
                    <tr class="hover:bg-indigo-50 transition duration-150">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-indigo-900">
                            {{ $purchase->invoice_number }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700">
                            {{ $purchase->supplier->name ?? 'N/A' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-indigo-700 font-semibold">
                            {{ optional($purchase->invoice_date)->format('d M, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 font-semibold text-indigo-900">
                            PKR {{ number_format($purchase->total_amount, 2) }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('director.purchases.show', $purchase->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                            No purchases found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $purchases->appends(['search' => $search, 'sort_by' => $sortBy, 'sort_direction' => $sortDirection])->links() }}
    </div>
</div>

@endsection
