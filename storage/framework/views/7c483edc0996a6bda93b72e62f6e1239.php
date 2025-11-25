<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'GAFCSC')); ?> - Inventory</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Top Navigation (SYNCED WITH MAIN APP) -->
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Content with Sidebar -->
        <div class="flex">
            <!-- Inventory Sidebar -->
            <div class="w-64 bg-white border-r border-gray-200 min-h-screen">
                <div class="p-6">
                    <!-- Inventory Header -->
                    <div class="mb-8">
                        <h1 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Inventory Management
                        </h1>
                        <p class="text-sm text-gray-600 mt-1">GAFCSC Equipment & Assets</p>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="space-y-6">
                        <!-- Overview Section -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Overview</h3>
                            <div class="space-y-1">
                                <a href="<?php echo e(route('inventory.index')); ?>" 
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('inventory.index') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    All Items
                                </a>

                                <a href="<?php echo e(route('inventory.low-stock')); ?>" 
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('inventory.low-stock') ? 'bg-red-100 text-red-700 border-r-2 border-red-500' : 'text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    Low Stock
                                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800" id="low-stock-count">
                                        <?php echo e(\App\Models\InventoryItem::active()->lowStock()->count()); ?>

                                    </span>
                                </a>
                            </div>
                        </div>

                        <!-- Items Section -->
                        <?php if(Auth::user()->canManageInventory()): ?>
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Items</h3>
                            <div class="space-y-1">
                                <a href="<?php echo e(route('inventory.create')); ?>" 
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('inventory.create') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add New Item
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Assignments Section -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Assignments</h3>
                            <div class="space-y-1">
                                <a href="<?php echo e(route('inventory.assignments.index')); ?>" 
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('inventory.assignments.index') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    All Assignments
                                </a>

                                <?php if(Auth::user()->canManageInventory()): ?>
                                <a href="<?php echo e(route('inventory.assignments.create')); ?>" 
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('inventory.assignments.create') ? 'bg-green-100 text-green-700 border-r-2 border-green-500' : 'text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Check Out Item
                                </a>
                                <?php endif; ?>

                                <a href="<?php echo e(route('inventory.assignments.overdue')); ?>" 
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('inventory.assignments.overdue') ? 'bg-red-100 text-red-700 border-r-2 border-red-500' : 'text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Overdue Returns
                                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <?php echo e(\App\Models\InventoryAssignment::overdue()->count()); ?>

                                    </span>
                                </a>
                            </div>
                        </div>

                        <!-- Categories Section -->
                        <?php if(Auth::user()->canManageInventory()): ?>
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Categories</h3>
                            <div class="space-y-1">
                                <a href="<?php echo e(route('inventory.categories.index')); ?>" 
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('inventory.categories.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Manage Categories
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Quick Stats -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Quick Stats</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Total Items</span>
                                    <span class="font-medium text-gray-900"><?php echo e(\App\Models\InventoryItem::active()->count()); ?></span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Active Assignments</span>
                                    <span class="font-medium text-gray-900"><?php echo e(\App\Models\InventoryAssignment::active()->count()); ?></span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Total Value</span>
                                    <span class="font-medium text-gray-900">$<?php echo e(number_format(\App\Models\InventoryItem::active()->sum(\DB::raw('unit_cost * total_quantity')), 0)); ?></span>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 overflow-x-hidden">
                <!-- Page Header -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="px-6 py-4">
                        <?php echo e($header ?? ''); ?>

                    </div>
                </header>

                <!-- Page Content -->
                <main>
                    <?php echo $__env->yieldContent('content'); ?>
                </main>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH /var/www/gafcsc-records/resources/views/layouts/inventory.blade.php ENDPATH**/ ?>