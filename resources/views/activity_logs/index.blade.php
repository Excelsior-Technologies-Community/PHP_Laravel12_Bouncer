<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Activity Logs
            </h2>
            <form method="POST" action="{{ route('activity.logs.clear') }}" class="inline">
                @csrf
                <input type="hidden" name="days" value="30">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow text-sm"
                        onclick="return confirm('Clear logs older than 30 days?')">
                    Clear Old Logs
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" action="{{ route('activity.logs.index') }}" class="mb-4 bg-white shadow-md rounded-xl p-4">
                <div class="flex flex-wrap gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search logs..."
                           class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-auto">

                    <select name="action" class="border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="user" value="{{ request('user') }}"
                           placeholder="Filter by user..."
                           class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-auto">

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Filter
                    </button>
                    <a href="{{ route('activity.logs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Reset
                    </a>
                </div>
            </form>

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="p-4 text-left">User</th>
                                <th class="p-4 text-left">Action</th>
                                <th class="p-4 text-left">Subject</th>
                                <th class="p-4 text-left">IP Address</th>
                                <th class="p-4 text-left">Created At</th>
                                <th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 font-medium text-gray-800">
                                        {{ $log->user?->name ?? 'System' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-gray-600">
                                        {{ $log->subject_type ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 text-gray-600">{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td class="p-4 text-gray-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('activity.logs.show', $log) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-500">
                                        No activity logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
