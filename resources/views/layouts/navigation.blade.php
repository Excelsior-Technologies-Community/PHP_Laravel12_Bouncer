<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            <!-- LEFT SIDE -->
            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- NAV LINKS -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    <!-- Dashboard (ALL USERS) -->
                    <x-nav-link :href="route('dashboard')"
                                :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    @auth

                        <!-- ADMIN ONLY -->
                        @if(auth()->user()->isAn('admin'))

                            <x-nav-link :href="route('roles.index')"
                                        :active="request()->routeIs('roles.*')">
                                Roles
                            </x-nav-link>

                            <x-nav-link :href="route('user.roles')"
                                        :active="request()->routeIs('user.roles')">
                                User Roles
                            </x-nav-link>

                        @endif

                        <!-- PERMISSION BASED -->
                        @can('manage-users')
                            <x-nav-link href="/users"
                                        :active="request()->is('users')">
                                Users
                            </x-nav-link>
                        @endcan

                    @endauth

                </div>
            </div>

            <!-- RIGHT SIDE (USER MENU) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                @auth
                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            <div class="flex items-center gap-2">

                                <!-- USER NAME -->
                                <span>{{ Auth::user()->name }}</span>

                                <!-- ROLE BADGE -->
                                @if(Auth::user()->isAn('admin'))
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                                        Admin
                                    </span>
                                @else
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                        User
                                    </span>
                                @endif

                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <!-- LOGOUT -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                         this.closest('form').submit();">
                                Logout
                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>
                @endauth

            </div>

            <!-- MOBILE MENU -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="p-2 text-gray-400 hover:text-gray-500">

                    ☰
                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE NAV -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link :href="route('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            @auth

                @if(auth()->user()->isAn('admin'))

                    <x-responsive-nav-link :href="route('roles.index')">
                        Roles
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('user.roles')">
                        User Roles
                    </x-responsive-nav-link>

                @endif

                @can('manage-users')
                    <x-responsive-nav-link href="/users">
                        Users
                    </x-responsive-nav-link>
                @endcan

            @endauth

        </div>

    </div>

</nav>