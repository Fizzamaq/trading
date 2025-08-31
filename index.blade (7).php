@extends('layouts.director')
@section('title', 'Suppliers')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Supplier Management</h1>
        <div class="flex space-x-4">
            <input type="text" placeholder="Search suppliers..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            <button class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Add Supplier
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-orange-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-orange-200">Active Suppliers</h3>
            <p class="text-3xl font-bold mt-2">{{ $suppliers->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-blue-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-blue-200">Total Suppliers</h3>
            <p class="text-3xl font-bold mt-2">{{ $suppliers->count() }}</p>
        </div>
        <div class="bg-purple-600 rounded-xl p-6 text-white">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-purple-200">Verified Suppliers</h3>
            <p class="text-3xl font-bold mt-2">{{ $suppliers->where('is_verified', true)->count() }}</p>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Contact Person</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($suppliers as $supplier)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $supplier->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $supplier->contact_person ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $supplier->email }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $supplier->phone ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                                @if($supplier->is_active) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium space-x-2">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No suppliers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $suppliers->links() }}
    </div>
</div>

@endsection
