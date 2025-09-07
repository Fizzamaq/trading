@extends('layouts.director')
@section('title', 'Sales Payments')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Sales Invoices for Payments</h1>
        <div class="flex space-x-4">
            <form action="{{ route('director.sales-payments.index') }}" method="GET" class="flex items-center space-x-4">
                <input type="text" name="search" placeholder="Search by Invoice # or Customer..." 
                       value="{{ $search ?? '' }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    Search
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-indigo-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-indigo-700">
                        Invoice #
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-indigo-700">
                        Customer
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-indigo-700">
                        Invoice Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-indigo-700">
                        Remaining Amount
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-indigo-700">
                        Status
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($invoices as $invoice)
                    <tr class="hover:bg-indigo-50 transition duration-150">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-indigo-900">
                            {{ $invoice->invoice_number }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700">
                            {{ $invoice->customer->name ?? 'N/A' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-indigo-700 font-semibold">
                            {{ optional($invoice->invoice_date)->format('d M, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 font-semibold text-indigo-900">
                            PKR {{ number_format($invoice->remaining_amount, 2) }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($invoice->status === 'unpaid') bg-red-100 text-red-800
                                @elseif($invoice->status === 'partially_paid') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            @if($invoice->status !== 'paid')
                                <a href="{{ route('director.sales-payments.record', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Record Payment
                                </a>
                            @else
                                <span class="text-gray-400">Paid</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                            No sales invoices found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $invoices->links() }}
    </div>
</div>

@endsection