@extends('layouts.inventory')
@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check Out Inventory Item') }}
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('inventory.assignments.store') }}" class="space-y-6">
                        @csrf

                        <!-- Item Selection -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Select Item</h3>
                            
                            <div class="mb-6">
                                <label for="item_id" class="block text-sm font-medium text-gray-700">Inventory Item *</label>
                                <select name="item_id" id="item_id" required onchange="updateItemInfo()"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select an item</option>
                                    @foreach($items->groupBy('category.name') as $categoryName => $categoryItems)
                                        <optgroup label="{{ $categoryName }}">
                                            @foreach($categoryItems as $inventoryItem)
                                                <option value="{{ $inventoryItem->id }}" 
                                                        data-available="{{ $inventoryItem->available_quantity }}"
                                                        data-name="{{ $inventoryItem->name }}"
                                                        data-code="{{ $inventoryItem->item_code }}"
                                                        {{ (request('item_id') == $inventoryItem->id || ($item && $item->id == $inventoryItem->id)) ? 'selected' : '' }}>
                                                    {{ $inventoryItem->name }} ({{ $inventoryItem->item_code }}) - {{ $inventoryItem->available_quantity }} available
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Item Info Display -->
                            <div id="item-info" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            <span id="selected-item-name"></span> (<span id="selected-item-code"></span>) - 
                                            <span id="selected-item-available"></span> units available
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Staff Selection -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Assign to Staff Member</h3>
                            
                            <div class="mb-6">
                                <label for="staff_id" class="block text-sm font-medium text-gray-700">Staff Member *</label>
                                <select name="staff_id" id="staff_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select staff member</option>
                                    @foreach($staff->groupBy('department') as $department => $departmentStaff)
                                        <optgroup label="{{ $department }}">
                                            @foreach($departmentStaff as $staffMember)
                                                <option value="{{ $staffMember->id }}" {{ old('staff_id') == $staffMember->id ? 'selected' : '' }}>
                                                    {{ $staffMember->name }} ({{ $staffMember->service_number }}) - {{ $staffMember->rank ?? 'No rank' }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Assignment Details -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Quantity -->
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" 
                                           min="1" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">Maximum available will be set when you select an item</p>
                                    @error('quantity')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Expected Return Date -->
                                <div>
                                    <label for="expected_return_date" class="block text-sm font-medium text-gray-700">Expected Return Date</label>
                                    <input type="date" name="expected_return_date" id="expected_return_date" 
                                           value="{{ old('expected_return_date') }}" min="{{ date('Y-m-d') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">Optional: Set when the item should be returned</p>
                                    @error('expected_return_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Assignment Notes -->
                            <div class="mt-6">
                                <label for="assignment_notes" class="block text-sm font-medium text-gray-700">Assignment Notes</label>
                                <textarea name="assignment_notes" id="assignment_notes" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Optional notes about this assignment...">{{ old('assignment_notes') }}</textarea>
                                @error('assignment_notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Assignment Summary</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><strong>Item:</strong> <span id="summary-item">Select an item first</span></p>
                                <p><strong>Staff:</strong> <span id="summary-staff">Select a staff member first</span></p>
                                <p><strong>Quantity:</strong> <span id="summary-quantity">1</span></p>
                                <p><strong>Expected Return:</strong> <span id="summary-return-date">Not set</span></p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('inventory.assignments.index') }}" 
                               class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Check Out Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update item info when selection changes
        function updateItemInfo() {
            const select = document.getElementById('item_id');
            const selected = select.selectedOptions[0];
            const itemInfo = document.getElementById('item-info');
            const quantityInput = document.getElementById('quantity');
            
            if (selected && selected.value) {
                const available = selected.getAttribute('data-available');
                const name = selected.getAttribute('data-name');
                const code = selected.getAttribute('data-code');
                
                document.getElementById('selected-item-name').textContent = name;
                document.getElementById('selected-item-code').textContent = code;
                document.getElementById('selected-item-available').textContent = available;
                document.getElementById('summary-item').textContent = name + ' (' + code + ')';
                
                quantityInput.setAttribute('max', available);
                itemInfo.classList.remove('hidden');
            } else {
                itemInfo.classList.add('hidden');
                document.getElementById('summary-item').textContent = 'Select an item first';
                quantityInput.removeAttribute('max');
            }
        }

        // Update staff summary when selection changes
        document.getElementById('staff_id').addEventListener('change', function() {
            const selected = this.selectedOptions[0];
            if (selected && selected.value) {
                document.getElementById('summary-staff').textContent = selected.textContent;
            } else {
                document.getElementById('summary-staff').textContent = 'Select a staff member first';
            }
        });

        // Update quantity summary
        document.getElementById('quantity').addEventListener('input', function() {
            document.getElementById('summary-quantity').textContent = this.value;
        });

        // Update return date summary
        document.getElementById('expected_return_date').addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                document.getElementById('summary-return-date').textContent = date.toLocaleDateString();
            } else {
                document.getElementById('summary-return-date').textContent = 'Not set';
            }
        });

        // Initialize if item is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            updateItemInfo();
        });
    </script>
@endsection