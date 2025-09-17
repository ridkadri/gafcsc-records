@extends('layouts.inventory')
@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assignment #{{ $assignment->id }}
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('inventory.assignments.index') }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Assignments
            </a>
            @if(Auth::user()->canManageStaff() && $assignment->status === 'active')
                <a href="{{ route('inventory.assignments.return', $assignment) }}" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Process Return
                </a>
            @endif
        </div>
    </div>
@endsection
@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Assignment Status Alert -->
            @if($assignment->isOverdue())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Assignment Overdue</h3>
                            <p class="text-sm text-red-700 mt-1">
                                This assignment was due {{ $assignment->expected_return_date->diffForHumans() }}.
                                Please contact the staff member or process the return.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Assignment Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Assignment Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assignment Details</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-600 font-medium">Assignment ID:</span>
                                    <span class="font-mono text-gray-900">#{{ $assignment->id }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-600 font-medium">Status:</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $assignment->status_color }}">
                                        {{ ucfirst($assignment->status) }}
                                        @if($assignment->isOverdue())
                                            <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </span>
                                </div>

                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-600 font-medium">Quantity:</span>
                                    <span class="text-gray-900 font-semibold">{{ $assignment->quantity }}</span>
                                </div>

                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-600 font-medium">Assigned Date:</span>
                                    <span class="text-gray-900">{{ $assignment->assigned_date->format('M j, Y') }}</span>
                                </div>

                                @if($assignment->expected_return_date)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-gray-600 font-medium">Expected Return:</span>
                                        <span class="text-gray-900 {{ $assignment->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                            {{ $assignment->expected_return_date->format('M j, Y') }}
                                            @if($assignment->isOverdue())
                                                <span class="text-sm">({{ $assignment->expected_return_date->diffForHumans() }})</span>
                                            @endif
                                        </span>
                                    </div>
                                @endif

                                @if($assignment->actual_return_date)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="text-gray-600 font-medium">Actual Return:</span>
                                        <span class="text-green-600 font-semibold">{{ $assignment->actual_return_date->format('M j, Y') }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between items-center py-3">
                                    <span class="text-gray-600 font-medium">Assigned By:</span>
                                    <span class="text-gray-900">{{ $assignment->assignedBy->name }}</span>
                                </div>

                                @if($assignment->returnedTo)
                                    <div class="flex justify-between items-center py-3">
                                        <span class="text-gray-600 font-medium">Returned To:</span>
                                        <span class="text-gray-900">{{ $assignment->returnedTo->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            
                            @if(Auth::user()->canManageStaff() && $assignment->status === 'active')
                                <div class="space-y-3">
                                    <a href="{{ route('inventory.assignments.return', $assignment) }}" 
                                       class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Process Return
                                    </a>

                                    <button onclick="toggleMarkLostForm()" 
                                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Mark as Lost
                                    </button>

                                    <form method="POST" action="{{ route('inventory.assignments.send-reminder', $assignment) }}" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                            </svg>
                                            Send Reminder
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <!-- Mark as Lost Form (Hidden by default) -->
                            <div id="mark-lost-form" class="hidden mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <h4 class="font-medium text-red-900 mb-3">Mark Assignment as Lost</h4>
                                <form method="POST" action="{{ route('inventory.assignments.mark-lost', $assignment) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-red-700">Reason for Loss *</label>
                                        <textarea name="notes" required rows="3" class="mt-1 block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                                                  placeholder="Please provide details about how/when the item was lost..."></textarea>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                            Confirm Lost
                                        </button>
                                        <button type="button" onclick="toggleMarkLostForm()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Staff Member</h3>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-full {{ $assignment->staff->isMilitary() ? 'bg-gradient-to-r from-green-600 to-green-800' : 'bg-gradient-to-r from-blue-500 to-purple-600' }} flex items-center justify-center">
                                    <span class="text-white font-medium text-lg">
                                        {{ $assignment->staff->initials }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ $assignment->staff->name }}</h4>
                                <p class="text-gray-600">{{ $assignment->staff->service_number }}</p>
                                @if($assignment->staff->rank)
                                    <p class="text-sm text-gray-500">{{ $assignment->staff->rank }}</p>
                                @endif
                            </div>
                        </div>

                        <dl class="space-y-3">
                            @if($assignment->staff->department)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Department:</dt>
                                    <dd class="font-medium text-gray-900">{{ $assignment->staff->department }}</dd>
                                </div>
                            @endif
                            
                            @if($assignment->staff->appointment)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Appointment:</dt>
                                    <dd class="font-medium text-gray-900">{{ $assignment->staff->appointment }}</dd>
                                </div>
                            @endif

                            @if($assignment->staff->location)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Location:</dt>
                                    <dd class="font-medium text-gray-900">{{ $assignment->staff->location }}</dd>
                                </div>
                            @endif

                            <div class="flex justify-between">
                                <dt class="text-gray-600">Staff Type:</dt>
                                <dd class="font-medium {{ $assignment->staff->isMilitary() ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ ucfirst($assignment->staff->staff_type) }}
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <a href="{{ route('staff.show', $assignment->staff) }}" 
                               class="text-blue-600 hover:text-blue-900 font-medium">
                                View Full Staff Profile →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Item Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Item</h3>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-lg flex items-center justify-center text-white font-bold text-lg"
                                     style="background-color: {{ $assignment->item->category->color }}">
                                    {{ $assignment->item->category->code }}
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ $assignment->item->name }}</h4>
                                <p class="text-gray-600">{{ $assignment->item->item_code }}</p>
                                @if($assignment->item->barcode)
                                    <p class="text-sm font-mono text-gray-500">{{ $assignment->item->barcode }}</p>
                                @endif
                            </div>
                        </div>

                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Category:</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                                          style="background-color: {{ $assignment->item->category->color }}">
                                        {{ $assignment->item->category->name }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Condition:</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $assignment->item->condition_color }}">
                                        {{ ucfirst($assignment->item->condition) }}
                                    </span>
                                </dd>
                            </div>

                            @if($assignment->item->serial_number)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Serial Number:</dt>
                                    <dd class="font-mono text-sm text-gray-900">{{ $assignment->item->serial_number }}</dd>
                                </div>
                            @endif

                            @if($assignment->item->location)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Location:</dt>
                                    <dd class="font-medium text-gray-900">{{ $assignment->item->location }}</dd>
                                </div>
                            @endif
                        </dl>

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <a href="{{ route('inventory.show', $assignment->item) }}" 
                               class="text-blue-600 hover:text-blue-900 font-medium">
                                View Full Item Details →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($assignment->assignment_notes || $assignment->return_notes)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                        
                        @if($assignment->assignment_notes)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">Assignment Notes:</h4>
                                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $assignment->assignment_notes }}</p>
                            </div>
                        @endif

                        @if($assignment->return_notes)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Return Notes:</h4>
                                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $assignment->return_notes }}</p>
                                @if($assignment->condition_on_return)
                                    <p class="text-sm text-gray-600 mt-2">
                                        Returned in <strong>{{ $assignment->condition_on_return }}</strong> condition
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleMarkLostForm() {
            const form = document.getElementById('mark-lost-form');
            form.classList.toggle('hidden');
        }
    </script>
@endsection