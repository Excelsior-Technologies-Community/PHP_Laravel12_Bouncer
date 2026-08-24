<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('export.users') }}" class="text-sm bg-green-600 text-white px-3 py-1 rounded-lg">
                    Export Users
                </a>
                <a href="{{ route('export.activity') }}" class="text-sm bg-blue-600 text-white px-3 py-1 rounded-lg">
                    Export Activity
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Card -->
            <div class="bg-white shadow rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-700">
                    Welcome back {{ Auth::user()->name }} 👋
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Role-Based Access Control Dashboard (Laravel + Bouncer)
                </p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-6">

                <!-- Total Users -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium">Total Users</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalUsers }}</p>
                        </div>
                        <div class="text-4xl opacity-80">👤</div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium">Active Users</h3>
                            <p class="text-3xl font-bold mt-2">{{ $activeUsers }}</p>
                        </div>
                        <div class="text-4xl opacity-80">✅</div>
                    </div>
                </div>

                <!-- Inactive Users -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium">Inactive Users</h3>
                            <p class="text-3xl font-bold mt-2">{{ $inactiveUsers }}</p>
                        </div>
                        <div class="text-4xl opacity-80">⏸️</div>
                    </div>
                </div>

                <!-- Suspended Users -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium">Suspended</h3>
                            <p class="text-3xl font-bold mt-2">{{ $suspendedUsers }}</p>
                        </div>
                        <div class="text-4xl opacity-80">🚫</div>
                    </div>
                </div>

                <!-- Total Roles -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium">Total Roles</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalRoles }}</p>
                        </div>
                        <div class="text-4xl opacity-80">🔐</div>
                    </div>
                </div>

            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                <!-- User Growth Chart -->
                <div class="bg-white shadow rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">User Growth (Last 30 Days)</h3>
                    <div class="relative" style="height: 320px;">
                        <canvas id="userGrowthChart" style="height: 100% !important;"></canvas>
                    </div>
                </div>

                <!-- Role Distribution Chart -->
                <div class="bg-white shadow rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Role Distribution</h3>
                    <div class="relative" style="height: 320px;">
                        <canvas id="roleDistributionChart" style="height: 100% !important;"></canvas>
                    </div>
                </div>

            </div>

            <!-- Recent Activity & Audit Logs -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                <!-- Recent Activity -->
                <div class="bg-white shadow rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-700">Recent Activity</h3>
                    </div>
                    <div class="p-4 max-h-96 overflow-y-auto">
                        @forelse($recentActivity as $log)
                            <div class="flex justify-between items-center p-3 border-b last:border-b-0 hover:bg-gray-50">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $log->action }}</span>
                                    <span class="text-gray-500 text-sm ml-2">by {{ $log->user?->name ?? 'System' }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent activity</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Audit Logs -->
                <div class="bg-white shadow rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-700">Recent Audit Logs</h3>
                    </div>
                    <div class="p-4 max-h-96 overflow-y-auto">
                        @forelse($recentAudit as $log)
                            <div class="flex justify-between items-center p-3 border-b last:border-b-0 hover:bg-gray-50">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $log->event }}</span>
                                    <span class="text-gray-500 text-sm ml-2">by {{ $log->user?->name ?? 'System' }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent audit logs</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                <a href="{{ route('users.index') }}"
                   class="bg-white p-5 rounded-xl shadow hover:shadow-md transition border">
                    <h4 class="font-semibold text-gray-700">Manage Users</h4>
                    <p class="text-sm text-gray-500 mt-1">View, create, edit users</p>
                </a>

                <a href="{{ route('roles.index') }}"
                   class="bg-white p-5 rounded-xl shadow hover:shadow-md transition border">
                    <h4 class="font-semibold text-gray-700">Manage Roles</h4>
                    <p class="text-sm text-gray-500 mt-1">Create and assign roles</p>
                </a>

                <a href="{{ route('tenants.index') }}"
                   class="bg-white p-5 rounded-xl shadow hover:shadow-md transition border">
                    <h4 class="font-semibold text-gray-700">Manage Tenants</h4>
                    <p class="text-sm text-gray-500 mt-1">Multi-tenancy management</p>
                </a>

                <a href="{{ route('settings.index') }}"
                   class="bg-white p-5 rounded-xl shadow hover:shadow-md transition border">
                    <h4 class="font-semibold text-gray-700">Settings</h4>
                    <p class="text-sm text-gray-500 mt-1">App configuration</p>
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
                                        Status
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

                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                {{ $user->status == 'active' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ $user->status == 'inactive' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $user->status == 'suspended' ? 'bg-red-100 text-red-700' : '' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $user->created_at->diffForHumans() }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                                            No users found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($userGrowth->pluck('date')) !!},
                datasets: [{
                    label: 'New Users',
                    data: {!! json_encode($userGrowth->pluck('count')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        const roleDistributionCtx = document.getElementById('roleDistributionChart').getContext('2d');
        new Chart(roleDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($roleDistribution->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($roleDistribution->pluck('count')) !!},
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#ec4899'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

</x-app-layout>
