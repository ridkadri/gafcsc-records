<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Edit Inventory Item')); ?>

            </h2>
            <a href="<?php echo e(route('inventory.show', $item)); ?>" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Back to Item
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="<?php echo e(route('inventory.update', $item)); ?>" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Basic Information -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Item Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Item Name *</label>
                                    <input type="text" name="name" id="name" value="<?php echo e(old('name', $item->name)); ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Item Code -->
                                <div>
                                    <label for="item_code" class="block text-sm font-medium text-gray-700">Item Code *</label>
                                    <input type="text" name="item_code" id="item_code" value="<?php echo e(old('item_code', $item->item_code)); ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['item_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                                    <select name="category_id" id="category_id" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Category</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $item->category_id) == $category->id ? 'selected' : ''); ?>>
                                                <?php echo e($category->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Barcode -->
                                <div>
                                    <label for="barcode" class="block text-sm font-medium text-gray-700">Barcode</label>
                                    <input type="text" name="barcode" id="barcode" value="<?php echo e(old('barcode', $item->barcode)); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('description', $item->description)); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Manufacturer -->
                                <div>
                                    <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                                    <input type="text" name="manufacturer" id="manufacturer" value="<?php echo e(old('manufacturer', $item->manufacturer)); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['manufacturer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Model -->
                                <div>
                                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                    <input type="text" name="model" id="model" value="<?php echo e(old('model', $item->model)); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Serial Number -->
                                <div>
                                    <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                                    <input type="text" name="serial_number" id="serial_number" value="<?php echo e(old('serial_number', $item->serial_number)); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['serial_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Current Stock Warning -->
                        <?php if($item->activeAssignments->count() > 0): ?>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Note:</strong> This item has <?php echo e($item->activeAssignments->count()); ?> active assignments. 
                                            Changes to quantities may affect assigned amounts.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Inventory Details -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <!-- Current Stock Info (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                                    <div class="mt-1 p-3 bg-gray-50 rounded-md border">
                                        <div class="text-sm">
                                            <div>Total: <?php echo e($item->total_quantity); ?></div>
                                            <div>Available: <?php echo e($item->available_quantity); ?></div>
                                            <div>Assigned: <?php echo e($item->assigned_quantity); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Minimum Stock Level -->
                                <div>
                                    <label for="minimum_stock_level" class="block text-sm font-medium text-gray-700">Min Stock Level *</label>
                                    <input type="number" name="minimum_stock_level" id="minimum_stock_level" 
                                           value="<?php echo e(old('minimum_stock_level', $item->minimum_stock_level)); ?>" min="0" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['minimum_stock_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Unit Cost -->
                                <div>
                                    <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost ($)</label>
                                    <input type="number" name="unit_cost" id="unit_cost" 
                                           value="<?php echo e(old('unit_cost', $item->unit_cost)); ?>" step="0.01" min="0"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['unit_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Condition -->
                                <div>
                                    <label for="condition" class="block text-sm font-medium text-gray-700">Condition *</label>
                                    <select name="condition" id="condition" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="good" <?php echo e(old('condition', $item->condition) == 'good' ? 'selected' : ''); ?>>Good</option>
                                        <option value="fair" <?php echo e(old('condition', $item->condition) == 'fair' ? 'selected' : ''); ?>>Fair</option>
                                        <option value="poor" <?php echo e(old('condition', $item->condition) == 'poor' ? 'selected' : ''); ?>>Poor</option>
                                        <option value="damaged" <?php echo e(old('condition', $item->condition) == 'damaged' ? 'selected' : ''); ?>>Damaged</option>
                                    </select>
                                    <?php $__errorArgs = ['condition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="mt-6">
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" name="location" id="location" value="<?php echo e(old('location', $item->location)); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       list="location-options">
                                <datalist id="location-options">
                                    <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($location); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </datalist>
                                <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                           value="<?php echo e(old('purchase_date', $item->purchase_date?->format('Y-m-d'))); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['purchase_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Warranty Expiry -->
                                <div>
                                    <label for="warranty_expiry" class="block text-sm font-medium text-gray-700">Warranty Expiry</label>
                                    <input type="date" name="warranty_expiry" id="warranty_expiry" 
                                           value="<?php echo e(old('warranty_expiry', $item->warranty_expiry?->format('Y-m-d'))); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['warranty_expiry'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Next Inspection -->
                                <div>
                                    <label for="next_inspection" class="block text-sm font-medium text-gray-700">Next Inspection</label>
                                    <input type="date" name="next_inspection" id="next_inspection" 
                                           value="<?php echo e(old('next_inspection', $item->next_inspection?->format('Y-m-d'))); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <?php $__errorArgs = ['next_inspection'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('notes', $item->notes)); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Specifications -->
                            <div>
                                <label for="specifications" class="block text-sm font-medium text-gray-700">Specifications (JSON format)</label>
                                <textarea name="specifications" id="specifications" rows="4"
                                          placeholder='{"weight": "2.5kg", "dimensions": "30x20x10cm", "color": "black"}'
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('specifications', $item->specifications ? json_encode($item->specifications, JSON_PRETTY_PRINT) : '')); ?></textarea>
                                <p class="mt-1 text-xs text-gray-500">Optional: Enter specifications in JSON format</p>
                                <?php $__errorArgs = ['specifications'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="<?php echo e(route('inventory.show', $item)); ?>" 
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /Users/ridwankadri/Desktop/code/gafcsc-management/resources/views/inventory/edit.blade.php ENDPATH**/ ?>