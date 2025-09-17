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
            <?php echo e(__('Add New Staff Member')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="<?php echo e(route('staff.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <!-- Rank/Title -->
                        <div class="mb-6">
                            <label for="rank" class="block text-sm font-medium text-gray-700 mb-2">
                                Rank/Title
                            </label>
                            <input type="text" 
                                   id="rank" 
                                   name="rank" 
                                   value="<?php echo e(old('rank')); ?>"
                                   placeholder="e.g., Colonel, Professor, Dr, Mr, Mrs, etc."
                                   list="rank-suggestions"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 <?php $__errorArgs = ['rank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            
                            <!-- Datalist for rank suggestions -->
                            <datalist id="rank-suggestions">
                                <!-- Military Ranks -->
                                <option value="General">
                                <option value="Brigadier General">
                                <option value="Colonel">
                                <option value="Lieutenant Colonel">
                                <option value="Major">
                                <option value="Captain">
                                <option value="Lieutenant">
                                <option value="Second Lieutenant">
                                <option value="Sergeant Major">
                                <option value="Sergeant">
                                <option value="Corporal">
                                <option value="Private">
                                <!-- Civilian Titles -->
                                <option value="Professor">
                                <option value="Associate Professor">
                                <option value="Assistant Professor">
                                <option value="Senior Lecturer">
                                <option value="Lecturer">
                                <option value="Dr">
                                <option value="Mr">
                                <option value="Mrs">
                                <option value="Ms">
                                <option value="Miss">
                                <?php if(isset($existingRanks)): ?>
                                    <?php $__currentLoopData = $existingRanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existingRank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($existingRank); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </datalist>
                            
                            <?php $__errorArgs = ['rank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="mt-1 text-sm text-gray-500">Military rank or civilian title (e.g., Colonel, Professor, Dr)</p>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo e(old('name')); ?>"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
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

                        <!-- Service Number -->
                        <div class="mb-6">
                            <label for="service_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Service Number *
                            </label>
                            <input type="text" 
                                   id="service_number" 
                                   name="service_number" 
                                   value="<?php echo e(old('service_number')); ?>"
                                   required
                                   placeholder="e.g., MIL001, CIV001, etc."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 <?php $__errorArgs = ['service_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['service_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="mt-1 text-sm text-gray-500">Unique identifier for both military and civilian staff</p>
                        </div>

                        <!-- Staff Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Staff Type *
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="staff_type" 
                                           value="military" 
                                           <?php echo e(old('staff_type') === 'military' ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                    <span class="ml-3 text-sm">
                                        <span class="font-medium text-gray-900">Military Personnel</span>
                                        <span class="block text-gray-500">Active military staff (commissioned and non-commissioned officers)</span>
                                    </span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="staff_type" 
                                           value="civilian" 
                                           <?php echo e(old('staff_type', 'civilian') === 'civilian' ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-3 text-sm">
                                        <span class="font-medium text-gray-900">Civilian Personnel</span>
                                        <span class="block text-gray-500">Academic and administrative civilian staff</span>
                                    </span>
                                </label>
                            </div>
                            <?php $__errorArgs = ['staff_type'];
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

                        

                        <!-- Appointment -->
                        <div class="mb-6">
                            <label for="appointment" class="block text-sm font-medium text-gray-700 mb-2">
                                Appointment
                            </label>
                            <input type="text" 
                                   id="appointment" 
                                   name="appointment" 
                                   value="<?php echo e(old('appointment')); ?>"
                                   placeholder="e.g., Commandant, Dean, Head of Department, etc."
                                   list="appointment-suggestions"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 <?php $__errorArgs = ['appointment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            
                            <!-- Datalist for appointment suggestions -->
                            <datalist id="appointment-suggestions">
                                <option value="Commandant">
                                <option value="Deputy Commandant">
                                <option value="Assistant Commandant (Snr Div)">
                                <option value="Assistant Commandant (Jnr Div)">
                                <option value="C COORD">
                                <option value="Dir Corporate Affairs">
                                <option value="Dir International Staff & Students">
                                <option value="Dir R&D">
                                <option value="GSOI Coord (Snr Div)">
                                <option value="GSOI Coord (Jnr Div)">
                                <option value="CO">
                                <option value="Financial Comptroller">
                                <option value="CPRO">
                                <option value="COMD Legal Officer">
                                <option value="IT Manager">
                                <option value="DD Corp Affairs">
                                <option value="GSO II (A&Q)">
                                <option value="GSO II Coord (Snr Div)">
                                <option value="GSO II Coord (Jnr Div)">
                                <option value="Comd PRO">
                                <option value="Admin Officer">
                                <option value="ADC to Cmdt">
                                <option value="GSO II Coord (Snr Div)">
                                <option value="Chief Instructor">
                                <option value="Directing Staff (Snr Div)">
                                <option value="Directing Staff (Jnr Div)">
                                <!---- Civilian Appointments ----->
                                <option value="Dean">
                                <option value="Associate Dean">
                                <option value="Senior Research Fellow">
                                <option value="Director">
                                <option value="Registrar">
                                <option value="Librarian">
                                <?php if(isset($existingAppointments)): ?>
                                    <?php $__currentLoopData = $existingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existingAppointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($existingAppointment); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </datalist>
                            
                            <?php $__errorArgs = ['appointment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="mt-1 text-sm text-gray-500">Official position or appointment within the university</p>
                        </div>

                        <!-- Department -->
                        <div class="mb-6">
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                Department *
                            </label>
                            <input type="text" 
                                   id="department" 
                                   name="department" 
                                   value="<?php echo e(old('department')); ?>"
                                   required
                                   placeholder="e.g., Command, Military Studies, Administration, etc."
                                   list="department-suggestions"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 <?php $__errorArgs = ['department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            
                            <!-- Datalist for department suggestions -->
                            <datalist id="department-suggestions">
                                <option value="Command">
                                <option value="Military Studies">
                                <option value="Teaching Class">
                                <option value="Registry">
                                <option value="Graphic & Map">
                                <option value="Stores">
                                <option value="Audio Visual">
                                <option value="Tradesmen">
                                <option value="Catering/Mess/Bar/Kitchen">
                                <option value="Labourers">
                                <option value="Technical (Printing)">
                                <option value="Gardener">
                                <option value="Sanitation">
                                <option value="Messenger">
                                <option value="Wardens">
                                <option value="Finance">
                                <option value="Corporate Affairs">
                                <option value="Information Technology">
                                <option value="Library">
                                <option value="Research">
                                <option value="Administration">
                                <option value="Secretarial">
                                <?php if(isset($existingDepartments)): ?>
                                    <?php $__currentLoopData = $existingDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existingDepartment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($existingDepartment); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </datalist>
                            
                            <?php $__errorArgs = ['department'];
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

                        <!-- Location -->
                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location
                            </label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   value="<?php echo e(old('location')); ?>"
                                   placeholder="e.g., HQ, Junior Division, Main Campus, etc."
                                   list="location-suggestions"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            
                            <!-- Datalist for location suggestions -->
                            <datalist id="location-suggestions">
                                <option value="HQ">
                                <option value="Junior Division">
                                <option value="Research Block">
                                <option value="Admin Block">
                                <option value="Library">
                                <option value="Dep CMDT Block">
                                <option value="DS Block">
                                <option value="Airforce Block">
                                <option value="MT Yard">
                                <?php if(isset($existingLocations)): ?>
                                    <?php $__currentLoopData = $existingLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existingLocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($existingLocation); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
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
                            <p class="mt-1 text-sm text-gray-500">Physical location or campus area</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="<?php echo e(route('staff.index')); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Add Staff Member
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
<?php endif; ?><?php /**PATH /Users/ridwankadri/Desktop/code/gafcsc-management/resources/views/staff/create.blade.php ENDPATH**/ ?>