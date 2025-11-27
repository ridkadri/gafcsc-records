<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'GAFCSC')); ?> - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex">
        <!-- Left Panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-32 -translate-y-32"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-48 translate-y-48"></div>
                <div class="absolute top-1/2 left-1/2 w-32 h-32 bg-white rounded-full -translate-x-16 -translate-y-16"></div>
            </div>
            
            <!-- Content -->
            <div class="flex flex-col justify-center items-center w-full px-16 relative z-10">
                <!-- Logo/Emblem -->
                <div class="mb-8">
                    <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-32 h-32 object-contain']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-32 h-32 object-contain']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                </div>
                
                <!-- Institution Info -->
                <div class="text-center text-gray-800">
                    <h1 class="text-4xl font-bold mb-4">GAFCSC</h1>
                    <h2 class="text-xl font-semibold mb-2">Ghana Armed Forces Command & Staff College</h2>
                    <h3 class="text-lg mb-6"></h3>
                    <div class="h-0.5 w-24 bg-gray-600 mx-auto mb-6 opacity-60"></div>
                    <p class="text-gray-700 text-lg leading-relaxed max-w-md">
                        Staff Management System
                    </p>
                    <p class="text-gray-600 text-sm mt-4 max-w-sm">
                        Comprehensive personnel, inventory, and resource management for authorized staff
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24 bg-gray-50">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center">
                        <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-12 h-12 mr-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                        <span class="text-2xl font-bold text-gray-900">GAFCSC</span>
                    </div>
                </div>

               <div>
                    <h2 class="text-3xl font-bold text-gray-900 text-center lg:text-left">Welcome Back</h2>
                    <p class="mt-2 text-sm text-gray-600 text-center lg:text-left">
                        Sign in with your username and password
                    </p>
                </div>

                <div class="mt-8">
                    <!-- Session Status -->
                    <?php if(session('status')): ?>
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Login Instructions -->
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-xs text-blue-800 font-medium">Login Information</p>
                                <p class="text-xs text-blue-700 mt-1">
                                    Use your <strong>username</strong> and the <strong>default password</strong> to sign in.<br>
                                    <strong>Default Password:</strong> gafcsc@123<br>
                                    You will be required to change your password on first login.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6" id="loginForm">
                        <?php echo csrf_field(); ?>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="username" 
                                    name="username" 
                                    type="text" 
                                    required 
                                    autofocus
                                    value="<?php echo e(old('username')); ?>"
                                    class="block w-full pl-10 pr-3 py-2.5 rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="Enter your username">
                            </div>
                            <?php $__errorArgs = ['username'];
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

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" 
                                    name="password" 
                                    type="password" 
                                    required 
                                    autocomplete="current-password"
                                    class="block w-full pl-10 pr-3 py-2.5 rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="Enter your password">
                            </div>
                            <?php $__errorArgs = ['password'];
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

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                    Remember me
                                </label>
                            </div>
                            
                            <?php if(Route::has('password.request')): ?>
                                <div class="text-sm">
                                    <a href="<?php echo e(route('password.request')); ?>" class="font-medium text-blue-600 hover:text-blue-500">
                                        Forgot your password?
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sign In Button -->
                        <div>
                            <button type="submit" 
                                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-lg hover:shadow-xl">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                Sign in to GAFCSC
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-400">
                        © <?php echo e(date('Y')); ?> Ghana Armed Forces Command & Staff College
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Staff Management System v1.0
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const displayInput = document.getElementById('date_of_birth_display');
        const hiddenInput = document.getElementById('date_of_birth');
        const dateHelp = document.getElementById('date-help');
        
        // Auto-format date as user types
        displayInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            let formattedValue = '';
            
            if (value.length >= 1) {
                formattedValue = value.substring(0, 2);
            }
            if (value.length >= 3) {
                formattedValue += '/' + value.substring(2, 4);
            }
            if (value.length >= 5) {
                formattedValue += '/' + value.substring(4, 8);
            }
            
            e.target.value = formattedValue;
            
            // Validate and convert to YYYY-MM-DD for submission
            if (formattedValue.length === 10) {
                const parts = formattedValue.split('/');
                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10);
                const year = parseInt(parts[2], 10);
                
                // Basic validation
                if (day >= 1 && day <= 31 && month >= 1 && month <= 12 && year >= 1900 && year <= 2100) {
                    // Convert to YYYY-MM-DD format for the hidden field
                    const isoDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    hiddenInput.value = isoDate;
                    
                    // Update help text to show what will be submitted
                    dateHelp.innerHTML = `<span class="text-green-600">✓ Valid date (will submit as: ${isoDate})</span>`;
                    displayInput.classList.remove('border-red-500');
                    displayInput.classList.add('border-green-500');
                } else {
                    hiddenInput.value = '';
                    dateHelp.innerHTML = '<span class="text-red-600">Invalid date. Please use DD/MM/YYYY format</span>';
                    displayInput.classList.add('border-red-500');
                    displayInput.classList.remove('border-green-500');
                }
            } else {
                hiddenInput.value = '';
                dateHelp.innerHTML = 'Enter date as DD/MM/YYYY (e.g., 15/03/1985)';
                displayInput.classList.remove('border-red-500', 'border-green-500');
            }
        });
        
        // Handle paste events
        displayInput.addEventListener('paste', function(e) {
            e.preventDefault();
            let pasteData = (e.clipboardData || window.clipboardData).getData('text');
            
            // Try to parse different date formats
            let dateRegex = /(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})/;
            let match = pasteData.match(dateRegex);
            
            if (match) {
                let day = match[1].padStart(2, '0');
                let month = match[2].padStart(2, '0');
                let year = match[3];
                displayInput.value = `${day}/${month}/${year}`;
                displayInput.dispatchEvent(new Event('input'));
            }
        });
        
        // Prevent form submission if date is invalid
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (!hiddenInput.value) {
                e.preventDefault();
                displayInput.focus();
                dateHelp.innerHTML = '<span class="text-red-600">Please enter a valid date before submitting</span>';
                displayInput.classList.add('border-red-500');
                return false;
            }
        });
        
        // If there's an old value (e.g., after validation error), convert it for display
        <?php if(old('date_of_birth')): ?>
            const oldDate = '<?php echo e(old('date_of_birth')); ?>';
            if (oldDate) {
                const parts = oldDate.split('-');
                if (parts.length === 3) {
                    displayInput.value = `${parts[2]}/${parts[1]}/${parts[0]}`;
                    hiddenInput.value = oldDate;
                }
            }
        <?php endif; ?>
    });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\gafcsc-records\resources\views/auth/login.blade.php ENDPATH**/ ?>