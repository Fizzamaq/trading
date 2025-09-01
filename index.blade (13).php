@extends('layouts.owner')
@section('title', 'Settings')
@section('content')

<div class="container mx-auto max-w-4xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Application Settings</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <form action="{{ route('owner.settings.update') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                    <input type="text" name="app_name" id="app_name" value="{{ $settings['app_name'] ?? '' }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="session_lifetime" class="block text-sm font-medium text-gray-700 mb-2">Session Lifetime (minutes)</label>
                    <input type="number" name="session_lifetime" id="session_lifetime" value="{{ $settings['session_lifetime'] ?? '' }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-2">Mail From Address</label>
                    <input type="email" name="mail_from_address" id="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                </div>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition duration-200 shadow-lg">
                    Update Settings
                </button>
            </div>
        </form>
    </div>
</div>

@endsection