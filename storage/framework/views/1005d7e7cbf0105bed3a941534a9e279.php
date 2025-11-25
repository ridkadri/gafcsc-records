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
                <?php echo e(__('Assign Subordinates to HOD')); ?>

            </h2>
            <a href="<?php echo e(route('hod-management.index')); ?>" class="text-sm text-blue-600 hover:text-blue-800">
                ← Back to HOD Management
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- HOD Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-lg font-bold">
                                <?php echo e(substr($hod->name, 0, 1)); ?>

                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($hod->name); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo e($hod->service_number); ?></p>
                                <p class="text-sm text-gray-600"><?php echo e($hod->department ?? 'No Department'); ?></p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="<?php echo e(route('hod-management.update-subordinates', $hod)); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Select Subordinates (Staff in the same department)
                                </label>

                                <?php if($availableStaff->count() > 0): ?>
                                    <div class="space-y-3 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4">
                                        <?php $__currentLoopData = $availableStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="relative flex items-start p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                <div class="flex items-center h-5">
                                                    <input 
                                                        type="checkbox" 
                                                        name="subordinates[]" 
                                                        value="<?php echo e($staff->id); ?>"
                                                        <?php echo e(in_array($staff->id, $currentSubordinates->pluck('id')->toArray()) ? 'checked' : ''); ?>

                                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    >
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <span class="block text-sm font-medium text-gray-900">
                                                        <?php echo e($staff->name); ?>

                                                    </span>
                                                    <span class="block text-xs text-gray-500 mt-1">
                                                        <?php echo e($staff->service_number); ?> • 
                                                        <?php echo e(ucfirst($staff->type)); ?> • 
                                                        <?php echo e($staff->appointment ?? 'No Appointment'); ?>

                                                    </span>
                                                </div>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-8 border border-gray-200 rounded-lg">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No staff available</h3>
                                        <p class="mt-1 text-sm text-gray-500">There are no other staff members in the same department to assign.</p>
                                    </div>
                                <?php endif; ?>

                                <?php $__errorArgs = ['subordinates'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">About Subordinates</h4>
                                <div class="text-xs text-blue-800 space-y-1">
                                    <p>• Only staff members in the same department as the HOD are shown.</p>
                                    <p>• Subordinates will appear on the HOD's dashboard.</p>
                                    <p>• The HOD will be able to view the profiles of their subordinates.</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                <a href="<?php echo e(route('hod-management.index')); ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Assign Subordinates
                                </button>
                            </div>
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
<?php endif; ?><?php /**PATH /var/www/gafcsc-records/resources/views/hod-management/assign-subordinates.blade.php ENDPATH**/ ?>