<?php $__env->startSection('header'); ?>
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Process Return - Assignment #<?php echo e($assignment->id); ?>

        </h2>
        <a href="<?php echo e(route('inventory.assignments.show', $assignment)); ?>" 
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Back to Assignment
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>  

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Assignment Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assignment Summary</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Staff Info -->
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full <?php echo e($assignment->staff->isMilitary() ? 'bg-gradient-to-r from-green-600 to-green-800' : 'bg-gradient-to-r from-blue-500 to-purple-600'); ?> flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">
                                        <?php echo e($assignment->staff->initials); ?>

                                    </span>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900"><?php echo e($assignment->staff->name); ?></h4>
                                <p class="text-sm text-gray-600"><?php echo e($assignment->staff->service_number); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($assignment->staff->department); ?></p>
                            </div>
                        </div>

                        <!-- Item Info -->
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-lg flex items-center justify-center text-white font-bold text-sm"
                                     style="background-color: <?php echo e($assignment->item->category->color); ?>">
                                    <?php echo e($assignment->item->category->code); ?>

                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900"><?php echo e($assignment->item->name); ?></h4>
                                <p class="text-sm text-gray-600"><?php echo e($assignment->item->item_code); ?></p>
                                <p class="text-xs text-gray-500">Quantity: <?php echo e($assignment->quantity); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Details -->
                    <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-900">Assigned:</span>
                            <span class="text-gray-600"><?php echo e($assignment->assigned_date->format('M j, Y')); ?></span>
                        </div>
                        <?php if($assignment->expected_return_date): ?>
                            <div>
                                <span class="font-medium text-gray-900">Expected Return:</span>
                                <span class="text-gray-600 <?php echo e($assignment->isOverdue() ? 'text-red-600 font-semibold' : ''); ?>">
                                    <?php echo e($assignment->expected_return_date->format('M j, Y')); ?>

                                    <?php if($assignment->isOverdue()): ?>
                                        (Overdue)
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <span class="font-medium text-gray-900">Assigned By:</span>
                            <span class="text-gray-600"><?php echo e($assignment->assignedBy->name); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Process Return</h3>
                    
                    <form method="POST" action="<?php echo e(route('inventory.assignments.process-return', $assignment)); ?>" class="space-y-6">
                        <?php echo csrf_field(); ?>

                        <!-- Quantity Returned -->
                        <div class="border-b border-gray-200 pb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Return Details</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Quantity to Return -->
                                <div>
                                    <label for="quantity_returned" class="block text-sm font-medium text-gray-700">Quantity Being Returned *</label>
                                    <input type="number" name="quantity_returned" id="quantity_returned" 
                                           value="<?php echo e(old('quantity_returned', $assignment->quantity)); ?>" 
                                           min="1" max="<?php echo e($assignment->quantity); ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Maximum: <?php echo e($assignment->quantity); ?> (originally assigned)
                                    </p>
                                    <?php $__errorArgs = ['quantity_returned'];
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

                                <!-- Condition on Return -->
                                <div>
                                    <label for="condition_on_return" class="block text-sm font-medium text-gray-700">Condition on Return *</label>
                                    <select name="condition_on_return" id="condition_on_return" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select condition</option>
                                        <option value="good" <?php echo e(old('condition_on_return') == 'good' ? 'selected' : ''); ?>>Good</option>
                                        <option value="fair" <?php echo e(old('condition_on_return') == 'fair' ? 'selected' : ''); ?>>Fair</option>
                                        <option value="poor" <?php echo e(old('condition_on_return') == 'poor' ? 'selected' : ''); ?>>Poor</option>
                                        <option value="damaged" <?php echo e(old('condition_on_return') == 'damaged' ? 'selected' : ''); ?>>Damaged</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Original condition: <strong><?php echo e(ucfirst($assignment->item->condition)); ?></strong>
                                    </p>
                                    <?php $__errorArgs = ['condition_on_return'];
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

                            <!-- Condition Warning -->
                            <div id="condition-warning" class="hidden mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Note:</strong> Items returned in "poor" or "damaged" condition will be moved to maintenance status and unavailable for assignment until repaired.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Return Notes -->
                        <div class="border-b border-gray-200 pb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Return Notes</h4>
                            
                            <div>
                                <label for="return_notes" class="block text-sm font-medium text-gray-700">Notes about Return</label>
                                <textarea name="return_notes" id="return_notes" rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Any issues, damages, or observations about the returned item..."><?php echo e(old('return_notes')); ?></textarea>
                                <p class="mt-1 text-xs text-gray-500">
                                    Optional: Add any notes about the condition or circumstances of the return
                                </p>
                                <?php $__errorArgs = ['return_notes'];
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

                        <!-- Partial Return Information -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800">Partial Returns</h4>
                                    <p class="text-sm text-blue-700 mt-1">
                                        If you're returning fewer items than originally assigned, a new assignment will be created for the remaining items. 
                                        The staff member will still be responsible for the unreturned items with the same return date.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Return Summary -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Return Summary</h4>
                            <div id="return-summary" class="text-sm text-gray-600 space-y-1">
                                <p><strong>Staff:</strong> <?php echo e($assignment->staff->name); ?></p>
                                <p><strong>Item:</strong> <?php echo e($assignment->item->name); ?> (<?php echo e($assignment->item->item_code); ?>)</p>
                                <p><strong>Quantity Returning:</strong> <span id="summary-quantity"><?php echo e($assignment->quantity); ?></span> of <?php echo e($assignment->quantity); ?></p>
                                <p><strong>Condition:</strong> <span id="summary-condition">Not selected</span></p>
                                <p><strong>Return Date:</strong> <?php echo e(now()->format('M j, Y')); ?></p>
                                <p><strong>Processed By:</strong> <?php echo e(Auth::user()->name); ?></p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="<?php echo e(route('inventory.assignments.show', $assignment)); ?>" 
                               class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Process Return
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update summary when form changes
        function updateSummary() {
            const quantity = document.getElementById('quantity_returned').value;
            const condition = document.getElementById('condition_on_return').value;
            
            document.getElementById('summary-quantity').textContent = quantity || '<?php echo e($assignment->quantity); ?>';
            document.getElementById('summary-condition').textContent = condition ? condition.charAt(0).toUpperCase() + condition.slice(1) : 'Not selected';
            
            // Show warning for poor/damaged condition
            const warning = document.getElementById('condition-warning');
            if (condition === 'poor' || condition === 'damaged') {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }

        // Add event listeners
        document.getElementById('quantity_returned').addEventListener('input', updateSummary);
        document.getElementById('condition_on_return').addEventListener('change', updateSummary);
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.inventory', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/gafcsc-records/resources/views/inventory/assignments/return.blade.php ENDPATH**/ ?>