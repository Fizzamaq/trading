@extends('layouts.owner')
@section('title', 'User Profile')
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">User Profile</h1>
        <div class="flex space-x-4">
            <a href="{{ route('owner.users.edit', $user->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Edit User
            </a>
            <a href="{{ route('owner.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                Back to User List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Full Name</p>
                <p class="text-lg font-bold text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Email Address</p>
                <p class="text-lg text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">User Role</p>
                <p class="text-lg text-gray-900">{{ ucfirst($user->role) }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Phone Number</p>
                <p class="text-lg text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Account Status</p>
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
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500">Member Since</p>
                <p class="text-lg text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

@endsection