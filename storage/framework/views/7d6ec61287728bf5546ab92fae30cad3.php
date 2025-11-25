<?php $__env->startSection('header'); ?>
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Inventory Categories')); ?>

        </h2>
        <?php if(Auth::user()->canManageStaff()): ?>
            <a href="<?php echo e(route('inventory.categories.create')); ?>" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Add Category
            </a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Categories Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="relative group">
                                <div class="bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-gray-300 transition-colors duration-200 <?php echo e(!$category->is_active ? 'opacity-60' : ''); ?>">
                                    <!-- Category Header -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-lg flex items-center justify-center text-white font-bold text-lg"
                                                 style="background-color: <?php echo e($category->color); ?>">
                                                <?php echo e($category->code); ?>

                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($category->name); ?></h3>
                                                <?php if(!$category->is_active): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Inactive
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if($category->requires_approval): ?>
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Requires Approval
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Description -->
                                    <?php if($category->description): ?>
                                        <p class="text-gray-600 text-sm mb-4"><?php echo e($category->description); ?></p>
                                    <?php endif; ?>

                                    <!-- Statistics -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-gray-900"><?php echo e($category->items_count); ?></div>
                                            <div class="text-xs text-gray-500">Items</div>
                                        </div>
                                        <?php if($category->items_count > 0): ?>
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-green-600">$<?php echo e(number_format($category->total_value ?? 0, 0)); ?></div>
                                                <div class="text-xs text-gray-500">Total Value</div>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-gray-400">—</div>
                                                <div class="text-xs text-gray-500">Total Value</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                        <a href="<?php echo e(route('inventory.categories.show', $category)); ?>" 
                                           class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            View Details
                                        </a>
                                        
                                        <?php if(Auth::user()->canManageStaff()): ?>
                                            <div class="flex space-x-3">
                                                <a href="<?php echo e(route('inventory.categories.edit', $category)); ?>" 
                                                   class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                                    Edit
                                                </a>
                                                
                                                <?php if($category->items_count == 0): ?>
                                                    <form method="POST" action="<?php echo e(route('inventory.categories.destroy', $category)); ?>" 
                                                          class="inline-block" 
                                                          onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                            Delete
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first inventory category.</p>
                                    <?php if(Auth::user()->canManageStaff()): ?>
                                        <div class="mt-6">
                                            <a href="<?php echo e(route('inventory.categories.create')); ?>" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Add Category
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Stats Summary -->
                <?php if($categories->count() > 0): ?>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-gray-900"><?php echo e($categories->count()); ?></div>
                                <div class="text-sm text-gray-500">Total Categories</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-600"><?php echo e($categories->where('is_active', true)->count()); ?></div>
                                <div class="text-sm text-gray-500">Active Categories</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-blue-600"><?php echo e($categories->sum('items_count')); ?></div>
                                <div class="text-sm text-gray-500">Total Items</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-600"><?php echo e($categories->where('requires_approval', true)->count()); ?></div>
                                <div class="text-sm text-gray-500">Require Approval</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <?php if(Auth::user()->canManageStaff()): ?>
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="<?php echo e(route('inventory.categories.create')); ?>" 
                       class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow hover:shadow-md transition-shadow">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700 ring-4 ring-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                Add New Category
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Create a new inventory category for organizing items
                            </p>
                        </div>
                    </a>

                    <a href="<?php echo e(route('inventory.index')); ?>" 
                       class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-green-500 rounded-lg shadow hover:shadow-md transition-shadow">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-green-50 text-green-700 ring-4 ring-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                Browse Inventory
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                View and manage all inventory items
                            </p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.inventory', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/gafcsc-records/resources/views/inventory/categories/index.blade.php ENDPATH**/ ?>