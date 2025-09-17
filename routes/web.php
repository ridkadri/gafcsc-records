<?php
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryAssignmentController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;


// Default welcome page
Route::redirect('/', '/login');

// Dashboard - all authenticated users can view
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Quick admin setup route (for initial setup only)
Route::post('/make-admin', [UserManagementController::class, 'makeAdmin'])
    ->middleware('auth')
    ->name('make.admin');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Quick super admin setup route (for initial setup only - when no super admin exists)
Route::post('/make-super-admin', [UserManagementController::class, 'makeSuperAdmin'])
    ->middleware('auth')
    ->name('make.super.admin');

// Quick admin setup route (for initial setup only)
Route::post('/make-admin', [UserManagementController::class, 'makeAdmin'])
    ->middleware('auth')
    ->name('make.admin');

// User Management routes (Admin and Super Admin only)
Route::middleware(['auth', 'verified', 'staff.permissions:manage'])->group(function () {
    // List all users
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    
    // Create new user
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    
    // Password reset routes
    Route::get('/users/{user}/reset-password', [UserManagementController::class, 'showResetForm'])->name('users.reset-form');
    Route::patch('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    
    // Show, edit, update, delete user routes
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});

// Super Admin only routes
Route::middleware(['auth', 'verified', 'super.admin'])->group(function () {
    // Super Admin dashboard
    Route::get('/super-admin/dashboard', [UserManagementController::class, 'superAdminIndex'])->name('users.super-admin.dashboard');
});

// In your dashboard or a simple test route
Route::get('/setup-super-admin', function() {
    $user = auth()->user();
    $user->role = 'super_admin';
    $user->save();
    return redirect()->route('dashboard')->with('success', 'You are now a Super Administrator!');
})->middleware('auth');


// Staff routes with permissions - IMPORTANT: Put specific routes BEFORE parameterized routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // View staff list (both admin and viewer can access)
    Route::get('/staff', [StaffController::class, 'index'])
        ->middleware('staff.permissions:view')
        ->name('staff.index');
    
    // Export routes (both admin and viewer can export) - MUST come before {staff} routes
    Route::get('/staff/export/csv', [StaffController::class, 'exportCsv'])
        ->middleware('staff.permissions:view')
        ->name('staff.export.csv');
        
    Route::get('/staff/export/excel', [StaffController::class, 'exportExcel'])
        ->middleware('staff.permissions:view')
        ->name('staff.export.excel');
        
    Route::get('/staff/export/pdf', [StaffController::class, 'exportPdf'])
        ->middleware('staff.permissions:view')
        ->name('staff.export.pdf');
    
    // CREATE routes MUST come before {staff} parameter routes
    Route::get('/staff/create', [StaffController::class, 'create'])
        ->middleware('staff.permissions:manage')
        ->name('staff.create');
    
    Route::post('/staff', [StaffController::class, 'store'])
        ->middleware('staff.permissions:manage')
        ->name('staff.store');
    
    // Parameterized routes come AFTER specific routes
    Route::get('/staff/{staff}', [StaffController::class, 'show'])
        ->middleware('staff.permissions:view')
        ->name('staff.show');
    
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])
        ->middleware('staff.permissions:manage')
        ->name('staff.edit');
    
    Route::put('/staff/{staff}', [StaffController::class, 'update'])
        ->middleware('staff.permissions:manage')
        ->name('staff.update');
    
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])
        ->middleware('staff.permissions:manage')
        ->name('staff.destroy');
});

