@extends('layouts.owner')
@section('title', 'Investors')
@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-semibold mb-8 text-gray-900">Investors</h1>

    <!-- Success and error notifications -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-5 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Investors Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200 text-gray-700">
            <thead class="bg-gray-50">
                <tr>
                    @foreach (['ID', 'Name', 'Email', 'Phone', 'Investment Amount', 'Status', 'Actions'] as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($investors as $investor)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $investor->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $investor->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $investor->user->email ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $investor->user->phone ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">${{ number_format($investor->investment_amount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">
                        @php
                            $statusColors = [
                                'pending_approval' => 'text-yellow-600 font-semibold',
                                'active' => 'text-green-600 font-semibold',
                                'inactive' => 'text-gray-600 font-semibold',
                            ];
                            $userStatus = $investor->user->status ?? '';
                        @endphp
                        <span class="{{ $statusColors[$userStatus] ?? 'text-gray-600' }}">
                            {{ str_replace('_', ' ', ucfirst($userStatus)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('owner.investors.show', $investor->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            View
                        </a>
                        @if($userStatus === 'pending_approval')
                       <form action="{{ route('owner.investors.approve', $investor->user->id) }}" method="POST" class="inline">
                           @csrf
                           @method('PATCH')
                           <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                               Approve
                           </button>
                       </form>

                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400 text-sm">
                        No investors found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $investors->links() }}
    </div>
</div>
@endsection
