@extends('layouts.owner')
@section('title', 'User Management')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">User Management</h1>
        <a href="{{ route('owner.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
            Add New User
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-5 py-3 rounded relative mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ ucfirst($user->role) }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending_approval' => 'bg-yellow-100 text-yellow-800',
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-red-100 text-red-800',
                                ];
                                $statusClass = $statusColors[$user->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $user->status)) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium space-x-2">
                            <a href="{{ route('owner.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('owner.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $users->links() }}
    </div>
</div>

@endsection