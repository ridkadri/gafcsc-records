<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Welcome Message --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ __("Welcome back, :name!", ['name' => Auth::user()->name]) }}</h3>
                            <p class="text-gray-600 mt-1">{{ Auth::user()->getRoleDisplayName() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ now()->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                
                {{-- Total Staff --}}
                @if(Auth::user()->canViewStaff())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Total Staff</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Staff::count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Military Staff --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Military</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Staff::where('type', 'military')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Civilian Staff --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Civilian</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Staff::where('type', 'civilian')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Total Users --}}
                @if(Auth::user()->canManageUsers())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">System Users</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Quick Links --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            
                            @if(Auth::user()->staff_id && Auth::user()->staff)
                                <a href="{{ route('staff.profile.show') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="text-gray-700 font-medium">View My Profile</span>
                                </a>
                            @endif

                            @if(Auth::user()->canViewStaff())
                                <a href="{{ route('staff.index') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-gray-700 font-medium">Manage Staff</span>
                                </a>

                                <a href="{{ route('staff.create') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    <span class="text-gray-700 font-medium">Add New Staff</span>
                                </a>
                            @endif

                            @if(Auth::user()->canManageUsers())
                                <a href="{{ route('users.index') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <span class="text-gray-700 font-medium">Manage Users</span>
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Account Settings</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- System Information --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Your Role</span>
                                <span class="font-semibold text-gray-900">{{ Auth::user()->getRoleDisplayName() }}</span>
                            </div>
                            
                            @if(Auth::user()->staff_id && Auth::user()->staff)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Staff Type</span>
                                    <span class="font-semibold text-gray-900">{{ ucfirst(Auth::user()->staff->type) }}</span>
                                </div>
                                
                                @if(Auth::user()->staff->department)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Department</span>
                                        <span class="font-semibold text-gray-900">{{ Auth::user()->staff->department }}</span>
                                    </div>
                                @endif
                            @endif
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Account Created</span>
                                <span class="font-semibold text-gray-900">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Last Login</span>
                                <span class="font-semibold text-gray-900">{{ Auth::user()->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>