<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tenants Management
            </h2>
            <a href="{{ route('tenants.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                + Add Tenant
            </a>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('tenants.index') }}" class="mb-4">
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search tenants..."
                           class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Search
                    </button>
                    <a href="{{ route('tenants.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Reset
                    </a>
                </div>
            </form>

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="p-4 text-left">Name</th>
                                <th class="p-4 text-left">Slug</th>
                                <th class="p-4 text-left">Email</th>
                                <th class="p-4 text-left">Users</th>
                                <th class="p-4 text-left">Status</th>
                                <th class="p-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($tenants as $tenant)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 font-medium text-gray-800">{{ $tenant->name }}</td>
                                    <td class="p-4 text-gray-600">{{ $tenant->slug }}</td>
                                    <td class="p-4 text-gray-600">{{ $tenant->email ?? 'N/A' }}</td>
                                    <td class="p-4 text-gray-600">{{ $tenant->users_count }}</td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            {{ $tenant->status == 'active' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $tenant->status == 'inactive' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $tenant->status == 'suspended' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ ucfirst($tenant->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('tenants.edit', $tenant) }}"
                                               class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md text-xs">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" class="inline" onsubmit="return confirm('Delete this tenant?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-500">
                                        No tenants found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t">
                    {{ $tenants->links() }}
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
