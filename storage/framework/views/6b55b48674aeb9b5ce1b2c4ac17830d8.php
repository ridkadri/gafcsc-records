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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('My Profile')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <?php if(session('success')): ?>
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <img id="profile-picture-display" 
                                     src="<?php echo e($staff->getProfilePictureUrl()); ?>?v=<?php echo e($staff->updated_at->timestamp); ?>" 
                                     alt="<?php echo e($staff->name); ?>" 
                                     class="w-32 h-32 rounded-full object-cover border-4 border-gray-200"
                                     onerror="this.src='<?php echo e($staff->getDefaultAvatar()); ?>'">
                                
                                
                                <button onclick="openUploadModal()"
                                        class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2 shadow-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo e($staff->name); ?></h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($staff->isMilitary() ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                                    <?php echo e(ucfirst($staff->type)); ?>

                                </span>
                                <span class="ml-2">Service #: <?php echo e($staff->service_number); ?></span>
                            </p>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                
                                <?php if($staff->isMilitary()): ?>
                                    <div>
                                        <p class="text-sm text-gray-500">Rank</p>
                                        <p class="font-semibold"><?php echo e($staff->rank ?: 'Not specified'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Sex</p>
                                        <p class="font-semibold"><?php echo e($staff->sex ?: 'Not specified'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Trade</p>
                                        <p class="font-semibold"><?php echo e($staff->trade ?: 'Not specified'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Arm of Service</p>
                                        <p class="font-semibold"><?php echo e($staff->arm_of_service ?: 'Not specified'); ?></p>
                                    </div>
                                    <?php if($staff->deployment): ?>
                                        <div>
                                            <p class="text-sm text-gray-500">Deployment</p>
                                            <p class="font-semibold"><?php echo e($staff->deployment); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="text-sm text-gray-500">Date of Enrollment</p>
                                        <p class="font-semibold"><?php echo e($staff->date_of_enrollment ? $staff->date_of_enrollment->format('d M Y') : 'Not specified'); ?></p>
                                    </div>
                                    <?php if($staff->date_of_enrollment): ?>
                                        <div>
                                            <p class="text-sm text-gray-500">Years of Service</p>
                                            <p class="font-semibold text-blue-600"><?php echo e($staff->years_of_service); ?> years</p>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Present Grade</p>
                                        <p class="font-semibold"><?php echo e($staff->present_grade ?: 'Not specified'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Location</p>
                                        <p class="font-semibold"><?php echo e($staff->location ?: 'Not specified'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Date of Employment</p>
                                        <p class="font-semibold"><?php echo e($staff->date_of_employment ? $staff->date_of_employment->format('d M Y') : 'Not specified'); ?></p>
                                    </div>
                                    <?php if($staff->date_of_posting): ?>
                                        <div>
                                            <p class="text-sm text-gray-500">Date of Posting</p>
                                            <p class="font-semibold"><?php echo e($staff->date_of_posting->format('d M Y')); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($staff->date_of_employment): ?>
                                        <div>
                                            <p class="text-sm text-gray-500">Years of Service</p>
                                            <p class="font-semibold text-blue-600"><?php echo e($staff->years_of_service); ?> years</p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($staff->job_description): ?>
                                        <div class="col-span-2">
                                            <p class="text-sm text-gray-500">Job Description</p>
                                            <p class="font-semibold"><?php echo e($staff->job_description); ?></p>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                
                                <div>
                                    <p class="text-sm text-gray-500">Date of Birth</p>
                                    <p class="font-semibold">
                                        <?php echo e($staff->date_of_birth ? $staff->date_of_birth->format('d M Y') : 'Not specified'); ?>

                                        <?php if($staff->date_of_birth): ?>
                                            <span class="text-xs text-gray-500">(<?php echo e($staff->age); ?> years old)</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <?php if($staff->last_promotion_date): ?>
                                    <div>
                                        <p class="text-sm text-gray-500">Last Promotion</p>
                                        <p class="font-semibold"><?php echo e($staff->last_promotion_date->format('d M Y')); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if($staff->department): ?>
                                    <div>
                                        <p class="text-sm text-gray-500">Department</p>
                                        <p class="font-semibold"><?php echo e($staff->department); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">My Documents</h3>
                        <button onclick="document.getElementById('upload-document-modal').classList.remove('hidden')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Upload Document
                        </button>
                    </div>

                    
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <?php $__currentLoopData = \App\Models\StaffDocument::getDocumentTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="#<?php echo e($type); ?>" 
                                   onclick="showDocumentType('<?php echo e($type); ?>')"
                                   class="document-tab <?php echo e($loop->first ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                                    <?php echo e($label); ?>

                                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs <?php echo e($loop->first ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'); ?>">
                                        <?php echo e($documentsByType[$type]->count()); ?>

                                    </span>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </nav>
                    </div>

                    
                    <?php $__currentLoopData = \App\Models\StaffDocument::getDocumentTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div id="docs-<?php echo e($type); ?>" class="document-list <?php echo e($loop->first ? '' : 'hidden'); ?>">
                            <?php if($documentsByType[$type]->count() > 0): ?>
                                <div class="space-y-3">
                                    <?php $__currentLoopData = $documentsByType[$type]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <span class="text-3xl"><?php echo e($document->getIcon()); ?></span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900"><?php echo e($document->document_name); ?></p>
                                                    <p class="text-sm text-gray-500">
                                                        <?php echo e($document->getFileSizeFormatted()); ?> • 
                                                        Uploaded <?php echo e($document->created_at->diffForHumans()); ?>

                                                        <?php if($document->expiry_date): ?>
                                                            • Expires: <?php echo e($document->expiry_date->format('d M Y')); ?>

                                                            <?php if($document->isExpired()): ?>
                                                                <span class="text-red-600 font-semibold">⚠️ Expired</span>
                                                            <?php elseif($document->expiresSoon()): ?>
                                                                <span class="text-yellow-600 font-semibold">⚠️ Expires Soon</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </p>
                                                    <?php if($document->description): ?>
                                                        <p class="text-xs text-gray-600 mt-1"><?php echo e($document->description); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="<?php echo e(route('documents.download', $document)); ?>" 
                                                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download
                                                </a>
                                                <form action="<?php echo e(route('documents.delete', $document)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-50 border border-red-200 rounded-lg text-sm font-medium text-red-700 hover:bg-red-100 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No <?php echo e(strtolower($label)); ?> uploaded</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by uploading a document.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">📦 My Assigned Inventory</h3>
                        <?php if($staff->activeInventoryAssignments->count() > 0): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <?php echo e($staff->activeInventoryAssignments->count()); ?> <?php echo e(Str::plural('Item', $staff->activeInventoryAssignments->count())); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if($staff->activeInventoryAssignments->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $staff->activeInventoryAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4 mb-3">
                                                <!-- Item Icon -->
                                                <div class="flex-shrink-0">
                                                    <?php if($assignment->item && $assignment->item->category): ?>
                                                        <div class="h-12 w-12 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                                                             style="background-color: <?php echo e($assignment->item->category->color); ?>">
                                                            <?php echo e($assignment->item->category->code); ?>

                                                        </div>
                                                    <?php else: ?>
                                                        <div class="h-12 w-12 rounded-lg flex items-center justify-center bg-gray-300 text-gray-600 text-sm font-bold">
                                                            ?
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Item Details -->
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 mb-1">
                                                        <h4 class="text-lg font-semibold text-gray-900">
                                                            <?php echo e($assignment->item->name); ?>

                                                        </h4>
                                                        <?php if($assignment->item->category): ?>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-white"
                                                                  style="background-color: <?php echo e($assignment->item->category->color); ?>">
                                                                <?php echo e($assignment->item->category->name); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <p class="text-sm text-gray-500">
                                                        <span class="font-mono"><?php echo e($assignment->item->item_code); ?></span>
                                                        <?php if($assignment->item->serial_number): ?>
                                                            • SN: <span class="font-mono"><?php echo e($assignment->item->serial_number); ?></span>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Assignment Info Grid -->
                                            <div class="ml-16 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <span class="text-gray-500 font-medium">Quantity</span>
                                                    <p class="text-gray-900 font-semibold text-lg mt-1"><?php echo e($assignment->quantity); ?></p>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <span class="text-gray-500 font-medium">Checked Out</span>
                                                    <p class="text-gray-900 font-semibold mt-1"><?php echo e($assignment->assigned_date->format('M j, Y')); ?></p>
                                                </div>
                                                <?php if($assignment->expected_return_date): ?>
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <span class="text-gray-500 font-medium">Due Back</span>
                                                        <p class="font-semibold mt-1 <?php echo e($assignment->isOverdue() ? 'text-red-600' : 'text-gray-900'); ?>">
                                                            <?php echo e($assignment->expected_return_date->format('M j, Y')); ?>

                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Condition & Notes -->
                                            <div class="ml-16 mt-3 flex items-center space-x-4 text-sm">
                                                <div>
                                                    <span class="text-gray-500">Condition:</span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium <?php echo e($assignment->item->condition_color); ?> ml-2">
                                                        <?php echo e(ucfirst($assignment->item->condition)); ?>

                                                    </span>
                                                </div>
                                            </div>

                                            <?php if($assignment->notes): ?>
                                                <div class="ml-16 mt-3 p-3 bg-blue-50 rounded-lg">
                                                    <p class="text-sm text-blue-900">
                                                        <span class="font-medium">📝 Notes:</span> <?php echo e($assignment->notes); ?>

                                                    </p>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($assignment->isOverdue()): ?>
                                                <div class="ml-16 mt-3 p-3 bg-red-50 rounded-lg border border-red-200">
                                                    <div class="flex items-center text-red-700">
                                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="font-semibold">OVERDUE by <?php echo e($assignment->expected_return_date->diffInDays(now())); ?> days</span>
                                                    </div>
                                                    <p class="text-sm mt-1 ml-7">Please return this item as soon as possible.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="ml-4 flex-shrink-0">
                                            <?php if($assignment->isOverdue()): ?>
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    ⚠️ Overdue
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    ✓ Active
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Summary Stats -->
                        <?php if($staff->activeInventoryAssignments->count() > 1): ?>
                            <div class="mt-6 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                                <h4 class="text-sm font-semibold text-blue-900 mb-3">📊 Summary</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div>
                                        <span class="text-blue-700 font-medium text-sm">Total Items</span>
                                        <p class="text-blue-900 font-bold text-2xl mt-1"><?php echo e($staff->activeInventoryAssignments->count()); ?></p>
                                    </div>
                                    <div>
                                        <span class="text-blue-700 font-medium text-sm">Total Quantity</span>
                                        <p class="text-blue-900 font-bold text-2xl mt-1"><?php echo e($staff->activeInventoryAssignments->sum('quantity')); ?></p>
                                    </div>
                                    <?php if($staff->overdueInventoryAssignments->count() > 0): ?>
                                        <div>
                                            <span class="text-red-700 font-medium text-sm">⚠️ Overdue</span>
                                            <p class="text-red-900 font-bold text-2xl mt-1"><?php echo e($staff->overdueInventoryAssignments->count()); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No Inventory Assigned</h3>
                            <p class="mt-2 text-sm text-gray-500">You currently have no inventory items checked out.</p>
                            <p class="mt-1 text-xs text-gray-400">When items are assigned to you, they will appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    
    <div id="profile-picture-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Upload Profile Picture</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="upload-form" action="<?php echo e(route('staff.profile.upload-picture')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Image (Max 5MB)</label>
                    <input type="file" 
                           id="picture-input"
                           name="profile_picture" 
                           accept="image/*" 
                           required
                           onchange="previewImage(event)"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 5MB</p>
                    
                    
                    <div id="preview-container" class="mt-4 hidden text-center">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <img id="preview-image" class="mx-auto w-32 h-32 rounded-full object-cover border-2 border-gray-300">
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeUploadModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" id="upload-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="upload-document-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Upload Document</h3>
                <button onclick="document.getElementById('upload-document-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="<?php echo e(route('staff.profile.upload-document')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                    <select name="document_type" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select type...</option>
                        <?php $__currentLoopData = \App\Models\StaffDocument::getDocumentTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>"><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Document Name *</label>
                    <input type="text" name="document_name" required placeholder="e.g., Bachelor's Degree"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">File * (Max 5MB)</label>
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="2" placeholder="Optional description..."
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Issue Date</label>
                        <input type="date" name="issue_date" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                        <input type="date" name="expiry_date"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="document.getElementById('upload-document-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Upload Document</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Document tabs
        function showDocumentType(type) {
            document.querySelectorAll('.document-list').forEach(list => list.classList.add('hidden'));
            document.querySelectorAll('.document-tab').forEach(tab => {
                tab.classList.remove('border-blue-500', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-500');
                const badge = tab.querySelector('span');
                if (badge) {
                    badge.classList.remove('bg-blue-100', 'text-blue-600');
                    badge.classList.add('bg-gray-100', 'text-gray-600');
                }
            });
            
            document.getElementById('docs-' + type).classList.remove('hidden');
            const activeTab = event.target.closest('.document-tab');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            const activeBadge = activeTab.querySelector('span');
            if (activeBadge) {
                activeBadge.classList.add('bg-blue-100', 'text-blue-600');
                activeBadge.classList.remove('bg-gray-100', 'text-gray-600');
            }
        }

        // Profile picture upload
        function openUploadModal() {
            document.getElementById('profile-picture-modal').classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('profile-picture-modal').classList.add('hidden');
            document.getElementById('upload-form').reset();
            document.getElementById('preview-container').classList.add('hidden');
        }

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-image');
            const container = document.getElementById('preview-container');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Auto-refresh image after upload
        <?php if(session('image_updated')): ?>
        window.addEventListener('load', function() {
            const img = document.getElementById('profile-picture-display');
            const timestamp = new Date().getTime();
            img.src = img.src.split('?')[0] + '?v=' + timestamp;
        });
        <?php endif; ?>
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /var/www/gafcsc-records/resources/views/staff/profile/show.blade.php ENDPATH**/ ?>