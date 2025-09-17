@extends('layouts.inventory')
    @section('header')
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Inventory Item') }}
            </h2>
            <a href="{{ route('inventory.show', $item) }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Back to Item
            </a>
        </div>
    @endsection
    @section('content')

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('inventory.update', $item) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Item Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Item Name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Item Code -->
                                <div>
                                    <label for="item_code" class="block text-sm font-medium text-gray-700">Item Code *</label>
                                    <input type="text" name="item_code" id="item_code" value="{{ old('item_code', $item->item_code) }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('item_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                                    <select name="category_id" id="category_id" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Barcode -->
                                <div>
                                    <label for="barcode" class="block text-sm font-medium text-gray-700">Barcode</label>
                                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $item->barcode) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('barcode')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Manufacturer -->
                                <div>
                                    <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                                    <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $item->manufacturer) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('manufacturer')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Model -->
                                <div>
                                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                    <input type="text" name="model" id="model" value="{{ old('model', $item->model) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('model')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Serial Number -->
                                <div>
                                    <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $item->serial_number) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('serial_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current Stock Warning -->
                        @if($item->activeAssignments->count() > 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Note:</strong> This item has {{ $item->activeAssignments->count() }} active assignments. 
                                            Changes to quantities may affect assigned amounts.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Inventory Details -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <!-- Current Stock Info (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                                    <div class="mt-1 p-3 bg-gray-50 rounded-md border">
                                        <div class="text-sm">
                                            <div>Total: {{ $item->total_quantity }}</div>
                                            <div>Available: {{ $item->available_quantity }}</div>
                                            <div>Assigned: {{ $item->assigned_quantity }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Minimum Stock Level -->
                                <div>
                                    <label for="minimum_stock_level" class="block text-sm font-medium text-gray-700">Min Stock Level *</label>
                                    <input type="number" name="minimum_stock_level" id="minimum_stock_level" 
                                           value="{{ old('minimum_stock_level', $item->minimum_stock_level) }}" min="0" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('minimum_stock_level')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Unit Cost -->
                                <div>
                                    <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost ($)</label>
                                    <input type="number" name="unit_cost" id="unit_cost" 
                                           value="{{ old('unit_cost', $item->unit_cost) }}" step="0.01" min="0"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('unit_cost')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Condition -->
                                <div>
                                    <label for="condition" class="block text-sm font-medium text-gray-700">Condition *</label>
                                    <select name="condition" id="condition" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="good" {{ old('condition', $item->condition) == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="fair" {{ old('condition', $item->condition) == 'fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="poor" {{ old('condition', $item->condition) == 'poor' ? 'selected' : '' }}>Poor</option>
                                        <option value="damaged" {{ old('condition', $item->condition) == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    </select>
                                    @error('condition')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="mt-6">
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location', $item->location) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       list="location-options">
                                <datalist id="location-options">
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}">
                                    @endforeach
                                </datalist>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Important Dates</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Purchase Date -->
                                <div>
                                    <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                                    <input type="date" name="purchase_date" id="purchase_date" 
                                           value="{{ old('purchase_date', $item->purchase_date?->format('Y-m-d')) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('purchase_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Warranty Expiry -->
                                <div>
                                    <label for="warranty_expiry" class="block text-sm font-medium text-gray-700">Warranty Expiry</label>
                                    <input type="date" name="warranty_expiry" id="warranty_expiry" 
                                           value="{{ old('warranty_expiry', $item->warranty_expiry?->format('Y-m-d')) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('warranty_expiry')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Next Inspection -->
                                <div>
                                    <label for="next_inspection" class="block text-sm font-medium text-gray-700">Next Inspection</label>
                                    <input type="date" name="next_inspection" id="next_inspection" 
                                           value="{{ old('next_inspection', $item->next_inspection?->format('Y-m-d')) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('next_inspection')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                            
                            <!-- Notes -->
                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $item->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Specifications -->
                            <div>
                                <label for="specifications" class="block text-sm font-medium text-gray-700">Specifications (JSON format)</label>
                                <textarea name="specifications" id="specifications" rows="4"
                                          placeholder='{"weight": "2.5kg", "dimensions": "30x20x10cm", "color": "black"}'
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('specifications', $item->specifications ? json_encode($item->specifications, JSON_PRETTY_PRINT) : '') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Optional: Enter specifications in JSON format</p>
                                @error('specifications')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('inventory.show', $item) }}" 
                               class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection