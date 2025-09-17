<?php $__env->startSection('header'); ?>
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Inventory Assignments')); ?>

        </h2>
        <?php if(Auth::user()->canManageStaff()): ?>
            <a href="<?php echo e(route('inventory.assignments.create')); ?>" 
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z"/>
                </svg>
                Check Out Item
            </a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Dashboard -->
            <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-3">
                <!-- Total Active -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active Assignments</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e(number_format($stats['total_active'])); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Overdue -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 <?php echo e($stats['total_overdue'] > 0 ? 'bg-red-500' : 'bg-gray-400'); ?> rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Overdue Returns</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e(number_format($stats['total_overdue'])); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <?php if($stats['total_overdue'] > 0): ?>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="<?php echo e(route('inventory.assignments.overdue')); ?>" class="font-medium text-red-600 hover:text-red-900">
                                    View overdue items →
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Total Returned -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Returned</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e(number_format($stats['total_returned'])); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Search and Filter Section -->
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="<?php echo e(route('inventory.assignments.index')); ?>" class="space-y-4">
                        <!-- Search Input -->
                        <div class="w-full">
                            <input type="text" 
                                   name="search" 
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="Search by staff name, service number, or item name..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <!-- Filter Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Status Filter -->
                            <div>
                                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                                    <option value="overdue" <?php echo e(request('status') === 'overdue' ? 'selected' : ''); ?>>Overdue</option>
                                    <option value="returned" <?php echo e(request('status') === 'returned' ? 'selected' : ''); ?>>Returned</option>
                                    <option value="lost" <?php echo e(request('status') === 'lost' ? 'selected' : ''); ?>>Lost</option>
                                </select>
                            </div>

                            <!-- Staff Filter -->
                            <div>
                                <select name="staff_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Staff</option>
                                    <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staffMember): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($staffMember->id); ?>" <?php echo e(request('staff_id') == $staffMember->id ? 'selected' : ''); ?>>
                                            <?php echo e($staffMember->name); ?> (<?php echo e($staffMember->service_number); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <button type="submit" 
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Search
                                </button>
                                
                                <?php if(request()->hasAny(['search', 'status', 'staff_id'])): ?>
                                    <a href="<?php echo e(route('inventory.assignments.index')); ?>" 
                                       class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Clear
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Assignments Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Assignment
                                </th>
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
                                    Dates
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 <?php echo e($assignment->isOverdue() ? 'bg-red-50' : ''); ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#<?php echo e($assignment->id); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo e($assignment->created_at->format('M j, Y')); ?></div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full <?php echo e($assignment->staff->isMilitary() ? 'bg-gradient-to-r from-green-600 to-green-800' : 'bg-gradient-to-r from-blue-500 to-purple-600'); ?> flex items-center justify-center">
                                                    <span class="text-white font-medium text-sm">
                                                        <?php echo e($assignment->staff->initials); ?>

                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($assignment->staff->name); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo e($assignment->staff->service_number); ?></div>
                                                <?php if($assignment->staff->department): ?>
                                                    <div class="text-xs text-gray-400"><?php echo e($assignment->staff->department); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white font-bold text-xs"
                                                     style="background-color: <?php echo e($assignment->item->category->color); ?>">
                                                    <?php echo e($assignment->item->category->code); ?>

                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($assignment->item->name); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo e($assignment->item->item_code); ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($assignment->quantity); ?>

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>
                                            <div class="font-medium">Assigned: <?php echo e($assignment->assigned_date->format('M j, Y')); ?></div>
                                            <?php if($assignment->expected_return_date): ?>
                                                <div class="<?php echo e($assignment->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500'); ?>">
                                                    Due: <?php echo e($assignment->expected_return_date->format('M j, Y')); ?>

                                                    <?php if($assignment->isOverdue()): ?>
                                                        <span class="text-xs">(<?php echo e($assignment->expected_return_date->diffForHumans()); ?>)</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($assignment->actual_return_date): ?>
                                                <div class="text-green-600">
                                                    Returned: <?php echo e($assignment->actual_return_date->format('M j, Y')); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($assignment->status_color); ?>">
                                            <?php echo e(ucfirst($assignment->status)); ?>

                                            <?php if($assignment->isOverdue()): ?>
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            <?php endif; ?>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="<?php echo e(route('inventory.assignments.show', $assignment)); ?>" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        
                                        <?php if(Auth::user()->canManageStaff() && $assignment->status === 'active'): ?>
                                            <a href="<?php echo e(route('inventory.assignments.return', $assignment)); ?>" 
                                               class="text-green-600 hover:text-green-900">Return</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No assignments found</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            <?php if(request()->hasAny(['search', 'status', 'staff_id'])): ?>
                                                Try adjusting your search criteria.
                                            <?php else: ?>
                                                Get started by checking out an inventory item to a staff member.
                                            <?php endif; ?>
                                        </p>
                                        <?php if(Auth::user()->canManageStaff() && !request()->hasAny(['search', 'status', 'staff_id'])): ?>
                                            <div class="mt-6">
                                                <a href="<?php echo e(route('inventory.assignments.create')); ?>" 
                                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                    Check Out Item
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($assignments->hasPages()): ?>
                    <div class="px-6 py-4 border-t border-gray-200">
                        <?php echo e($assignments->links()); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <?php if(Auth::user()->canManageStaff()): ?>
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="<?php echo e(route('inventory.assignments.create')); ?>" 
                       class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-green-500 rounded-lg shadow hover:shadow-md transition-shadow">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-green-50 text-green-700 ring-4 ring-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                New Assignment
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Check out an inventory item to a staff member
                            </p>
                        </div>
                    </a>

                    <a href="<?php echo e(route('inventory.assignments.overdue')); ?>" 
                       class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-500 rounded-lg shadow hover:shadow-md transition-shadow">
                        <div>
                            <span class="rounded-lg inline-flex p-3 <?php echo e($stats['total_overdue'] > 0 ? 'bg-red-50 text-red-700' : 'bg-gray-50 text-gray-700'); ?> ring-4 ring-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                Overdue Returns
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                <?php echo e($stats['total_overdue']); ?> assignments are overdue
                            </p>
                        </div>
                    </a>

                    <a href="<?php echo e(route('inventory.index')); ?>" 
                       class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow hover:shadow-md transition-shadow">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700 ring-4 ring-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                View Inventory
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Browse and manage inventory items
                            </p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.inventory', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ridwankadri/Desktop/code/gafcsc-management/resources/views/inventory/assignments/index.blade.php ENDPATH**/ ?>