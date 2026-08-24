<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Menu Management
            </h2>
            <a href="{{ route('menus.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                + Add Menu
            </a>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

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
                                <th class="p-4 text-left">Name</th>
                                <th class="p-4 text-left">Slug</th>
                                <th class="p-4 text-left">Link</th>
                                <th class="p-4 text-left">Permission</th>
                                <th class="p-4 text-left">Order</th>
                                <th class="p-4 text-left">Parent</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($menus as $menu)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 font-medium text-gray-800">{{ $menu->name }}</td>
                                    <td class="p-4 text-gray-600">{{ $menu->slug }}</td>
                                    <td class="p-4 text-gray-600">{{ $menu->link ?? 'N/A' }}</td>
                                    <td class="p-4 text-gray-600">{{ $menu->permission ?? 'N/A' }}</td>
                                    <td class="p-4 text-gray-600">{{ $menu->order }}</td>
                                    <td class="p-4 text-gray-600">{{ $menu->parent?->name ?? 'Root' }}</td>
                                    <td class="p-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            {{ $menu->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $menu->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('menus.edit', $menu) }}"
                                               class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md text-xs">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('menus.destroy', $menu) }}" class="inline" onsubmit="return confirm('Delete this menu?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                @if($menu->children->count() > 0)
                                    @foreach($menu->children as $child)
                                        <tr class="hover:bg-gray-50 transition bg-gray-50">
                                            <td class="p-4 font-medium text-gray-800 pl-8">└── {{ $child->name }}</td>
                                            <td class="p-4 text-gray-600">{{ $child->slug }}</td>
                                            <td class="p-4 text-gray-600">{{ $child->link ?? 'N/A' }}</td>
                                            <td class="p-4 text-gray-600">{{ $child->permission ?? 'N/A' }}</td>
                                            <td class="p-4 text-gray-600">{{ $child->order }}</td>
                                            <td class="p-4 text-gray-600">{{ $child->parent?->name ?? 'Root' }}</td>
                                            <td class="p-4 text-center">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $child->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $child->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="p-4">
                                                <div class="flex justify-center gap-2">
                                                    <a href="{{ route('menus.edit', $child) }}"
                                                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md text-xs">
                                                        Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('menus.destroy', $child) }}" class="inline" onsubmit="return confirm('Delete this menu?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
