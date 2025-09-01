@extends('layouts.owner')
@section('title', 'Accounts Receivable Aging')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900">Accounts Receivable Aging</h1>
            <p class="text-gray-600 mt-2">Outstanding sales invoices categorized by their age</p>
        </div>
        <a href="{{ route('owner.reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
            Back to Reports
        </a>
    </div>

    @forelse ($agingReport as $customerName => $agingBuckets)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-800">{{ $customerName }}</h3>
            </div>
            
            @foreach($agingBuckets as $bucket => $invoices)
                <div class="p-8 border-b last:border-b-0">
                    <h4 class="text-xl font-bold mb-4
                        @if($bucket === 'Current') text-green-600
                        @elseif($bucket === '1-30 Days') text-yellow-600
                        @elseif($bucket === '31-60 Days') text-orange-600
                        @else text-red-600 @endif">
                        {{ $bucket }} (Total: ${{ number_format(collect($invoices)->sum('remaining_amount'), 2) }})
                    </h4>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Invoice #</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Remaining</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Days Overdue</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->invoice_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->due_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-red-600">${{ number_format($invoice->remaining_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->days_overdue > 0 ? $invoice->days_overdue : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center text-gray-400 italic">
            No outstanding sales invoices found.
        </div>
    @endforelse
</div>

@endsection