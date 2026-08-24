<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Profile
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user) }}"
                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg shadow text-sm">
                    Edit
                </a>
                <a href="{{ route('users.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow text-sm">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="p-6 border-b bg-gray-50 flex items-center gap-6">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-full object-cover bg-gray-200">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="flex gap-2 mt-2">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $user->status == 'active' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $user->status == 'inactive' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $user->status == 'suspended' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                            @foreach($user->roles as $role)
                                <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-medium">
                                    {{ $role->title ?? $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">User ID</label>
                            <p class="text-gray-800">{{ $user->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-800">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tenant</label>
                            <p class="text-gray-800">{{ $user->tenant?->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Login</label>
                            <p class="text-gray-800">{{ $user->last_login_at?->format('Y-m-d H:i:s') ?? 'Never' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                            <p class="text-gray-800">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Updated At</label>
                            <p class="text-gray-800">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>

                    @if($user->activityLogs->count() > 0)
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Recent Activity</h4>
                            <div class="space-y-2">
                                @foreach($user->activityLogs->take(10) as $log)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="font-medium text-gray-800">{{ $log->action }}</span>
                                            <span class="text-gray-500 text-sm ml-2">{{ $log->subject_type }}</span>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($user->auditLogs->count() > 0)
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Recent Audit Logs</h4>
                            <div class="space-y-2">
                                @foreach($user->auditLogs->take(10) as $log)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="font-medium text-gray-800">{{ $log->event }}</span>
                                            <span class="text-gray-500 text-sm ml-2">{{ $log->auditable_type }}</span>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
