<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users Management
            </h2>
            <a href="{{ route('users.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                + Add User
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

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white shadow-md rounded-xl p-6 mb-6">
                <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by name or email..."
                           class="border border-gray-300 rounded-lg px-4 py-2">

                    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>

                    <select name="role" class="border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->title }}
                            </option>
                        @endforeach
                    </select>

                    <select name="tenant" class="border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">All Tenants</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}" {{ request('tenant') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Filter
                        </button>
                        <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <form method="POST" action="{{ route('users.bulk.assign.role') }}" id="bulkForm" class="mb-4">
                @csrf
                <div class="bg-white shadow-md rounded-xl p-4 mb-4">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-sm font-medium text-gray-700">Bulk Actions:</span>

                        <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->title }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm">
                            Assign Role
                        </button>

                        <button type="button" onclick="confirmBulkDelete()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm">
                            Delete Selected
                        </button>

                        <a href="{{ route('users.trashed') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm">
                            Trash ({{ \App\Models\User::onlyTrashed()->count() }})
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white shadow-md rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="p-4 text-center">
                                        <input type="checkbox" id="selectAll" onclick="toggleAll()">
                                    </th>
                                    <th class="p-4 text-left">User</th>
                                    <th class="p-4 text-left">Email</th>
                                    <th class="p-4 text-left">Status</th>
                                    <th class="p-4 text-left">Roles</th>
                                    <th class="p-4 text-left">Tenant</th>
                                    <th class="p-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-4 text-center">
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox">
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                                     class="w-10 h-10 rounded-full object-cover bg-gray-200">
                                                <div>
                                                    <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-gray-600">{{ $user->email }}</td>
                                        <td class="p-4">
                                            <form method="POST" action="{{ route('users.update.status', $user) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()"
                                                        class="text-xs border rounded-full px-2 py-1
                                                        {{ $user->status == 'active' ? 'bg-green-100 text-green-700 border-green-300' : '' }}
                                                        {{ $user->status == 'inactive' ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : '' }}
                                                        {{ $user->status == 'suspended' ? 'bg-red-100 text-red-700 border-red-300' : '' }}">
                                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="p-4">
                                            @foreach($user->roles as $role)
                                                <span class="inline-block px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700 mr-1 mb-1">
                                                    {{ $role->title ?? $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="p-4 text-gray-600">
                                            {{ $user->tenant?->name ?? 'N/A' }}
                                        </td>
                                        <td class="p-4">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('users.show', $user) }}"
                                                   class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-1 rounded-md text-xs">
                                                    View
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}"
                                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md text-xs">
                                                    Edit
                                                </a>
                                                @if(auth()->user()->id !== $user->id)
                                                    <form method="POST" action="{{ route('users.destroy') }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        <input type="hidden" name="user_ids[]" value="{{ $user->id }}">
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10 text-gray-500">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t">
                        {{ $users->links() }}
                    </div>
                </div>
            </form>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('export.users') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                    Export Users (CSV)
                </a>
                <a href="{{ route('export.activity') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    Export Activity Logs
                </a>
                <a href="{{ route('export.audit') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                    Export Audit Logs
                </a>
            </div>
        </div>

    </div>

    <script>
        function toggleAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        }

        function confirmBulkDelete() {
            const checked = document.querySelectorAll('.user-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one user');
                return;
            }
            if (confirm('Are you sure you want to delete ' + checked.length + ' users?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('users.destroy') }}';
                form.innerHTML = '@csrf';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'user_ids[]';
                    input.value = cb.value;
                    form.appendChild(input);
                });
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

</x-app-layout>
