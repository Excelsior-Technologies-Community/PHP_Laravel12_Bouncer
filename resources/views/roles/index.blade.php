<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Roles Management
            </h2>

            <a href="{{ route('roles.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                + Create Role
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

            <!-- Search Form -->
            <form method="GET" action="{{ route('roles.index') }}" class="mb-4">
                <div class="flex gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search role by name or title..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200 focus:border-indigo-500">

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                        Search
                    </button>

                    <a href="{{ route('roles.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                        Reset
                    </a>

                </div>
            </form>

            <div class="bg-white shadow-md rounded-xl overflow-hidden">

                <!-- Header -->
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700">
                        All Roles
                    </h3>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm text-left">

                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Role Name</th>
                                <th class="px-6 py-3">Title</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @forelse($roles as $role)

                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        #{{ $role->id }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">
                                            {{ $role->name }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $role->title }}
                                    </td>

                                    <td class="px-6 py-4">

                                        <div class="flex justify-center gap-2">

                                            <a href="{{ route('roles.edit', $role->id) }}"
                                               class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md text-xs">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('roles.destroy', $role->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    onclick="return confirm('Are you sure you want to delete this role?')"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center py-10 text-gray-500">
                                        No roles found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <!-- Pagination -->
                <div class="p-4 border-t">
                    {{ $roles->links() }}
                </div>

            </div>

        </div>

    </div>

</x-app-layout>