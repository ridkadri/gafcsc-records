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
                <?php echo e(__('Edit User Role')); ?>

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
                    <!-- User Info Card -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-lg font-bold">
                                <?php echo e(substr($user->name, 0, 1)); ?>

                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($user->name); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo e($user->email ?? 'No email'); ?></p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Current Role: <span class="font-medium text-blue-600"><?php echo e($user->getRoleDisplayName()); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Change Role Form -->
                    <form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Select New Role
                                </label>

                                <div class="space-y-3">
                                    <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleValue => $roleLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($roleValue === 'head_of_department'): ?>
                                            <!-- Special styling for HOD role -->
                                            <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors <?php echo e($user->role === $roleValue ? 'border-blue-500 bg-blue-50' : 'border-blue-200'); ?>">
                                        <?php else: ?>
                                            <label class="relative flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors <?php echo e($user->role === $roleValue ? 'border-blue-500 bg-blue-50' : 'border-gray-300'); ?>">
                                        <?php endif; ?>
                                        <div class="flex items-center h-5">
                                            <input 
                                                type="radio" 
                                                name="role" 
                                                value="<?php echo e($roleValue); ?>"
                                                <?php echo e($user->role === $roleValue ? 'checked' : ''); ?>

                                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                required
                                            >
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <span class="block text-sm font-medium text-gray-900">
                                                <?php echo e($roleLabel); ?>

                                                <?php if($roleValue === 'head_of_department'): ?>
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    HOD
                                                    </span>
                                                <?php endif; ?>
                                            </span>
                                            <span class="block text-xs text-gray-500 mt-1">
                                                <?php switch($roleValue):
                                                    case ('super_admin'): ?>
                                                        Full system access. Can manage all users and assign roles.
                                                        <?php break; ?>
                                                    <?php case ('admin'): ?>
                                                        Full system access except Super Admin management.
                                                        <?php break; ?>
                                                    <?php case ('head_of_department'): ?>
                                                        Can view and manage staff in their department. Sees subordinates on dashboard.
                                                        <?php break; ?>
                                                    <?php case ('military_admin'): ?>
                                                        Can view all staff (military + civilian) and inventory.
                                                        <?php break; ?>
                                                    <!-- ... other roles ... -->
                                                <?php endswitch; ?>
                                            </span>
                                        </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <?php $__errorArgs = ['role'];
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

                            <!-- Permission Summary for Selected Role -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">📋 Permission Summary</h4>
                                <div id="permission-summary" class="text-xs text-blue-800 space-y-1">
                                    <!-- Will be updated via JavaScript based on selected role -->
                                </div>
                            </div>

                            <!-- Warning for Role Change -->
                            <?php if($user->role !== 'viewer'): ?>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-800">
                                                <strong>Warning:</strong> Changing this user's role will immediately affect their access permissions. Make sure you understand the implications before proceeding.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                <a href="<?php echo e(route('users.index')); ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update Role
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update permission summary based on selected role
        const permissionDescriptions = {
            'super_admin': [
                '✅ Full access to all pages',
                '✅ Manage users and assign roles',
                '✅ View and edit all staff',
                '✅ Manage inventory',
                '✅ Delete any records'
            ],
            'admin': [
            '✅ Full access to all pages',
            '✅ Manage users and assign roles (except Super Admins)',
            '✅ View and edit all staff',
            '✅ Manage inventory',
            '✅ Delete any records (except Super Admins)'
        ],
            'head_of_department': [
            '✅ View and manage staff in their department',
            '✅ See subordinates on dashboard',
            '✅ Department-specific statistics',
            '❌ Cannot manage users',
            '❌ Cannot access other departments',
            '❌ Limited to assigned department only'
        ],
            'military_admin': [
                '✅ View all staff (military + civilian)',
                '✅ View inventory',
                '❌ Cannot manage users',
                '❌ Cannot add/edit staff',
                '❌ Cannot manage inventory'
            ],
            'chief_clerk': [
                '✅ View military staff',
                '✅ Add new military staff',
                '✅ Edit military staff',
                '❌ Cannot view civilian staff',
                '❌ Cannot view inventory',
                '❌ Cannot manage users'
            ],
            'capo': [
                '✅ View civilian staff',
                '✅ Edit civilian staff',
                '❌ Cannot view military staff',
                '❌ Cannot view inventory',
                '❌ Cannot manage users'
            ],
            'peo': [
                '✅ View civilian staff',
                '✅ Add new civilian staff',
                '❌ Cannot edit existing civilians',
                '❌ Cannot view military staff',
                '❌ Cannot view inventory',
                '❌ Cannot manage users'
            ],
            'viewer': [
                '✅ View own profile only',
                '❌ Cannot view other staff',
                '❌ Cannot add/edit any records',
                '❌ Cannot access inventory',
                '❌ Cannot manage users'
            ]
        };

        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const summary = document.getElementById('permission-summary');
                const permissions = permissionDescriptions[this.value] || [];
                summary.innerHTML = permissions.join('<br>');
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="role"]:checked');
            if (checkedRadio) {
                const summary = document.getElementById('permission-summary');
                const permissions = permissionDescriptions[checkedRadio.value] || [];
                summary.innerHTML = permissions.join('<br>');
            }
        });
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
<?php endif; ?><?php /**PATH /var/www/gafcsc-records/resources/views/users/edit.blade.php ENDPATH**/ ?>