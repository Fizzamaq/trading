@extends('layouts.owner')
@section('title', 'Investor P&L: ' . $investor->user->name)
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900">Investor P&L Statement</h1>
            <p class="text-gray-600 mt-2">Financial performance for {{ $investor->user->name }}</p>
            <div class="text-sm text-gray-500 mt-1">
                Share Percentage: <span class="font-bold text-purple-600">{{ number_format($report['share_percentage'], 2) }}%</span>
                <span class="ml-4">Profit Split: {{ number_format($investor->profit_percentage, 2) }}% (Investor) / {{ number_format(100 - $investor->profit_percentage, 2) }}% (Owner)</span>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('owner.reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                Back to Reports
            </a>
            <form method="GET" action="{{ route('owner.reports.investor-pl', ['investor' => $investor->id]) }}" class="flex space-x-2">
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-4 py-2 border border-gray-300 rounded-lg">
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-4 py-2 border border-gray-300 rounded-lg">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <table class="w-full divide-y divide-gray-200">
            <tbody class="bg-white divide-y divide-gray-100 text-lg">
                <tr class="bg-blue-50 font-bold">
                    <td class="px-6 py-4">Proportional Revenue</td>
                    <td class="px-6 py-4 text-right">${{ number_format($report['revenue']['sales_revenue'], 2) }}</td>
                </tr>
                <tr class="bg-red-50">
                    <td class="px-6 py-4 pl-12">Proportional Cost of Goods Sold</td>
                    <td class="px-6 py-4 text-right">(${!! number_format($report['cost_of_sales']['cost_of_goods_sold'], 2) !!})</td>
                </tr>
                <tr class="bg-gray-100 font-extrabold text-xl">
                    <td class="px-6 py-4">Proportional Gross Profit</td>
                    <td class="px-6 py-4 text-right">${{ number_format($report['gross_profit'], 2) }}</td>
                </tr>
                <tr class="bg-red-50">
                    <td class="px-6 py-4 pl-12">Proportional Operating Expenses</td>
                    <td class="px-6 py-4 text-right">(${!! number_format($report['expenses']['operating_expenses'], 2) !!})</td>
                </tr>
                <tr class="bg-green-100 font-black text-2xl">
                    <td class="px-6 py-4">Proportional Net Profit</td>
                    <td class="px-6 py-4 text-right">${{ number_format($report['net_profit'], 2) }}</td>
                </tr>
                <tr class="bg-purple-100 font-bold">
                    <td class="px-6 py-4">Your Profit Portion ({{ number_format($investor->profit_percentage, 2) }}%)</td>
                    <td class="px-6 py-4 text-right text-purple-700">${{ number_format($report['investor_portion'], 2) }}</td>
                </tr>
                <tr class="bg-purple-50 font-semibold">
                    <td class="px-6 py-4">Owner's Profit Portion ({{ number_format(100 - $investor->profit_percentage, 2) }}%)</td>
                    <td class="px-6 py-4 text-right text-purple-700">${{ number_format($report['owner_portion'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection