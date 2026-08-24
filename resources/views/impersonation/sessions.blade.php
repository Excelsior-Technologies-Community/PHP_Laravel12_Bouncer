<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Impersonation Sessions
            </h2>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="p-4 text-left">Admin</th>
                                <th class="p-4 text-left">Impersonated User</th>
                                <th class="p-4 text-left">IP Address</th>
                                <th class="p-4 text-left">Started At</th>
                                <th class="p-4 text-left">Ended At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($sessions as $session)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 font-medium text-gray-800">{{ $session->admin?->name ?? 'N/A' }}</td>
                                    <td class="p-4 text-gray-600">{{ $session->impersonatedUser?->name ?? 'N/A' }}</td>
                                    <td class="p-4 text-gray-600">{{ $session->ip_address }}</td>
                                    <td class="p-4 text-gray-500">{{ $session->started_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="p-4 text-gray-500">
                                        {{ $session->ended_at?->format('Y-m-d H:i:s') ?? 'Active' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-gray-500">
                                        No impersonation sessions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t">
                    {{ $sessions->links() }}
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
