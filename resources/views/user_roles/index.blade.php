<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Role Assignment
            </h2>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-xl overflow-hidden">

                <!-- Header strip -->
                <div class="p-6 border-b bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Assign Roles to Users
                    </h3>
                    <p class="text-sm text-gray-500">
                        Manage user access and permissions efficiently.
                    </p>
                </div>

                <form method="GET" action="{{ route('user.roles') }}" class="mb-4">
                    <div class="flex gap-2">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search user by name or email..."
                            class="border border-gray-300 rounded-lg px-4 py-2 w-full">

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Search
                        </button>

                        <a href="{{ route('user.roles') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            Reset
                        </a>

                    </div>
                </form>

                <!-- TABLE -->
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="p-4 text-left">User</th>
                                <th class="p-4 text-left">Email</th>
                                <th class="p-4 text-left">Current Role</th>
                                <th class="p-4 text-left">Assign Role</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">

                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition">

                                    <!-- Name -->
                                    <td class="p-4 font-medium text-gray-800">
                                        {{ $user->name }}
                                    </td>

                                    <!-- Email -->
                                    <td class="p-4 text-gray-600">
                                        {{ $user->email }}
                                    </td>

                                    <!-- Current Role -->
                                    <td class="p-4">
                                        @php
                                            $role = optional($user->roles->first())->name;
                                        @endphp

                                        @if($role)
                                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                                {{ $role }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-600">
                                                No Role
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Assign Role -->
                                    <td class="p-4">

                                        <form method="POST" action="{{ route('user.roles.assign') }}"
                                            class="flex items-center gap-2">

                                            @csrf

                                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                                            <select name="role"
                                                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}">
                                                        {{ $role->title }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button type="submit"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                                                Save
                                            </button>

                                        </form>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                    <div class="p-4 border-t">
                        {{ $users->links() }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>