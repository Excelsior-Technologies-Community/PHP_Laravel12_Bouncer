<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Notification Preferences
            </h2>
            <a href="{{ route('notifications.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow text-sm">
                Back to Notifications
            </a>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="p-6 border-b bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700">Notification Settings</h3>
                </div>

                <form method="POST" action="{{ route('notifications.updatePreferences') }}" class="p-6 space-y-6">
                    @csrf

                    <div>
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Email Notifications</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="email_notifications[]" value="user_created"
                                       {{ in_array('user_created', $preferences->email_notifications ?? []) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">When a new user is created</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="email_notifications[]" value="role_assigned"
                                       {{ in_array('role_assigned', $preferences->email_notifications ?? []) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">When a role is assigned</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="email_notifications[]" value="user_suspended"
                                       {{ in_array('user_suspended', $preferences->email_notifications ?? []) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">When a user is suspended</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Database Notifications</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="database_notifications[]" value="user_created"
                                       {{ in_array('user_created', $preferences->database_notifications ?? []) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">When a new user is created</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="database_notifications[]" value="role_assigned"
                                       {{ in_array('role_assigned', $preferences->database_notifications ?? []) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">When a role is assigned</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Real-time Notifications</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="realtime_notifications[]" value="user_created"
                                       {{ in_array('user_created', $preferences->realtime_notifications ?? []) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">When a new user is created</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="realtime_notifications[]" value="role_assigned"
                                       {{ in_array('role_assigned', $preferences->realtime_notifications ?? []) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">When a role is assigned</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</x-app-layout>
