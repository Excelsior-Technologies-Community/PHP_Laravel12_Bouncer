<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Tenant
            </h2>
            <a href="{{ route('tenants.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
                Back to Tenants
            </a>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="p-6 border-b bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700">Edit Tenant: {{ $tenant->name }}</h3>
                </div>

                <form method="POST" action="{{ route('tenants.update', $tenant) }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $tenant->name) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $tenant->slug) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                            <input type="text" name="domain" value="{{ old('domain', $tenant->domain) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            @error('domain')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $tenant->email) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <option value="active" {{ $tenant->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $tenant->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ $tenant->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                            @if($tenant->logo)
                                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}"
                                     class="w-16 h-16 rounded object-cover bg-gray-200 mb-2">
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            @error('logo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Settings (JSON)</label>
                            <textarea name="settings" rows="4"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2">{{ old('settings', $tenant->settings ? json_encode($tenant->settings) : '') }}</textarea>
                            @error('settings')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('tenants.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                            Update Tenant
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</x-app-layout>
