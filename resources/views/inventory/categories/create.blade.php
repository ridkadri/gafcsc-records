@extends('layouts.inventory')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Add New Category</h1>
        <p class="text-gray-600 text-sm">Create a new inventory category for organizing items</p>
    </div>
    <a href="{{ route('inventory.categories.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
        </svg>
        Back to Categories
    </a>
</div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('inventory.categories.store') }}" class="space-y-6">
                    @csrf

                    <!-- Basic Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Category Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Category Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="e.g., Weapons & Firearms">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Category Code *</label>
                                <input type="text" name="code" id="code" value="{{ old('code') }}" required maxlength="10"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="e.g., WPNS" style="text-transform: uppercase;">
                                <p class="mt-1 text-xs text-gray-500">Short code (max 10 characters) used for item identification</p>
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Brief description of what items belong in this category...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Visual Settings -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Visual Settings</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Color -->
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">Category Color *</label>
                                <div class="mt-1 flex items-center space-x-4">
                                    <input type="color" name="color" id="color" value="{{ old('color', '#6B7280') }}" required
                                           class="h-10 w-16 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <input type="text" name="color_hex" id="color_hex" value="{{ old('color', '#6B7280') }}"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               placeholder="#6B7280" readonly>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">This color will be used for category badges and identification</p>
                                @error('color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Color Preview -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                                <div class="mt-1 p-4 border border-gray-200 rounded-md bg-gray-50">
                                    <div id="category-preview" class="inline-flex items-center">
                                        <div id="preview-badge" class="h-12 w-12 rounded-lg flex items-center justify-center text-white font-bold text-sm mr-3"
                                             style="background-color: #6B7280;">
                                            <span id="preview-code">CODE</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <span id="preview-name">Category Name</span>
                                            </div>
                                            <span id="preview-tag" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                                                  style="background-color: #6B7280;">
                                                Category Name
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Predefined Colors -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Colors</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="setColor('#DC2626')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #DC2626;" title="Red"></button>
                                <button type="button" onclick="setColor('#EA580C')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #EA580C;" title="Orange"></button>
                                <button type="button" onclick="setColor('#F59E0B')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #F59E0B;" title="Yellow"></button>
                                <button type="button" onclick="setColor('#059669')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #059669;" title="Green"></button>
                                <button type="button" onclick="setColor('#0891B2')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #0891B2;" title="Cyan"></button>
                                <button type="button" onclick="setColor('#2563EB')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #2563EB;" title="Blue"></button>
                                <button type="button" onclick="setColor('#7C3AED')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #7C3AED;" title="Purple"></button>
                                <button type="button" onclick="setColor('#C2410C')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #C2410C;" title="Brown"></button>
                                <button type="button" onclick="setColor('#6B7280')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #6B7280;" title="Gray"></button>
                                <button type="button" onclick="setColor('#1F2937')" class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-400" style="background-color: #1F2937;" title="Dark Gray"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Security & Settings</h3>
                        
                        <div class="space-y-4">
                            <!-- Requires Approval -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="requires_approval" id="requires_approval" value="1" 
                                           {{ old('requires_approval') ? 'checked' : '' }}
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="requires_approval" class="font-medium text-gray-700">Requires Approval</label>
                                    <p class="text-gray-500">Check-out of items in this category requires administrative approval</p>
                                </div>
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-medium text-gray-700">Active Category</label>
                                    <p class="text-gray-500">Only active categories can be used for new items</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Examples -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">Category Examples</h4>
                                <div class="text-sm text-blue-700 mt-1">
                                    <p class="mb-2">Common military university categories:</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li><strong>WPNS</strong> - Weapons & Firearms (requires approval)</li>
                                        <li><strong>UNIF</strong> - Uniforms & Clothing</li>
                                        <li><strong>ELEC</strong> - Electronics & Communication</li>
                                        <li><strong>PROT</strong> - Protective Equipment (requires approval)</li>
                                        <li><strong>MED</strong> - Medical Supplies</li>
                                        <li><strong>EDU</strong> - Books & Educational Materials</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('inventory.categories.index') }}" 
                           class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Update color input and preview
    function setColor(color) {
        document.getElementById('color').value = color;
        document.getElementById('color_hex').value = color;
        updatePreview();
    }

    // Sync color picker with hex input
    document.getElementById('color').addEventListener('change', function() {
        document.getElementById('color_hex').value = this.value;
        updatePreview();
    });

    // Update preview when inputs change
    function updatePreview() {
        const name = document.getElementById('name').value || 'Category Name';
        const code = document.getElementById('code').value || 'CODE';
        const color = document.getElementById('color').value;

        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-code').textContent = code.toUpperCase().substring(0, 4);
        document.getElementById('preview-badge').style.backgroundColor = color;
        document.getElementById('preview-tag').style.backgroundColor = color;
        document.getElementById('preview-tag').textContent = name;
    }

    // Auto-uppercase code input
    document.getElementById('code').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
        updatePreview();
    });

    // Update preview when name changes
    document.getElementById('name').addEventListener('input', updatePreview);

    // Initialize preview
    updatePreview();
</script>
@endsection