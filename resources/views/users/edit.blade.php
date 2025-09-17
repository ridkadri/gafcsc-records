<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            Change Role for {{ $user->name }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            Update the user's access level in the system.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- User Info -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white font-medium">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-400">
                                        Member since {{ $user->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Role -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Current Role
                            </label>
                            <div class="p-3 bg-gray-100 rounded-md">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Administrator
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Viewer
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- New Role Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                New Role
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-start space-x-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="role" value="admin" 
                                           {{ $user->role === 'admin' ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900">Administrator</span>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Full Access
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Can view, create, edit, and delete staff members. Can manage user roles.
                                        </p>
                                    </div>
                                </label>

                                <label class="flex items-start space-x-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="role" value="viewer" 
                                           {{ $user->role === 'viewer' ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900">Viewer</span>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Read Only
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Can only view staff member information. Cannot create, edit, or delete.
                                        </p>
                                    </div>
                                </label>
                            </div>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('users.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>