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
                <?php echo e(__('Reset Password')); ?>

            </h2>
            <a href="<?php echo e(route('users.index')); ?>" class="text-sm text-blue-600 hover:text-blue-800">
                ← Back to Users
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- User Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-full flex items-center justify-center text-white text-lg font-bold 
                                <?php echo e($user->isSuperAdmin() ? 'bg-red-500' : 'bg-blue-500'); ?>">
                                <?php echo e(substr($user->name, 0, 1)); ?>

                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($user->name); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo e($user->email ?? 'No email'); ?></p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Current Role: <span class="font-medium"><?php echo e($user->getRoleDisplayName()); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Alert -->
                    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <h4 class="font-medium text-yellow-800">Reset Password Confirmation</h4>
                                <p class="text-yellow-700 text-sm mt-1">
                                    You are about to reset the password for <strong><?php echo e($user->name); ?></strong> to the default system password.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Default Password Info -->
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="font-medium text-blue-800">Default Password Information</h4>
                                <div class="mt-2 space-y-2 text-sm text-blue-700">
                                    <div class="flex items-center">
                                        <span class="font-medium w-32">Default Password:</span>
                                        <code class="bg-blue-100 px-2 py-1 rounded font-mono">gafcsc@123</code>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="font-medium w-32">After Reset:</span>
                                        <span>The user will be required to change their password on next login.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Super Admin Protection Notice -->
                    <?php if($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-red-800">Super Administrator Protection</h4>
                                    <p class="text-red-700 text-sm mt-1">
                                        Super Administrator passwords cannot be reset by regular administrators for security reasons.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Reset Form -->
                    <?php if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin()): ?>
                        <form method="POST" action="<?php echo e(route('users.reset-password', $user)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <a href="<?php echo e(route('users.index')); ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                                        onclick="return confirm('Are you sure you want to reset the password for <?php echo e($user->name); ?> to the default?')">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Reset Password to Default
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
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
<?php endif; ?><?php /**PATH /var/www/gafcsc-records/resources/views/users/reset-password.blade.php ENDPATH**/ ?>