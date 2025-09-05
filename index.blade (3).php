@extends('layouts.director')
@section('title', 'Payments')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Payment Management</h1>
        <div class="flex space-x-4">
            <input type="text" placeholder="Search payments..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            <a href="{{ route('director.sales-payments.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Add Payment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-teal-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-teal-200">Total Payments</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format(125000, 2) }}</p>
        </div>
        <div class="bg-green-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-green-200">Received</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format(85000, 2) }}</p>
        </div>
        <div class="bg-blue-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-blue-200">Pending</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format(25000, 2) }}</p>
        </div>
        <div class="bg-red-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-red-200">Failed</h3>
            <p class="text-3xl font-bold mt-2">${{ number_format(15000, 2) }}</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Payment ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $payment->payment_reference }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">N/A</td> <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $payment->customer->name ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-semibold text-gray-900">${{ number_format($payment->payment_amount, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $payment->payment_method }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $payment->payment_date->format('d M, Y') }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                             @php $status = 'completed' @endphp
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-green-100 text-green-800">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium space-x-2">
                            <a href="{{ route('director.sales-payments.show', $payment->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('director.sales-payments.edit', $payment->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400 italic">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $payments->links() }}
    </div>
</div>

@endsection
