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
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">

                    <!-- Dashboard (ALL USERS) -->
                    <x-nav-link :href="route('dashboard')"
                                :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    @auth
                        <!-- Roles -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-roles'))
                            <x-nav-link :href="route('roles.index')"
                                        :active="request()->routeIs('roles.*')">
                                Roles
                            </x-nav-link>
                        @endif

                        <!-- User Role Assignment -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-users'))
                            <x-nav-link :href="route('user.roles')"
                                        :active="request()->routeIs('user.roles')">
                                User Roles
                            </x-nav-link>
                        @endif

                        <!-- Users -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-users'))
                            <x-nav-link :href="route('users.index')"
                                        :active="request()->routeIs('users.index')">
                                Users
                            </x-nav-link>
                        @endif

                        <!-- Trash -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-users'))
                            <x-nav-link :href="route('users.trashed')"
                                        :active="request()->routeIs('users.trashed')">
                                Trash
                            </x-nav-link>
                        @endif

                        <!-- Tenants -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-tenants'))
                            <x-nav-link :href="route('tenants.index')"
                                        :active="request()->routeIs('tenants.*')">
                                Tenants
                            </x-nav-link>
                        @endif

                        <!-- Settings -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-settings'))
                            <x-nav-link :href="route('settings.index')"
                                        :active="request()->routeIs('settings.*')">
                                Settings
                            </x-nav-link>
                        @endif

                        <!-- Menus -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-menus'))
                            <x-nav-link :href="route('menus.index')"
                                        :active="request()->routeIs('menus.*')">
                                Menus
                            </x-nav-link>
                        @endif

                        <!-- Activity Logs -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('view-activity-logs'))
                            <x-nav-link :href="route('activity.logs.index')"
                                        :active="request()->routeIs('activity.logs.*')">
                                Activity Logs
                            </x-nav-link>
                        @endif

                        <!-- Audit Logs -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('view-audit-logs'))
                            <x-nav-link :href="route('audit.logs.index')"
                                        :active="request()->routeIs('audit.logs.*')">
                                Audit Logs
                            </x-nav-link>
                        @endif

                        <!-- Impersonation -->
                        @if(auth()->user()->isAn('admin') || auth()->user()->can('impersonate-users'))
                            <x-nav-link :href="route('impersonation.sessions')"
                                        :active="request()->routeIs('impersonation.*')">
                                Impersonation
                            </x-nav-link>
                        @endif

                    @endauth

                </div>
            </div>

            <!-- RIGHT SIDE (USER MENU) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                @auth
                <!-- Notifications Bell -->
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp
                <a href="{{ route('notifications.index') }}" class="relative mr-4 text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

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

                        <x-dropdown-link :href="route('notifications.index')">
                            Notifications
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('notifications.preferences')">
                            Notification Preferences
                        </x-dropdown-link>

                        @if(session('impersonated_by'))
                            <x-dropdown-link :href="route('impersonation.leave')">
                                Leave Impersonation
                            </x-dropdown-link>
                        @endif

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
                @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-roles'))
                    <x-responsive-nav-link :href="route('roles.index')">
                        Roles
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-users'))
                    <x-responsive-nav-link :href="route('user.roles')">
                        User Roles
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('users.index')">
                        Users
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('users.trashed')">
                        Trash
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-tenants'))
                    <x-responsive-nav-link :href="route('tenants.index')">
                        Tenants
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-settings'))
                    <x-responsive-nav-link :href="route('settings.index')">
                        Settings
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAn('admin') || auth()->user()->can('manage-menus'))
                    <x-responsive-nav-link :href="route('menus.index')">
                        Menus
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAn('admin') || auth()->user()->can('view-activity-logs'))
                    <x-responsive-nav-link :href="route('activity.logs.index')">
                        Activity Logs
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAn('admin') || auth()->user()->can('view-audit-logs'))
                    <x-responsive-nav-link :href="route('audit.logs.index')">
                        Audit Logs
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAn('admin') || auth()->user()->can('impersonate-users'))
                    <x-responsive-nav-link :href="route('impersonation.sessions')">
                        Impersonation
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link :href="route('notifications.index')">
                    Notifications
                </x-responsive-nav-link>

            @endauth

        </div>

    </div>

</nav>
