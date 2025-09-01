@extends('layouts.owner')
@section('title', 'All Transactions')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Transactions</h1>
        <div class="flex space-x-4">
            <input type="text" placeholder="Search transactions..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <a href="{{ route('owner.transactions.export') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Export
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Debit Account</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Credit Account</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $transaction->reference }}</td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $transaction->description }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $transaction->debitAccount->account_name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $transaction->creditAccount->account_name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-semibold text-gray-900">PKR{{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $transactions->links() }}
    </div>
</div>

@endsection