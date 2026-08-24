<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Audit Log Details
            </h2>
            <a href="{{ route('audit.logs.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow text-sm">
                Back to Logs
            </a>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="p-6 border-b bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700">Log #{{ $log->id }}</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Event</label>
                            <p class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $log->event)) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">User</label>
                            <p class="text-gray-800">{{ $log->user?->name ?? 'System' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Auditable Type</label>
                            <p class="text-gray-800">{{ $log->auditable_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Auditable ID</label>
                            <p class="text-gray-800">{{ $log->auditable_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">URL</label>
                            <p class="text-gray-800">{{ $log->url ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                            <p class="text-gray-800">{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>

                    @if($log->old_values)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Old Values</label>
                            <pre class="bg-red-50 p-4 rounded-lg text-sm overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif

                    @if($log->new_values)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">New Values</label>
                            <pre class="bg-green-50 p-4 rounded-lg text-sm overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
