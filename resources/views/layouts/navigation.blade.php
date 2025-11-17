<!-- Top Navigation for Main Application Layout -->
<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        <span class="ml-3 text-lg font-semibold text-gray-900 hidden md:block">GAFCSC</span>
                    </a>
                </div>
            </div>

            <div class="flex items-center">
                <!-- Main Navigation -->
                <div class="hidden space-x-8 sm:flex">
                    {{-- Dashboard - Only for non-viewers --}}
                    @if(!Auth::user()->isViewer())
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif

                    {{-- My Profile - Only show if user has staff profile --}}
                    @if(Auth::user()->staff_id && Auth::user()->staff)
                        <x-nav-link :href="route('staff.profile.show')" :active="request()->routeIs('staff.profile.*')"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('My Profile') }}
                        </x-nav-link>
                    @endif
                    
                    {{-- Staff - Show only if user can view staff (NOT for viewers) --}}
                    @if(Auth::user()->canViewStaff())
                        <x-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.index', 'staff.show', 'staff.edit', 'staff.create')"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ __('Staff') }}
                            @if(Auth::user()->isChiefClerk())
                                <span class="ml-1 text-xs text-gray-500">(Military)</span>
                            @elseif(Auth::user()->isCapo() || Auth::user()->isPeo())
                                <span class="ml-1 text-xs text-gray-500">(Civilian)</span>
                            @endif
                        </x-nav-link>
                    @endif

                    {{-- HOD Management Link --}}
                    @if(Auth::user()->canManageUsers())
                        <x-nav-link :href="route('hod-management.index')" :active="request()->routeIs('hod-management.*')">
                            {{ __('HOD Management') }}
                        </x-nav-link>
                    @endif
                    
                    {{-- Inventory - Show only to Super Admin and Military Admin --}}
                    @if(Auth::user()->canViewInventory())
                        <x-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')" 
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            {{ __('Inventory') }}
                        </x-nav-link>
                    @endif
                    
                    {{-- Users - Show only to Super Admin --}}
                    @if(Auth::user()->canManageUsers())
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            {{ __('Users') }}
                        </x-nav-link>
                    @endif
                </div>

                <!-- User Menu -->
                <div class="ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                                <div class="flex items-center">
                                    @php
                                        $hasStaff = Auth::user()->staff_id && Auth::user()->staff;
                                    @endphp
                                    
                                    @if($hasStaff)
                                        <img src="{{ Auth::user()->staff->getProfilePictureUrl() }}?v={{ Auth::user()->staff->updated_at->timestamp }}" 
                                             alt="{{ Auth::user()->name }}"
                                             class="h-8 w-8 rounded-full object-cover mr-2"
                                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=32&background=random'">
                                    @else
                                        <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="text-left hidden md:block">
                                        <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500">{{ Auth::user()->getRoleDisplayName() }}</div>
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
                                Manage Account
                            </div>

                            {{-- My Profile Link - Only if has staff profile --}}
                            @if(Auth::user()->staff_id && Auth::user()->staff)
                                <x-dropdown-link :href="route('staff.profile.show')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ __('My Profile') }}
                                </x-dropdown-link>
                            @endif

                            {{-- Settings - Show for everyone --}}
                            <x-dropdown-link :href="route('profile.edit')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ __('Settings') }}
                            </x-dropdown-link>
                            
                            <div class="border-t border-gray-100"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden ml-2">
                    <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>