@extends('layouts.director')
@section('title', 'View Supplier')
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Supplier Details</h1>
        <div class="flex space-x-4">
            <a href="{{ route('director.suppliers.edit', $supplier->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Edit Supplier
            </a>
            <a href="{{ route('director.suppliers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Name</p>
                <p class="text-lg font-bold text-gray-900">{{ $supplier->name }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Contact Person</p>
                <p class="text-lg text-gray-900">{{ $supplier->contact_person ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Email</p>
                <p class="text-lg text-gray-900">{{ $supplier->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Phone</p>
                <p class="text-lg text-gray-900">{{ $supplier->phone ?? 'N/A' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-semibold uppercase text-gray-500">Address</p>
                <p class="text-lg text-gray-900">{{ $supplier->address ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Credit Limit</p>
                <p class="text-lg font-bold text-gray-900">${{ number_format($supplier->credit_limit, 2) }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Credit Days</p>
                <p class="text-lg text-gray-900">{{ $supplier->credit_days }} days</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Status</p>
                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                    @if($supplier->is_active) bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>
</div>

@endsection