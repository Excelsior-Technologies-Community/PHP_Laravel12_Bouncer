<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Role
            </h2>

            <a href="{{ route('roles.index') }}"
               class="text-sm text-gray-600 hover:text-gray-900">
                ← Back to Roles
            </a>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-xl p-8">

                <!-- Header -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Update Role Details
                    </h3>
                    <p class="text-sm text-gray-500">
                        Modify role information and save changes.
                    </p>
                </div>

                <!-- FORM -->
                <form method="POST" action="{{ route('roles.update',$role->id) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Role Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Role Name
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ $role->name }}"
                               class="w-full border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 rounded-lg p-3 outline-none transition"
                               required>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Role Title
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ $role->title }}"
                               class="w-full border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 rounded-lg p-3 outline-none transition"
                               required>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-3 pt-4">

                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow">
                            Update Role
                        </button>

                        <a href="{{ route('roles.index') }}"
                           class="text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>