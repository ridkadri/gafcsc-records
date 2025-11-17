<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New User</h2>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Please fix the following errors:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                        @csrf

                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                        placeholder="Enter full name" required>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                        placeholder="user@gafcsc.edu.gh" required>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Security</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                                    <input type="password" id="password" name="password" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="Minimum 8 characters" required>
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="Confirm password" required>
                                </div>
                            </div>
                        </div>

                        <!-- Role Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Role</h3>
                            <div class="space-y-3">
                                
                                @if(auth()->user()->isSuperAdmin())
                                    <!-- Super Admin Option (Only for Super Admins) -->
                                    <label class="flex items-start space-x-3 p-4 border-2 border-red-200 rounded-lg hover:bg-red-50 cursor-pointer">
                                        <input type="radio" name="role" value="super_admin" {{ old('role') === 'super_admin' ? 'checked' : '' }} 
                                               class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <span class="text-sm font-medium text-gray-900">Super Administrator</span>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Supreme Access</span>
                                            </div>
                                            <p class="text-sm text-red-600 mt-1 font-medium">
                                                HIGHEST LEVEL: Can manage everything including other Super Administrators. Use with extreme caution.
                                            </p>
                                        </div>
                                    </label>
                                @endif

                                <!-- Admin Role -->
                                <label class="flex items-start space-x-3 p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }} 
                                           class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900">Administrator</span>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Full Access</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Can manage users, staff, and inventory. Cannot manage Super Administrators.</p>
                                    </div>
                                </label>

                                <!-- Viewer Role -->
                                <label class="flex items-start space-x-3 p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="role" value="viewer" {{ old('role', 'viewer') === 'viewer' ? 'checked' : '' }} 
                                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900">Viewer</span>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Read Only</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Can only view information. Cannot create, edit, or delete.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Create User
                            </button>
                        </div>
                    </form>

                    <!-- Security Note -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm">
                                <h4 class="font-medium text-blue-800 mb-1">Security Note</h4>
                                <p class="text-blue-700">Users will be automatically verified and can log in immediately after creation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
                                    