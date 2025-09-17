<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Staff Details') }}
            </h2>
            @if(Auth::user()->canManageStaff())
                <div class="flex space-x-2">
                    <a href="{{ route('staff.edit', $staff) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header Section -->
                <div class="px-6 py-6 bg-gradient-to-r from-blue-500 to-purple-600">
                    <div class="flex items-center space-x-6">
                        <div class="h-20 w-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">
                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="text-white">
                            <h1 class="text-3xl font-bold">{{ $staff->name }}</h1>
                            <p class="text-blue-100 text-lg">SVC No: {{ $staff->service_number }}</p>
                            @if($staff->rank)
                                <p class="text-blue-100 text-sm">{{ $staff->rank }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-500">Full Name</span>
                                        <span class="text-sm text-gray-900">{{ $staff->name }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-500">Staff ID</span>
                                        <span class="text-sm text-gray-900 font-mono">{{ $staff->service_number }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-500">Rank/Title</span>
                                        <span class="text-sm text-gray-900">
                                            @if($staff->rank)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $staff->rank }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">Not specified</span>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-500">Appointment</span>
                                        <span class="text-sm text-gray-900">
                                            @if($staff->appointment)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $staff->appointment }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">Not specified</span>
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-500">Department</span>
                                        <span class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                📍 {{ $staff->department }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-500">Date Added</span>
                                        <span class="text-sm text-gray-900">{{ $staff->created_at->format('F j, Y') }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                                        <span class="text-sm text-gray-900">{{ $staff->updated_at->format('F j, Y \a\t g:i A') }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-500">Record ID</span>
                                        <span class="text-sm text-gray-900 font-mono">#{{ $staff->id }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Stats</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Days in system</span>
                                        <span class="text-gray-900">{{ $staff->created_at->diffInDays(now()) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Office colleagues</span>
                                        <span class="text-gray-900">
                                            {{ App\Models\Staff::where('department', $staff->department)->where('id', '!=', $staff->id)->count() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap justify-between items-center gap-4">
                            <a href="{{ route('staff.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                </svg>
                                Back to Staff List
                            </a>

                            @if(Auth::user()->canManageStaff())
                                <div class="flex space-x-2">
                                    <a href="{{ route('staff.edit', $staff) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Edit Details
                                    </a>

                                    <form method="POST" action="{{ route('staff.destroy', $staff) }}" 
                                          class="inline-block" 
                                          onsubmit="return confirm('Are you sure you want to delete {{ $staff->name }}? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 012 0v4a1 1 0 11-2 0V7zM12 7a1 1 0 112 0v4a1 1 0 11-2 0V7z" clip-rule="evenodd"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>