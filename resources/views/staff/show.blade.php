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
                <div class="px-6 py-6 {{ $staff->isMilitary() ? 'bg-gradient-to-r from-green-600 to-green-800' : 'bg-gradient-to-r from-blue-500 to-purple-600' }}">
                    <div class="flex items-center space-x-6">
                        <!-- Profile Picture -->
                        <div class="h-24 w-24 rounded-full bg-white overflow-hidden ring-4 ring-white ring-opacity-30">
                            @if($staff->hasProfilePicture())
                                <img src="{{ $staff->getProfilePictureUrl() }}" 
                                     alt="{{ $staff->name }}" 
                                     class="h-full w-full object-cover">
                            @else
                                <img src="{{ $staff->getDefaultAvatar() }}" 
                                     alt="{{ $staff->name }}" 
                                     class="h-full w-full object-cover">
                            @endif
                        </div>
    
                        <div class="text-white">
                            <div class="flex items-center space-x-2 mb-1">
                                <h1 class="text-3xl font-bold">{{ $staff->name }}</h1>
                                @if($staff->isMilitary())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                        🎖️ Military
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                        👔 Civilian
                                    </span>
                                @endif
                            </div>
                            <p class="text-white text-opacity-90 text-lg">SVC No: {{ $staff->service_number }}</p>
                            @if($staff->isMilitary())
                                @if($staff->rank)
                                    <p class="text-white text-opacity-80 text-sm mt-1">{{ $staff->rank }}</p>
                                @endif
                            @else
                                @if($staff->present_grade)
                                    <p class="text-white text-opacity-80 text-sm mt-1">{{ $staff->present_grade }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="p-6">
                    @if($staff->isMilitary())
                        <!-- MILITARY DETAILS -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Military Personnel Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Service Number</span>
                                            <span class="text-sm text-gray-900 font-mono">{{ $staff->service_number }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Rank</span>
                                            <span class="text-sm text-gray-900">
                                                @if($staff->rank)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                                            <span class="text-sm font-medium text-gray-500">Sex</span>
                                            <span class="text-sm text-gray-900">{{ $staff->sex ?: 'Not specified' }}</span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Trade</span>
                                            <span class="text-sm text-gray-900">{{ $staff->trade ?: 'Not specified' }}</span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Arm of Service</span>
                                            <span class="text-sm text-gray-900">{{ $staff->arm_of_service ?: 'Not specified' }}</span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Status</span>
                                            <span class="text-sm text-gray-900">
                                                @if($staff->deployment)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        {{ $staff->deployment }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Enrollment</span>
                                            <span class="text-sm text-gray-900">
                                                {{ $staff->date_of_enrollment ? $staff->date_of_enrollment->format('F j, Y') : 'Not specified' }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Birth</span>
                                            <span class="text-sm text-gray-900">
                                                {{ $staff->date_of_birth ? $staff->date_of_birth->format('F j, Y') : 'Not specified' }}
                                                @if($staff->date_of_birth)
                                                    <span class="text-xs text-gray-500">({{ $staff->age }} years old)</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Last Promotion</span>
                                            <span class="text-sm text-gray-900">
                                                {{ $staff->last_promotion_date ? $staff->last_promotion_date->format('F j, Y') : 'Not specified' }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Department/Unit</span>
                                            <span class="text-sm text-gray-900">
                                                @if($staff->department)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        📍 {{ $staff->department }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                @endif
                                            </span>
                                        </div>

                                        @if($staff->date_of_enrollment)
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Years of Service</span>
                                            <span class="text-sm text-gray-900 font-semibold text-green-600">
                                                {{ $staff->years_of_service }} years
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- CIVILIAN DETAILS -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Civilian Personnel Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Present Grade</span>
                                            <span class="text-sm text-gray-900">
                                                @if($staff->present_grade)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $staff->present_grade }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Staff Category</span>
                                            <span class="text-sm text-gray-900">
                                                @if($staff->staff_category)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $staff->staff_category === 'Senior' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                        {{ $staff->staff_category }} Staff
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
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                                                @if($staff->department)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        📍 {{ $staff->department }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Employment</span>
                                            <span class="text-sm text-gray-900">
                                                {{ $staff->date_of_employment ? $staff->date_of_employment->format('F j, Y') : 'Not specified' }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Birth</span>
                                            <span class="text-sm text-gray-900">
                                                {{ $staff->date_of_birth ? $staff->date_of_birth->format('F j, Y') : 'Not specified' }}
                                                @if($staff->date_of_birth)
                                                    <span class="text-xs text-gray-500">({{ $staff->age }} years old)</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Last Promotion</span>
                                            <span class="text-sm text-gray-900">
                                                {{ $staff->last_promotion_date ? $staff->last_promotion_date->format('F j, Y') : 'Not specified' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Posting</span>
                                            <span class="text-sm text-gray-900">
                                                {{ $staff->date_of_posting ? $staff->date_of_posting->format('F j, Y') : 'Not specified' }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Location</span>
                                            <span class="text-sm text-gray-900">
                                                @if($staff->location)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        📍 {{ $staff->location }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Status</span>
                                            <span class="text-sm text-gray-900">
                                                @if($staff->deployment)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        {{ $staff->deployment }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                @endif
                                            </span>
                                        </div>

                                        @if($staff->date_of_employment)
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Years of Service</span>
                                            <span class="text-sm text-gray-900 font-semibold text-blue-600">
                                                {{ $staff->years_of_service }} years
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- ADD USER ACCOUNT INFO HERE -->
                    @if($staff->user)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-medium text-blue-800">User Account</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            <strong>Username:</strong> {{ $staff->user->username }}<br>
                            <strong>Default Password:</strong> gafcsc@123<br>
                            <strong>Status:</strong> 
                            @if($staff->user->needsPasswordChange())
                                <span class="text-orange-600">Needs password change</span>
                            @else
                                <span class="text-green-600">Password set</span>
                            @endif
                        </p>
                    </div>
                    @endif

                    <!-- Assigned Inventory Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Assigned Inventory</h3>
                            @if($staff->activeInventoryAssignments->count() > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $staff->activeInventoryAssignments->count() }} {{ Str::plural('Item', $staff->activeInventoryAssignments->count()) }}
                                </span>
                            @endif
                        </div>

                        @if($staff->activeInventoryAssignments->count() > 0)
                            <div class="space-y-3">
                                @foreach($staff->activeInventoryAssignments as $assignment)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <!-- Item Icon -->
                                                    <div class="flex-shrink-0">
                                                        @if($assignment->item && $assignment->item->category)
                                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                                                                 style="background-color: {{ $assignment->item->category->color }}">
                                                                {{ $assignment->item->category->code }}
                                                            </div>
                                                        @else
                                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-gray-300 text-gray-600 text-sm font-bold">
                                                                ?
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Item Details -->
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2">
                                                            <a href="{{ route('inventory.show', $assignment->item) }}" 
                                                               class="text-base font-semibold text-gray-900 hover:text-blue-600 transition">
                                                                {{ $assignment->item->name }}
                                                            </a>
                                                            @if($assignment->item->category)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-white"
                                                                      style="background-color: {{ $assignment->item->category->color }}">
                                                                    {{ $assignment->item->category->name }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            <span class="font-mono">{{ $assignment->item->item_code }}</span>
                                                            @if($assignment->item->serial_number)
                                                                • SN: <span class="font-mono">{{ $assignment->item->serial_number }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Assignment Info -->
                                                <div class="ml-13 grid grid-cols-2 md:grid-cols-4 gap-4 mt-3 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Quantity:</span>
                                                        <span class="font-medium text-gray-900 ml-1">{{ $assignment->quantity }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Checked Out:</span>
                                                        <span class="font-medium text-gray-900 ml-1">{{ $assignment->assigned_date->format('M j, Y') }}</span>
                                                    </div>
                                                    @if($assignment->expected_return_date)
                                                        <div>
                                                            <span class="text-gray-500">Due Back:</span>
                                                            <span class="font-medium {{ $assignment->isOverdue() ? 'text-red-600' : 'text-gray-900' }} ml-1">
                                                                {{ $assignment->expected_return_date->format('M j, Y') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <span class="text-gray-500">Condition:</span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $assignment->item->condition_color }} ml-1">
                                                            {{ ucfirst($assignment->item->condition) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                @if($assignment->notes)
                                                    <div class="ml-13 mt-2">
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">Notes:</span> {{ $assignment->notes }}
                                                        </p>
                                                    </div>
                                                @endif

                                                @if($assignment->isOverdue())
                                                    <div class="ml-13 mt-2">
                                                        <div class="flex items-center text-red-600 text-sm">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <span class="font-medium">OVERDUE by {{ $assignment->expected_return_date->diffInDays(now()) }} days</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Status Badge -->
                                            <div class="ml-4">
                                                @if($assignment->isOverdue())
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Overdue
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Summary Stats -->
                            @if($staff->activeInventoryAssignments->count() > 1)
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-blue-700 font-medium">Total Items:</span>
                                            <span class="text-blue-900 font-bold ml-2">{{ $staff->activeInventoryAssignments->count() }}</span>
                                        </div>
                                        <div>
                                            <span class="text-blue-700 font-medium">Total Quantity:</span>
                                            <span class="text-blue-900 font-bold ml-2">{{ $staff->activeInventoryAssignments->sum('quantity') }}</span>
                                        </div>
                                        @if($staff->overdueInventoryAssignments->count() > 0)
                                            <div>
                                                <span class="text-red-700 font-medium">Overdue:</span>
                                                <span class="text-red-900 font-bold ml-2">{{ $staff->overdueInventoryAssignments->count() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No Inventory Assigned</h3>
                                <p class="mt-1 text-sm text-gray-500">This staff member has no active inventory assignments.</p>
                                @if(Auth::user()->canManageStaff())
                                    <div class="mt-4">
                                        <a href="{{ route('inventory.index') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Assign Inventory
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- System Information -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Record ID</span>
                                    <span class="text-sm text-gray-900 font-mono">#{{ $staff->id }}</span>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Date Added</span>
                                    <span class="text-sm text-gray-900">{{ $staff->created_at->format('F j, Y') }}</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Last Updated</span>
                                    <span class="text-sm text-gray-900">{{ $staff->updated_at->format('F j, Y \a\t g:i A') }}</span>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Days in System</span>
                                    <span class="text-sm text-gray-900">{{ $staff->created_at->diffInDays(now()) }} days</span>
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
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
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