<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Notifications
            </h2>
            <a href="{{ route('notifications.preferences') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow text-sm">
                Preferences
            </a>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Your Notifications</h3>
                <form method="POST" action="{{ route('notifications.markAllRead') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                        Mark All as Read
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                @forelse($notifications as $notification)
                    <div class="p-4 border-b hover:bg-gray-50 transition {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-medium text-gray-800">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </div>
                                <div class="text-sm text-gray-600 mt-1">
                                    {{ $notification->data['message'] ?? '' }}
                                </div>
                                <div class="text-xs text-gray-400 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @if(!$notification->read_at)
                                <form method="POST" action="{{ route('notifications.markAsRead', $notification) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                        Mark Read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No notifications found.
                    </div>
                @endforelse
            </div>

            <div class="p-4 border-t">
                {{ $notifications->links() }}
            </div>

            <div class="mt-4">
                <form method="POST" action="{{ route('notifications.test') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                        Send Test Notification
                    </button>
                </form>
            </div>

        </div>

    </div>

</x-app-layout>
