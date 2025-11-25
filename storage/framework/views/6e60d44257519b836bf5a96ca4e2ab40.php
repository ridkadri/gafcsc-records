<?php $__env->startSection('header'); ?>
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e($item->name); ?>

        </h2>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('inventory.index')); ?>" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Inventory
            </a>
            <?php if(Auth::user()->canManageStaff()): ?>
                <?php if($item->available_quantity > 0): ?>
                    <a href="<?php echo e(route('inventory.assignments.create', ['item_id' => $item->id])); ?>" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Check Out
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('inventory.edit', $item)); ?>" 
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Item
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Item Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Main Info -->
                        <div class="lg:col-span-2">
                            <div class="flex items-start space-x-4">
                                <?php if($item->category): ?>
                                <div class="flex-shrink-0 h-16 w-16">
                                    <div class="h-16 w-16 rounded-lg flex items-center justify-center text-white font-bold text-lg"
                                         style="background-color: <?php echo e($item->category->color); ?>">
                                        <?php echo e($item->category->code); ?>

                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="flex-1">
                                    <h1 class="text-2xl font-bold text-gray-900"><?php echo e($item->name); ?></h1>
                                    <p class="text-lg text-gray-600 mb-2"><?php echo e($item->item_code); ?></p>
                                    <?php if($item->barcode): ?>
                                        <p class="text-sm font-mono text-gray-500 mb-2"><?php echo e($item->barcode); ?></p>
                                    <?php endif; ?>
                                    <?php if($item->description): ?>
                                        <p class="text-gray-700 mb-4"><?php echo e($item->description); ?></p>
                                    <?php endif; ?>
                                    
                                    <!-- Tags -->
                                    <div class="flex flex-wrap gap-2">
                                        <?php if($item->category): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white"
                                              style="background-color: <?php echo e($item->category->color); ?>">
                                            <?php echo e($item->category->name); ?>

                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            No Category
                                        </span>
                                        <?php endif; ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($item->condition_color); ?>">
                                            <?php echo e(ucfirst($item->condition)); ?>

                                        </span>
                                        <?php if($item->isLowStock()): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                Low Stock
                                            </span>
                                        <?php endif; ?>
                                        <?php if($item->needsInspection()): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                Inspection Due
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Stock Status</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Available:</span>
                                        <span class="font-semibold <?php echo e($item->isLowStock() ? 'text-red-600' : 'text-green-600'); ?>">
                                            <?php echo e($item->available_quantity); ?>

                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total:</span>
                                        <span class="font-semibold"><?php echo e($item->total_quantity); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Assigned:</span>
                                        <span class="font-semibold text-orange-600"><?php echo e($item->assigned_quantity); ?></span>
                                    </div>
                                    <?php if($item->maintenance_quantity > 0): ?>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Maintenance:</span>
                                            <span class="font-semibold text-yellow-600"><?php echo e($item->maintenance_quantity); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if(Auth::user()->canManageStaff()): ?>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <button onclick="toggleAdjustQuantity()" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            Adjust Quantity
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adjust Quantity Form (Hidden by default) -->
            <?php if(Auth::user()->canManageStaff()): ?>
                <div id="adjust-quantity-form" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Adjust Quantity</h3>
                        <form method="POST" action="<?php echo e(route('inventory.adjust-quantity', $item)); ?>" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Adjustment Type</label>
                                    <select name="adjustment_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="increase">Increase</option>
                                        <option value="decrease">Decrease</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        Adjust
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <input type="text" name="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Detailed Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Item Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Details</h3>
                        <dl class="space-y-3">
                            <?php if($item->manufacturer): ?>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Manufacturer:</dt>
                                    <dd class="font-medium"><?php echo e($item->manufacturer); ?></dd>
                                </div>
                            <?php endif; ?>

                            <?php if($item->warranty_expiry): ?>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Warranty Expiry:</dt>
                                    <dd class="font-medium <?php echo e($item->isWarrantyExpired() ? 'text-red-600' : ''); ?>">
                                        <?php echo e($item->warranty_expiry->format('M j, Y')); ?>

                                        <?php if($item->isWarrantyExpired()): ?>
                                            <span class="text-xs text-red-600">(Expired)</span>
                                        <?php endif; ?>
                                    </dd>
                                </div>
                            <?php endif; ?>
                            <?php if($item->last_inspection): ?>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Last Inspection:</dt>
                                    <dd class="font-medium"><?php echo e($item->last_inspection->format('M j, Y')); ?></dd>
                                </div>
                            <?php endif; ?>
                            <?php if($item->next_inspection): ?>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Next Inspection:</dt>
                                    <dd class="font-medium <?php echo e($item->needsInspection() ? 'text-red-600' : ''); ?>">
                                        <?php echo e($item->next_inspection->format('M j, Y')); ?>

                                        <?php if($item->needsInspection()): ?>
                                            <span class="text-xs text-red-600">(Due)</span>
                                        <?php endif; ?>
                                    </dd>
                                </div>
                            <?php endif; ?>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Added:</dt>
                                <dd class="font-medium"><?php echo e($item->created_at->format('M j, Y')); ?></dd>
                            </div>
                        </dl>

                        <?php if(Auth::user()->canManageStaff() && !$item->barcode): ?>
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <form method="POST" action="<?php echo e(route('inventory.generate-barcode', $item)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        Generate Barcode
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Specifications -->
            <?php if($item->specifications && count($item->specifications) > 0): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $item->specifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 capitalize"><?php echo e(str_replace('_', ' ', $key)); ?>:</dt>
                                    <dd class="font-medium"><?php echo e($value); ?></dd>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </dl>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Notes -->
            <?php if($item->notes): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                        <p class="text-gray-700"><?php echo e($item->notes); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Active Assignments -->
            <?php if($item->activeAssignments->count() > 0): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Currently Assigned</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expected Return</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <?php if(Auth::user()->canManageStaff()): ?>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $item->activeAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full <?php echo e($assignment->staff->isMilitary() ? 'bg-gradient-to-r from-green-600 to-green-800' : 'bg-gradient-to-r from-blue-500 to-purple-600'); ?> flex items-center justify-center">
                                                        <span class="text-white font-medium text-xs">
                                                            <?php echo e($assignment->staff->initials); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($assignment->staff->name); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo e($assignment->staff->service_number); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($assignment->quantity); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($assignment->assigned_date->format('M j, Y')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php if($assignment->expected_return_date): ?>
                                                <span class="<?php echo e($assignment->isOverdue() ? 'text-red-600' : ''); ?>">
                                                    <?php echo e($assignment->expected_return_date->format('M j, Y')); ?>

                                                    <?php if($assignment->isOverdue()): ?>
                                                        <span class="text-xs">(Overdue)</span>
                                                    <?php endif; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400">Not set</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($assignment->status_color); ?>">
                                                <?php echo e(ucfirst($assignment->status)); ?>

                                            </span>
                                        </td>
                                        <?php if(Auth::user()->canManageStaff()): ?>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="<?php echo e(route('inventory.assignments.show', $assignment)); ?>" 
                                                   class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                <a href="<?php echo e(route('inventory.assignments.return', $assignment)); ?>" 
                                                   class="text-green-600 hover:text-green-900">Return</a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Recent Transactions -->
            <?php if($item->transactions->count() > 0): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Processed By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $item->transactions->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($transaction->created_at->format('M j, Y g:i A')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($transaction->transaction_type_color); ?>">
                                                <?php echo e(ucwords(str_replace('_', ' ', $transaction->transaction_type))); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php if($transaction->staff): ?>
                                                <?php echo e($transaction->staff->name); ?>

                                            <?php else: ?>
                                                <span class="text-gray-400">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($transaction->quantity); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php if($transaction->processedBy): ?>
                                                <?php echo e($transaction->processedBy->name); ?>

                                            <?php else: ?>
                                                <span class="text-gray-400">System</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?php echo e($transaction->notes); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleAdjustQuantity() {
            const form = document.getElementById('adjust-quantity-form');
            form.classList.toggle('hidden');
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.inventory', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/gafcsc-records/resources/views/inventory/show.blade.php ENDPATH**/ ?>