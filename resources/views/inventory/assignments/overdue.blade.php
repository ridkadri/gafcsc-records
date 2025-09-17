@extends('layouts.inventory')
@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Overdue Returns') }}
        </h2>
        <a href="{{ route('inventory.assignments.index') }}" 
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Back to Assignments
        </a>
    </div>
@endsection
@section('content')    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Banner -->
            @if($assignments->count() > 0)
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Overdue Items Alert</h3>
                            <p class="text-sm text-red-700 mt-1">
                                {{ $assignments->count() }} {{ $assignments->count() === 1 ? 'assignment' : 'assignments' }} are past their expected return dates.
                                Contact the assigned staff members or process returns immediately.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Overdue Assignments</h3>
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ $assignments->count() }} Overdue
                            </span>
                            @if(Auth::user()->canManageStaff() && $assignments->count() > 0)
                                <button onclick="toggleBulkActions()" 
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Bulk Actions
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions (Hidden by default) -->
                @if(Auth::user()->canManageStaff())
                    <div id="bulk-actions" class="hidden px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                        <form method="POST" action="{{ route('inventory.assignments.bulk-return') }}" onsubmit="return confirmBulkReturn()">
                            @csrf
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <label for="select-all" class="ml-2 text-sm font-medium text-gray-700">Select All</label>
                                </div>
                                <select name="condition_on_return" required class="rounded-md border-gray-300 shadow-sm text-sm">
                                    <option value="">Return Condition</option>
                                    <option value="good">Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                    <option value="damaged">Damaged</option>
                                </select>
                                <input type="text" name="return_notes" placeholder="Return notes (optional)" 
                                       class="flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    Process Selected Returns
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Overdue Assignments Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @if(Auth::user()->canManageStaff())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm" disabled>
                                    </th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Staff Member
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Days Overdue
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assignments as $assignment)
                                <tr class="bg-red-50 hover:bg-red-100">
                                    @if(Auth::user()->canManageStaff())
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="assignment_ids[]" value="{{ $assignment->id }}" 
                                                   class="assignment-checkbox rounded border-gray-300 text-blue-600 shadow-sm">
                                        </td>
                                    @endif
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full {{ $assignment->staff->isMilitary() ? 'bg-gradient-to-r from-green-600 to-green-800' : 'bg-gradient-to-r from-blue-500 to-purple-600' }} flex items-center justify-center">
                                                    <span class="text-white font-medium text-sm">
                                                        {{ $assignment->staff->initials }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $assignment->staff->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $assignment->staff->service_number }}</div>
                                                @if($assignment->staff->department)
                                                    <div class="text-xs text-gray-400">{{ $assignment->staff->department }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white font-bold text-xs"
                                                     style="background-color: {{ $assignment->item->category->color }}">
                                                    {{ $assignment->item->category->code }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $assignment->item->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $assignment->item->item_code }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $assignment->quantity }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                        {{ $assignment->expected_return_date->format('M j, Y') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ abs($assignment->expected_return_date->diffInDays(now())) }} 
                                            {{ abs($assignment->expected_return_date->diffInDays(now())) === 1 ? 'day' : 'days' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('inventory.assignments.show', $assignment) }}" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        
                                        @if(Auth::user()->canManageStaff())
                                            <a href="{{ route('inventory.assignments.return', $assignment) }}" 
                                               class="text-green-600 hover:text-green-900">Return</a>
                                            
                                            <form method="POST" action="{{ route('inventory.assignments.send-reminder', $assignment) }}" 
                                                  class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                    Remind
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->canManageStaff() ? '7' : '6' }}" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Overdue Assignments</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            All assignments are either returned or within their expected return dates.
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('inventory.assignments.index') }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                View All Assignments
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Summary Footer -->
                @if($assignments->count() > 0)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ $assignments->count() }}</span> 
                                overdue {{ $assignments->count() === 1 ? 'assignment' : 'assignments' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Oldest overdue: 
                                <span class="font-medium text-red-600">
                                    {{ $assignments->sortBy('expected_return_date')->first()?->expected_return_date->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Recommendations -->
            @if(Auth::user()->canManageStaff() && $assignments->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recommended Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Process Returns</h4>
                                    <p class="text-sm text-gray-500">Contact staff members and process item returns immediately.</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Send Reminders</h4>
                                    <p class="text-sm text-gray-500">Send reminder notifications to staff with overdue items.</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Review Policies</h4>
                                    <p class="text-sm text-gray-500">Consider updating return date policies or consequences for overdue items.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleBulkActions() {
            const bulkActions = document.getElementById('bulk-actions');
            bulkActions.classList.toggle('hidden');
        }

        // Select all functionality
        document.getElementById('select-all')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.assignment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        function confirmBulkReturn() {
            const checkedBoxes = document.querySelectorAll('.assignment-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select at least one assignment to process.');
                return false;
            }
            
            const condition = document.querySelector('select[name="condition_on_return"]').value;
            if (!condition) {
                alert('Please select a return condition.');
                return false;
            }
            
            return confirm(`Are you sure you want to process ${checkedBoxes.length} returns as "${condition}" condition?`);
        }
    </script>
@endsection