// Inventory routes with permissions - same permission structure as staff
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/inventory-test', function() {
    return 'Inventory routes are working';
    });

    Route::get('/test-assignments', function() {
    return 'Route works!';
    });
    
    // Inventory overview (both admin and viewer can access)
    Route::get('/inventory', [InventoryController::class, 'index'])
        ->middleware('staff.permissions:view')
        ->name('inventory.index');
    
    // Inventory stats API
    Route::get('/inventory/stats', [InventoryController::class, 'getStats'])
        ->middleware('staff.permissions:view')
        ->name('inventory.stats');
    
    // Low stock and inspection routes (viewers can see these)
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock'])
        ->middleware('staff.permissions:view')
        ->name('inventory.low-stock');
        
    Route::get('/inventory/needs-inspection', [InventoryController::class, 'needsInspection'])
        ->middleware('staff.permissions:view')
        ->name('inventory.needs-inspection');
    
    // CREATE routes (admin only) - specific routes before parameterized
    Route::get('/inventory/create', [InventoryController::class, 'create'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.create');
    
    Route::post('/inventory', [InventoryController::class, 'store'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.store');
    
    // Assignment routes (check-in/check-out)
    Route::get('/inventory/assignments', [InventoryAssignmentController::class, 'index'])
        ->middleware('staff.permissions:view')
        ->name('inventory.assignments.index');
    
    Route::get('/inventory/assignments/overdue', [InventoryAssignmentController::class, 'overdue'])
        ->middleware('staff.permissions:view')
        ->name('inventory.assignments.overdue');
    
    // Assignment management (admin only)
    Route::get('/inventory/assignments/create', [InventoryAssignmentController::class, 'create'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.assignments.create');
    
    Route::post('/inventory/assignments', [InventoryAssignmentController::class, 'store'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.assignments.store');
    
    Route::get('/inventory/assignments/{assignment}', [InventoryAssignmentController::class, 'show'])
        ->middleware('staff.permissions:view')
        ->name('inventory.assignments.show');
    
    Route::get('/inventory/assignments/{assignment}/return', [InventoryAssignmentController::class, 'returnForm'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.assignments.return');
    
    Route::post('/inventory/assignments/{assignment}/return', [InventoryAssignmentController::class, 'processReturn'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.assignments.process-return');
    
    Route::post('/inventory/assignments/{assignment}/mark-lost', [InventoryAssignmentController::class, 'markAsLost'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.assignments.mark-lost');
    
    Route::post('/inventory/assignments/{assignment}/send-reminder', [InventoryAssignmentController::class, 'sendReminder'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.assignments.send-reminder');
    
    Route::post('/inventory/assignments/bulk-return', [InventoryAssignmentController::class, 'bulkReturn'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.assignments.bulk-return');
    
    // Inventory item management (parameterized routes after specific ones)
    Route::get('/inventory/{item}', [InventoryController::class, 'show'])
        ->middleware('staff.permissions:view')
        ->name('inventory.show');
    
    Route::get('/inventory/{item}/edit', [InventoryController::class, 'edit'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.edit');
    
    Route::put('/inventory/{item}', [InventoryController::class, 'update'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.update');
    
    Route::delete('/inventory/{item}', [InventoryController::class, 'destroy'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.destroy');
    
    // Inventory quantity adjustments (admin only)
    Route::post('/inventory/{item}/adjust-quantity', [InventoryController::class, 'adjustQuantity'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.adjust-quantity');
    
    Route::post('/inventory/{item}/generate-barcode', [InventoryController::class, 'generateBarcode'])
        ->middleware('staff.permissions:manage')
        ->name('inventory.generate-barcode');
    
    
    
    // Categories management (admin only)
    Route::resource('inventory-categories', InventoryCategoryController::class)
        ->middleware('staff.permissions:manage')
        ->names([
            'index' => 'inventory.categories.index',
            'create' => 'inventory.categories.create',
            'store' => 'inventory.categories.store',
            'show' => 'inventory.categories.show',
            'edit' => 'inventory.categories.edit',
            'update' => 'inventory.categories.update',
            'destroy' => 'inventory.categories.destroy',
        ]);
    
    // Add these routes to your inventory categories section in web.php:
    Route::get('/inventory-categories/{inventoryCategory}/items', [InventoryCategoryController::class, 'items'])
        ->middleware('staff.permissions:view')
        ->name('inventory.categories.items');

    Route::get('/inventory-categories/{inventoryCategory}/low-stock', [InventoryCategoryController::class, 'lowStock'])
        ->middleware('staff.permissions:view')
        ->name('inventory.categories.low-stock');

    Route::get('/inventory-categories/{inventoryCategory}/needs-inspection', [InventoryCategoryController::class, 'needsInspection'])
        ->middleware('staff.permissions:view')
        ->name('inventory.categories.needs-inspection');
    });


require __DIR__.'/auth.php';