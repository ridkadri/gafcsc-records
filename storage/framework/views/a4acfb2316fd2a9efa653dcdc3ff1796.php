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
                <?php echo e(__('Staff Details')); ?>

            </h2>
            <?php if(Auth::user()->canManageStaff()): ?>
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('staff.edit', $staff)); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header Section -->
                <div class="px-6 py-6 <?php echo e($staff->isMilitary() ? 'bg-gradient-to-r from-green-600 to-green-800' : 'bg-gradient-to-r from-blue-500 to-purple-600'); ?>">
                    <div class="flex items-center space-x-6">
                        <!-- Profile Picture -->
                        <div class="h-24 w-24 rounded-full bg-white overflow-hidden ring-4 ring-white ring-opacity-30">
                            <?php if($staff->hasProfilePicture()): ?>
                                <img src="<?php echo e($staff->getProfilePictureUrl()); ?>" 
                                     alt="<?php echo e($staff->name); ?>" 
                                     class="h-full w-full object-cover">
                            <?php else: ?>
                                <img src="<?php echo e($staff->getDefaultAvatar()); ?>" 
                                     alt="<?php echo e($staff->name); ?>" 
                                     class="h-full w-full object-cover">
                            <?php endif; ?>
                        </div>
    
                        <div class="text-white">
                            <div class="flex items-center space-x-2 mb-1">
                                <h1 class="text-3xl font-bold"><?php echo e($staff->name); ?></h1>
                                <?php if($staff->isMilitary()): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                        🎖️ Military
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                        👔 Civilian
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-white text-opacity-90 text-lg">SVC No: <?php echo e($staff->service_number); ?></p>
                            <?php if($staff->isMilitary()): ?>
                                <?php if($staff->rank): ?>
                                    <p class="text-white text-opacity-80 text-sm mt-1"><?php echo e($staff->rank); ?></p>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if($staff->present_grade): ?>
                                    <p class="text-white text-opacity-80 text-sm mt-1"><?php echo e($staff->present_grade); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="p-6">
                    <?php if($staff->isMilitary()): ?>
                        <!-- MILITARY DETAILS -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Military Personnel Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Service Number</span>
                                            <span class="text-sm text-gray-900 font-mono"><?php echo e($staff->service_number); ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Rank</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->rank): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <?php echo e($staff->rank); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Appointment</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->appointment): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <?php echo e($staff->appointment); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Sex</span>
                                            <span class="text-sm text-gray-900"><?php echo e($staff->sex ?: 'Not specified'); ?></span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Trade</span>
                                            <span class="text-sm text-gray-900"><?php echo e($staff->trade ?: 'Not specified'); ?></span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Arm of Service</span>
                                            <span class="text-sm text-gray-900"><?php echo e($staff->arm_of_service ?: 'Not specified'); ?></span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Status</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->deployment): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <?php echo e($staff->deployment); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Enrollment</span>
                                            <span class="text-sm text-gray-900">
                                                <?php echo e($staff->date_of_enrollment ? $staff->date_of_enrollment->format('F j, Y') : 'Not specified'); ?>

                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Birth</span>
                                            <span class="text-sm text-gray-900">
                                                <?php echo e($staff->date_of_birth ? $staff->date_of_birth->format('F j, Y') : 'Not specified'); ?>

                                                <?php if($staff->date_of_birth): ?>
                                                    <span class="text-xs text-gray-500">(<?php echo e($staff->age); ?> years old)</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Last Promotion</span>
                                            <span class="text-sm text-gray-900">
                                                <?php echo e($staff->last_promotion_date ? $staff->last_promotion_date->format('F j, Y') : 'Not specified'); ?>

                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Department/Unit</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->department): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        📍 <?php echo e($staff->department); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <?php if($staff->date_of_enrollment): ?>
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Years of Service</span>
                                            <span class="text-sm text-gray-900 font-semibold text-green-600">
                                                <?php echo e($staff->years_of_service); ?> years
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- CIVILIAN DETAILS -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Civilian Personnel Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Present Grade</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->present_grade): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <?php echo e($staff->present_grade); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Staff Category</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->staff_category): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($staff->staff_category === 'Senior' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800'); ?>">
                                                        <?php echo e($staff->staff_category); ?> Staff
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Appointment</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->appointment): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <?php echo e($staff->appointment); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Department</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->department): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        📍 <?php echo e($staff->department); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Employment</span>
                                            <span class="text-sm text-gray-900">
                                                <?php echo e($staff->date_of_employment ? $staff->date_of_employment->format('F j, Y') : 'Not specified'); ?>

                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Birth</span>
                                            <span class="text-sm text-gray-900">
                                                <?php echo e($staff->date_of_birth ? $staff->date_of_birth->format('F j, Y') : 'Not specified'); ?>

                                                <?php if($staff->date_of_birth): ?>
                                                    <span class="text-xs text-gray-500">(<?php echo e($staff->age); ?> years old)</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Last Promotion</span>
                                            <span class="text-sm text-gray-900">
                                                <?php echo e($staff->last_promotion_date ? $staff->last_promotion_date->format('F j, Y') : 'Not specified'); ?>

                                            </span>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Date of Posting</span>
                                            <span class="text-sm text-gray-900">
                                                <?php echo e($staff->date_of_posting ? $staff->date_of_posting->format('F j, Y') : 'Not specified'); ?>

                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Location</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->location): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        📍 <?php echo e($staff->location); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Status</span>
                                            <span class="text-sm text-gray-900">
                                                <?php if($staff->deployment): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <?php echo e($staff->deployment); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Not specified</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <?php if($staff->date_of_employment): ?>
                                        <div class="flex justify-between py-2 border-b border-gray-200">
                                            <span class="text-sm font-medium text-gray-500">Years of Service</span>
                                            <span class="text-sm text-gray-900 font-semibold text-blue-600">
                                                <?php echo e($staff->years_of_service); ?> years
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- ADD USER ACCOUNT INFO HERE -->
                    <?php if($staff->user): ?>
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-medium text-blue-800">User Account</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            <strong>Username:</strong> <?php echo e($staff->user->username); ?><br>
                            <strong>Default Password:</strong> gafcsc@123<br>
                            <strong>Status:</strong> 
                            <?php if($staff->user->needsPasswordChange()): ?>
                                <span class="text-orange-600">Needs password change</span>
                            <?php else: ?>
                                <span class="text-green-600">Password set</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Assigned Inventory Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Assigned Inventory</h3>
                            <?php if($staff->activeInventoryAssignments->count() > 0): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <?php echo e($staff->activeInventoryAssignments->count()); ?> <?php echo e(Str::plural('Item', $staff->activeInventoryAssignments->count())); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if($staff->activeInventoryAssignments->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $staff->activeInventoryAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <!-- Item Icon -->
                                                    <div class="flex-shrink-0">
                                                        <?php if($assignment->item && $assignment->item->category): ?>
                                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                                                                 style="background-color: <?php echo e($assignment->item->category->color); ?>">
                                                                <?php echo e($assignment->item->category->code); ?>

                                                            </div>
                                                        <?php else: ?>
                                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center bg-gray-300 text-gray-600 text-sm font-bold">
                                                                ?
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Item Details -->
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2">
                                                            <a href="<?php echo e(route('inventory.show', $assignment->item)); ?>" 
                                                               class="text-base font-semibold text-gray-900 hover:text-blue-600 transition">
                                                                <?php echo e($assignment->item->name); ?>

                                                            </a>
                                                            <?php if($assignment->item->category): ?>
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-white"
                                                                      style="background-color: <?php echo e($assignment->item->category->color); ?>">
                                                                    <?php echo e($assignment->item->category->name); ?>

                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            <span class="font-mono"><?php echo e($assignment->item->item_code); ?></span>
                                                            <?php if($assignment->item->serial_number): ?>
                                                                • SN: <span class="font-mono"><?php echo e($assignment->item->serial_number); ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Assignment Info -->
                                                <div class="ml-13 grid grid-cols-2 md:grid-cols-4 gap-4 mt-3 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Quantity:</span>
                                                        <span class="font-medium text-gray-900 ml-1"><?php echo e($assignment->quantity); ?></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Checked Out:</span>
                                                        <span class="font-medium text-gray-900 ml-1"><?php echo e($assignment->assigned_date->format('M j, Y')); ?></span>
                                                    </div>
                                                    <?php if($assignment->expected_return_date): ?>
                                                        <div>
                                                            <span class="text-gray-500">Due Back:</span>
                                                            <span class="font-medium <?php echo e($assignment->isOverdue() ? 'text-red-600' : 'text-gray-900'); ?> ml-1">
                                                                <?php echo e($assignment->expected_return_date->format('M j, Y')); ?>

                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <span class="text-gray-500">Condition:</span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($assignment->item->condition_color); ?> ml-1">
                                                            <?php echo e(ucfirst($assignment->item->condition)); ?>

                                                        </span>
                                                    </div>
                                                </div>

                                                <?php if($assignment->notes): ?>
                                                    <div class="ml-13 mt-2">
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">Notes:</span> <?php echo e($assignment->notes); ?>

                                                        </p>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if($assignment->isOverdue()): ?>
                                                    <div class="ml-13 mt-2">
                                                        <div class="flex items-center text-red-600 text-sm">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <span class="font-medium">OVERDUE by <?php echo e($assignment->expected_return_date->diffInDays(now())); ?> days</span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Status Badge -->
                                            <div class="ml-4">
                                                <?php if($assignment->isOverdue()): ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Overdue
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <!-- Summary Stats -->
                            <?php if($staff->activeInventoryAssignments->count() > 1): ?>
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-blue-700 font-medium">Total Items:</span>
                                            <span class="text-blue-900 font-bold ml-2"><?php echo e($staff->activeInventoryAssignments->count()); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-blue-700 font-medium">Total Quantity:</span>
                                            <span class="text-blue-900 font-bold ml-2"><?php echo e($staff->activeInventoryAssignments->sum('quantity')); ?></span>
                                        </div>
                                        <?php if($staff->overdueInventoryAssignments->count() > 0): ?>
                                            <div>
                                                <span class="text-red-700 font-medium">Overdue:</span>
                                                <span class="text-red-900 font-bold ml-2"><?php echo e($staff->overdueInventoryAssignments->count()); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No Inventory Assigned</h3>
                                <p class="mt-1 text-sm text-gray-500">This staff member has no active inventory assignments.</p>
                                <?php if(Auth::user()->canManageStaff()): ?>
                                    <div class="mt-4">
                                        <a href="<?php echo e(route('inventory.index')); ?>" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Assign Inventory
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- System Information -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Record ID</span>
                                    <span class="text-sm text-gray-900 font-mono">#<?php echo e($staff->id); ?></span>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Date Added</span>
                                    <span class="text-sm text-gray-900"><?php echo e($staff->created_at->format('F j, Y')); ?></span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Last Updated</span>
                                    <span class="text-sm text-gray-900"><?php echo e($staff->updated_at->format('F j, Y \a\t g:i A')); ?></span>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Days in System</span>
                                    <span class="text-sm text-gray-900"><?php echo e($staff->created_at->diffInDays(now())); ?> days</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap justify-between items-center gap-4">
                            <a href="<?php echo e(route('staff.index')); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                </svg>
                                Back to Staff List
                            </a>

                            <?php if(Auth::user()->canManageStaff()): ?>
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('staff.edit', $staff)); ?>" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Edit Details
                                    </a>

                                    <form method="POST" action="<?php echo e(route('staff.destroy', $staff)); ?>" 
                                          class="inline-block" 
                                          onsubmit="return confirm('Are you sure you want to delete <?php echo e($staff->name); ?>? This action cannot be undone.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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
<?php endif; ?><?php /**PATH /var/www/gafcsc-records/resources/views/staff/show.blade.php ENDPATH**/ ?>