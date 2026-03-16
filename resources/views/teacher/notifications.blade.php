<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Mark All Read --}}
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500">{{ $notifications->count() }} notification(s)</p>
                <form method="POST" action="{{ route('teacher.notifications.read.all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">
                        Mark All as Read
                    </button>
                </form>
            </div>

            {{-- Notifications List --}}
            @forelse($notifications as $notification)
            <div class="bg-white shadow-sm sm:rounded-lg p-5 border-l-4 {{ $notification->is_read ? 'border-gray-200' : 'border-blue-500' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">
                            {{ $notification->title }}
                            @if(!$notification->is_read)
                                <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-600 rounded text-xs">New</span>
                            @endif
                        </p>
                        <p class="text-gray-600 text-sm mt-1">{{ $notification->message }}</p>
                        <p class="text-gray-400 text-xs mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->is_read)
                    <form method="POST" action="{{ route('teacher.notifications.read', $notification->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-xs text-blue-600 hover:underline ml-4">
                            Mark as read
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center text-gray-400">
                No notifications yet.
            </div>
            @endforelse

        </div>
    </div>
</x-app-layout>