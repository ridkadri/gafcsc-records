@extends('layouts.inventory')

@section('header')
<div class="flex justify-between items-center">
    <div class="flex items-center space-x-4">
        <div class="h-12 w-12 rounded-lg flex items-center justify-center text-white font-bold text-sm"
             style="background-color: {{ $inventoryCategory->color }};">
            {{ strtoupper(substr($inventoryCategory->code, 0, 4)) }}
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Items Needing Inspection</h1>
            <p class="text-gray-600 text-sm">{{ $inventoryCategory->name }} - Items requiring inspection or overdue</p>
        </div>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('inventory.categories.show', $inventoryCategory) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
            </svg>
            View Category
        </a>
        <a href="{{ route('inventory.categories.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Back to Categories
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Summary Stats -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-800">Items Needing Inspection</p>
                        <p class="text-2xl font-bold text-red-900">{{ $inspectionItems->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-800">Overdue Inspections</p>
                        <p class="text-2xl font-bold text-yellow-900">
                            {{ $inspectionItems->where('next_inspection', '<', now())->whereNotNull('next_inspection')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Never Inspected</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $inspectionItems->whereNull('next_inspection')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Items Requiring Inspection</h3>
                    <div class="flex space-x-2">
                        <button onclick="selectAll()" 
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Select All
                        </button>
                        <button onclick="markInspected()" 
                                class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Mark as Inspected
                        </button>
                    </div>
                </div>
            </div>

            @if($inspectionItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="select-all" 
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inspection Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Inspection</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Due</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inspectionItems as $item)
                                <tr class="hover:bg-gray-50 {{ $item->next_inspection && $item->next_inspection < now() ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                                               class="item-checkbox focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center text-white text-xs font-bold mr-3"
                                                 style="background-color: {{ $inventoryCategory->color }};">
                                                {{ strtoupper(substr($item->item_code ?? $item->id, -3)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->item_code }}</div>
                                                @if($item->serial_number)
                                                    <div class="text-xs text-gray-400">S/N: {{ $item->serial_number }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @elseif($item->status === 'maintenance')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Maintenance
                                            </span>
                                        @elseif($item->status === 'needs_inspection')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Needs Inspection
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->next_inspection && $item->next_inspection < now())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Overdue
                                            </span>
                                        @elseif(is_null($item->next_inspection))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Never Inspected
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Due Soon
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->last_inspection_date ? $item->last_inspection_date->format('M j, Y') : 'Never' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($item->next_inspection)
                                            <span class="{{ $item->next_inspection < now() ? 'text-red-600 font-medium' : '' }}">
                                                {{ $item->next_inspection->format('M j, Y') }}
                                            </span>
                                            @if($item->next_inspection < now())
                                                <div class="text-xs text-red-500">
                                                    {{ $item->next_inspection->diffForHumans() }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-gray-400">Not scheduled</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->location ?? 'Not specified' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('inventory.show', $item) }}" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        <button onclick="scheduleInspection({{ $item->id }})" 
                                                class="text-green-600 hover:text-green-900">Schedule</button>
                                        @if($item->activeAssignments->count() == 0)
                                            <button onclick="markInspectedSingle({{ $item->id }})" 
                                                    class="text-purple-600 hover:text-purple-900">Mark Inspected</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($inspectionItems->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200">
                        {{ $inspectionItems->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">All items are up to date!</h3>
                    <p class="mt-1 text-sm text-gray-500">No items in this category require inspection at this time.</p>
                    <div class="mt-6">
                        <a href="{{ route('inventory.categories.show', $inventoryCategory) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            View Category Details
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions Card -->
        @if($inspectionItems->count() > 0)
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Schedule Bulk Inspection</h4>
                            <p class="text-sm text-gray-600 mb-3">Set inspection dates for multiple items at once</p>
                            <button onclick="bulkSchedule()" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Schedule Selected
                            </button>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Export Report</h4>
                            <p class="text-sm text-gray-600 mb-3">Download inspection report for this category</p>
                            <button onclick="exportReport()" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Download CSV
                            </button>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Send Reminders</h4>
                            <p class="text-sm text-gray-600 mb-3">Notify staff about upcoming inspections</p>
                            <button onclick="sendReminders()" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                Send Notifications
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modals and Forms -->
<div id="inspection-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="inspection-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule Inspection</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="next_inspection" class="block text-sm font-medium text-gray-700">Next Inspection Date</label>
                            <input type="date" name="next_inspection" id="next_inspection" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="inspection_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="inspection_notes" id="inspection_notes" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Optional notes about the inspection schedule..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Schedule Inspection
                    </button>
                    <button type="button" onclick="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Select all functionality
    function selectAll() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const selectAllBox = document.getElementById('select-all');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(cb => cb.checked = !allChecked);
        selectAllBox.checked = !allChecked;
    }

    // Individual checkbox handler
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Schedule inspection for single item
    function scheduleInspection(itemId) {
        document.getElementById('inspection-form').action = `/inventory/${itemId}/schedule-inspection`;
        document.getElementById('inspection-modal').classList.remove('hidden');
    }

    // Mark single item as inspected
    function markInspectedSingle(itemId) {
        if (confirm('Mark this item as inspected? This will update the last inspection date to today.')) {
            fetch(`/inventory/${itemId}/mark-inspected`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error updating inspection status');
                }
            });
        }
    }

    // Bulk schedule inspection
    function bulkSchedule() {
        const selected = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) {
            alert('Please select items to schedule inspection for');
            return;
        }

        document.getElementById('inspection-form').action = '/inventory/bulk-schedule-inspection';
        
        // Add hidden inputs for selected items
        const form = document.getElementById('inspection-form');
        // Remove existing hidden inputs
        form.querySelectorAll('input[name="selected_items[]"]').forEach(input => input.remove());
        
        // Add new hidden inputs
        selected.forEach(itemId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_items[]';
            input.value = itemId;
            form.appendChild(input);
        });

        document.getElementById('inspection-modal').classList.remove('hidden');
    }

    // Export report
    function exportReport() {
        const categoryId = {{ $inventoryCategory->id }};
        window.location.href = `/inventory/categories/${categoryId}/inspection-report/export`;
    }

    // Send reminders
    function sendReminders() {
        if (confirm('Send inspection reminders to relevant staff members?')) {
            fetch(`/inventory/categories/{{ $inventoryCategory->id }}/send-inspection-reminders`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    alert('Inspection reminders sent successfully!');
                } else {
                    alert('Error sending reminders');
                }
            });
        }
    }

    // Close modal
    function closeModal() {
        document.getElementById('inspection-modal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('inspection-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Update select-all checkbox based on individual selections
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-checkbox')) {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const selectAllBox = document.getElementById('select-all');
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                selectAllBox.indeterminate = false;
                selectAllBox.checked = false;
            } else if (checkedBoxes.length === checkboxes.length) {
                selectAllBox.indeterminate = false;
                selectAllBox.checked = true;
            } else {
                selectAllBox.indeterminate = true;
            }
        }
    });
</script>
@endsection
                }
            });
        }
    }

    // Mark selected items as inspected
    function markInspected() {
        const selected = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) {
            alert('Please select items to mark as inspected');
            return;
        }

        if (confirm(`Mark ${selected.length} item(s) as inspected?`)) {
            fetch('/inventory/bulk-mark-inspected', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ items: selected })
            }).then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error updating inspection status');