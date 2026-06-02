<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Role
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow rounded">

                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block">Role Name</label>
                        <input type="text" name="name"
                               class="w-full border p-2 rounded"
                               placeholder="admin, user">
                    </div>

                    <div class="mb-4">
                        <label class="block">Title</label>
                        <input type="text" name="title"
                               class="w-full border p-2 rounded"
                               placeholder="Administrator">
                    </div>

                    <button class="bg-green-500 text-white px-4 py-2 rounded">
                        Save
                    </button>

                    <a href="{{ route('roles.index') }}"
                       class="ml-2 text-gray-600">
                        Cancel
                    </a>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>