@extends('layouts.owner')
@section('title', 'Audit Logs')
@section('content')

<div class="container mx-auto max-w-7xl p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900">Audit Logs</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800">System Activity Log</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">ID</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">User</th>
                        <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Action</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Details</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">IP Address</th>
                        <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($auditLogs as $log)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6">
                                <div class="text-sm font-semibold text-gray-900">{{ $log->id }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-gray-900">{{ $log->user->name ?? 'System' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 text-white
                                    @if(strpos($log->action, 'login') !== false) bg-green-500
                                    @elseif(strpos($log->action, 'logout') !== false) bg-yellow-500
                                    @elseif(strpos($log->action, 'created') !== false) bg-blue-500
                                    @elseif(strpos($log->action, 'updated') !== false) bg-purple-500
                                    @else bg-gray-500 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-600 max-w-xs truncate">
                                @if($log->model_type)
                                    Model: {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-600">{{ $log->ip_address }}</td>
                            <td class="px-8 py-6 text-sm text-gray-600">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-gray-400 italic">No audit logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
            {{ $auditLogs->links() }}
        </div>
    </div>
</div>

@endsection