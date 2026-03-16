<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Assignment History</h3>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Date & Time</th>
                            <th class="px-4 py-3">Performed By</th>
                            <th class="px-4 py-3">Action</th>
                            <th class="px-4 py-3">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-3">{{ $log->user->name }}</td>
                            <td class="px-4 py-3">
                                @if($log->action === 'generated')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-xs font-medium">
                                        Generated Schedule
                                    </span>
                                @elseif($log->action === 'overridden')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded text-xs font-medium">
                                        Manual Override
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                @if($log->action === 'generated')
                                    Generated {{ $log->details['total_assignments'] ?? 0 }} assignments
                                @elseif($log->action === 'overridden')
                                    Subject: <strong>{{ $log->details['subject'] ?? 'N/A' }}</strong>
                                    — From: {{ $log->details['from_teacher'] ?? 'N/A' }}
                                    → To: {{ $log->details['to_teacher'] ?? 'N/A' }}
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                                No activity yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>