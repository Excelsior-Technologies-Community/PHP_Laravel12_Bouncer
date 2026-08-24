<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings
            </h2>
            <button onclick="document.getElementById('createSettingForm').classList.toggle('hidden')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow text-sm">
                + Add Setting
            </button>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div id="createSettingForm" class="hidden mb-6 bg-white shadow-md rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Create New Setting</h3>
                <form method="POST" action="{{ route('settings.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                        <input type="text" name="group" value="{{ old('group') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        @error('group')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Key</label>
                        <input type="text" name="key" value="{{ old('key') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        @error('key')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                        <input type="text" name="value" value="{{ old('value') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        @error('value')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="string">String</option>
                            <option value="integer">Integer</option>
                            <option value="boolean">Boolean</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                            Create Setting
                        </button>
                    </div>
                </form>
            </div>

            @foreach($settings as $group => $groupSettings)
                <div class="bg-white shadow-md rounded-xl overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-700">{{ ucfirst($group) }} Settings</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="p-4 text-left">Key</th>
                                    <th class="p-4 text-left">Value</th>
                                    <th class="p-4 text-left">Type</th>
                                    <th class="p-4 text-left">Description</th>
                                    <th class="p-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($groupSettings as $setting)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-4 font-medium text-gray-800">{{ $setting->key }}</td>
                                        <td class="p-4 text-gray-600">{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}</td>
                                        <td class="p-4 text-gray-600">{{ $setting->type }}</td>
                                        <td class="p-4 text-gray-500">{{ $setting->description ?? 'N/A' }}</td>
                                        <td class="p-4 text-center">
                                            <form method="POST" action="{{ route('settings.update', $setting) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="value" value="{{ $setting->value }}">
                                                <input type="text" name="new_value" value="{{ $setting->value }}"
                                                       class="border border-gray-300 rounded px-2 py-1 text-xs w-32">
                                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs">
                                                    Update
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('settings.destroy', $setting) }}" class="inline" onsubmit="return confirm('Delete this setting?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs ml-2">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

        </div>

    </div>

</x-app-layout>
