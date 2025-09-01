@extends('layouts.owner')
@section('title', 'Edit User')
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Edit User: {{ $user->name }}</h1>
        <a href="{{ route('owner.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
            Back to User List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <form action="{{ route('owner.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password (optional)</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">User Role</label>
                    <select name="role" id="role" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="investor" {{ (old('role') == 'investor' || $user->role == 'investor') ? 'selected' : '' }}>Investor</option>
                        <option value="director" {{ (old('role') == 'director' || $user->role == 'director') ? 'selected' : '' }}>Director</option>
                        <option value="owner" {{ (old('role') == 'owner' || $user->role == 'owner') ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                    <select name="status" id="status" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="pending_approval" {{ (old('status') == 'pending_approval' || $user->status == 'pending_approval') ? 'selected' : '' }}>Pending Approval</option>
                        <option value="active" {{ (old('status') == 'active' || $user->status == 'active') ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ (old('status') == 'inactive' || $user->status == 'inactive') ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                </div>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition duration-200 shadow-lg">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

@endsection