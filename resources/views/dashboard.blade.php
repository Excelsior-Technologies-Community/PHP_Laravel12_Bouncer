<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Card -->
            <div class="bg-white shadow rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-700">
                    Welcome back 👋
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Role-Based Access Control Dashboard (Laravel + Bouncer)
                </p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Total Users -->
                <div
                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex justify-between items-center">

                        <div>
                            <h3 class="text-sm font-medium">
                                Total Users
                            </h3>

                            <p class="text-3xl font-bold mt-2">
                                {{ $totalUsers }}
                            </p>
                        </div>

                        <div class="text-4xl opacity-80">
                            👤
                        </div>

                    </div>
                </div>

                <!-- Total Roles -->
                <div
                    class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex justify-between items-center">

                        <div>
                            <h3 class="text-sm font-medium">
                                Total Roles
                            </h3>

                            <p class="text-3xl font-bold mt-2">
                                {{ $totalRoles }}
                            </p>
                        </div>

                        <div class="text-4xl opacity-80">
                            🔐
                        </div>

                    </div>
                </div>

                <!-- Total Permissions -->
                <div
                    class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex justify-between items-center">

                        <div>
                            <h3 class="text-sm font-medium">
                                Total Permissions
                            </h3>

                            <p class="text-3xl font-bold mt-2">
                                {{ $totalPermissions }}
                            </p>
                        </div>

                        <div class="text-4xl opacity-80">
                            ⚡
                        </div>

                    </div>
                </div>

            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">

                <a href="{{ route('roles.index') }}"
                    class="bg-white p-5 rounded-xl shadow hover:shadow-md transition border">

                    <h4 class="font-semibold text-gray-700">
                        Manage Roles
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Create, edit and delete system roles
                    </p>

                </a>

                <a href="{{ route('user.roles') }}"
                    class="bg-white p-5 rounded-xl shadow hover:shadow-md transition border">

                    <h4 class="font-semibold text-gray-700">
                        Assign Roles
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Manage user access control
                    </p>

                </a>

                <a href="{{ url('/users') }}" class="bg-white p-5 rounded-xl shadow hover:shadow-md transition border">

                    <h4 class="font-semibold text-gray-700">
                        User Access
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Protected permission route
                    </p>

                </a>

            </div>

            <!-- Recent Users Section -->
            <div class="mt-8">

                <div class="bg-white shadow rounded-xl overflow-hidden">

                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-700">
                            Recent Users
                        </h3>
                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full text-sm">

                            <thead class="bg-gray-100">

                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        Name
                                    </th>

                                    <th class="px-6 py-3 text-left">
                                        Email
                                    </th>

                                    <th class="px-6 py-3 text-left">
                                        Registered
                                    </th>
                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @forelse($recentUsers as $user)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4">
                                            {{ $user->name }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $user->email }}
                                        </td>

                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $user->created_at->diffForHumans() }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                                            No users found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>
                        <div class="p-4 border-t">
                            {{ $recentUsers->links() }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>