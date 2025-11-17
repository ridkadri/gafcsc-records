<?php
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryAssignmentController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HodManagementController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\Auth\PasswordChangeController;
use Illuminate\Support\Facades\Route;


// Default welcome page
Route::redirect('/', '/login');

// Password change routes (accessible without password change enforcement)
Route::middleware(['auth'])->group(function () {
    Route::get('/password-change', [PasswordChangeController::class, 'show'])
        ->name('password.change');
    Route::post('/password-change', [PasswordChangeController::class, 'update'])
        ->name('password.change.update');
});

// Dashboard - with password change enforcement
Route::get('/dashboard', function () {
    // If user needs password change, redirect them (extra protection)
    if (auth()->user()->needsPasswordChange()) {
        return redirect()->route('password.change');
    }
    
    if (auth()->user()->isViewer()) {
        return redirect()->route('staff.profile.show');
    }
    return view('dashboard');
})->middleware(['auth', 'verified', 'force.password.change'])->name('dashboard');

// Profile routes
Route::middleware(['auth', 'verified', 'force.password.change'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
     Route::patch('/profile/update-dob', [ProfileController::class, 'updateDateOfBirth'])->name('profile.update-dob');
});

// Quick super admin setup route (for initial setup only - when no super admin exists)
Route::post('/make-super-admin', [UserManagementController::class, 'makeSuperAdmin'])
    ->middleware(['auth', 'force.password.change'])
    ->name('make.super.admin');

    // Add this route in the User Management routes group
Route::post('/users/{user}/quick-reset-password', [UserManagementController::class, 'quickResetPassword'])
    ->name('users.quick-reset-password');

// Quick admin setup route (for initial setup only)
Route::post('/make-admin', [UserManagementController::class, 'makeAdmin'])
    ->middleware(['auth', 'force.password.change'])
    ->name('make.admin');

// User Management routes (Super Admin only)
Route::middleware(['auth', 'verified', 'super.admin', 'force.password.change'])->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/reset-password', [UserManagementController::class, 'showResetForm'])->name('users.reset-form');
    Route::patch('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});

// Staff Profile Routes (for staff to view their own profile)
Route::middleware(['auth', 'verified', 'force.password.change'])->group(function () {
    // My Profile - All authenticated staff can access their own profile
    Route::get('/my-profile', [StaffProfileController::class, 'show'])->name('staff.profile.show');
    
    // Upload profile picture (staff uploads their own)
    Route::post('/my-profile/upload-picture', [StaffProfileController::class, 'uploadProfilePicture'])->name('staff.profile.upload-picture');
    
    // Upload document (staff uploads their own documents)
    Route::post('/my-profile/upload-document', [StaffProfileController::class, 'uploadDocument'])->name('staff.profile.upload-document');
    
    // Download document
    Route::get('/documents/{document}/download', [StaffProfileController::class, 'downloadDocument'])->name('documents.download');
    
    // Delete document
    Route::delete('/documents/{document}', [StaffProfileController::class, 'deleteDocument'])->name('documents.delete');
});

// Admin: Upload documents/pictures for staff
Route::middleware(['auth', 'verified', 'staff.permissions:manage', 'force.password.change'])->group(function () {
    // Admin uploads document for a staff member
    Route::post('/staff/{staff}/upload-document', [StaffProfileController::class, 'adminUploadDocument'])->name('staff.admin.upload-document');
    
    // Admin uploads profile picture for a staff member
    Route::post('/staff/{staff}/upload-picture', [StaffProfileController::class, 'adminUploadProfilePicture'])->name('staff.admin.upload-picture');
});

Route::get('/staff/department-report', [StaffController::class, 'departmentReport'])
    ->name('staff.department-report')
    ->middleware(['auth']);


// Staff routes with role-based permissions
Route::middleware(['auth', 'verified', 'force.password.change'])->group(function () {
    
    // View staff list (anyone with staff viewing permission)
    Route::get('/staff', [StaffController::class, 'index'])
        ->middleware('staff.permissions:view')
        ->name('staff.index');
    
    // Export routes - MUST come before {staff} routes
    Route::get('/staff/export/csv', [StaffController::class, 'exportCsv'])
        ->middleware('staff.permissions:view')
        ->name('staff.export.csv');
        
    Route::get('/staff/export/excel', [StaffController::class, 'exportExcel'])
        ->middleware('staff.permissions:view')
        ->name('staff.export.excel');

    Route::get('/staff/export/pdf/preview', [StaffController::class, 'previewPdf'])
        ->middleware('staff.permissions:view')
        ->name('staff.preview.pdf');
        
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



// HOD Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/hod-management', [HodManagementController::class, 'index'])->name('hod-management.index');
    Route::get('/hod-management/create', [HodManagementController::class, 'create'])->name('hod-management.create');
    Route::post('/hod-management', [HodManagementController::class, 'store'])->name('hod-management.store');
    Route::delete('/hod-management/{hod}', [HodManagementController::class, 'destroy'])->name('hod-management.destroy');
});

// Inventory routes with permissions - Super Admin and Military Admin only
Route::middleware(['auth', 'verified', 'force.password.change'])->group(function () {

    Route::prefix('inventory')->name('inventory.')->group(function (){
        // Categories management (super admin only for now)
        Route::resource('categories', InventoryCategoryController::class)
            ->middleware('staff.permissions:manage_inventory');
        
        // Inventory categories section:
        Route::get('/categories/{category}/items', [InventoryCategoryController::class, 'items'])
            ->middleware('staff.permissions:view_inventory')
            ->name('categories.items');

        Route::get('/categories/{category}/low-stock', [InventoryCategoryController::class, 'lowStock'])
            ->middleware('staff.permissions:view_inventory')
            ->name('categories.low-stock');
    });
    
    // Inventory overview (Military Admin and Super Admin can view)
    Route::get('/inventory', [InventoryController::class, 'index'])
        ->middleware('staff.permissions:view_inventory')
        ->name('inventory.index');
    
    // Inventory stats API
    Route::get('/inventory/stats', [InventoryController::class, 'getStats'])
        ->middleware('staff.permissions:view_inventory')
        ->name('inventory.stats');
    
    // Low stock and inspection routes (viewers can see these)
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock'])
        ->middleware('staff.permissions:view_inventory')
        ->name('inventory.low-stock');
    
    // CREATE routes (super admin only) - specific routes before parameterized
    Route::get('/inventory/create', [InventoryController::class, 'create'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.create');
    
    Route::post('/inventory', [InventoryController::class, 'store'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.store');
    
    // Assignment routes (check-in/check-out)
    Route::get('/inventory/assignments', [InventoryAssignmentController::class, 'index'])
        ->middleware('staff.permissions:view_inventory')
        ->name('inventory.assignments.index');
    
    Route::get('/inventory/assignments/overdue', [InventoryAssignmentController::class, 'overdue'])
        ->middleware('staff.permissions:view_inventory')
        ->name('inventory.assignments.overdue');
    
    // Assignment management (super admin only)
    Route::get('/inventory/assignments/create', [InventoryAssignmentController::class, 'create'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.assignments.create');
    
    Route::post('/inventory/assignments', [InventoryAssignmentController::class, 'store'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.assignments.store');
    
    Route::get('/inventory/assignments/{assignment}', [InventoryAssignmentController::class, 'show'])
        ->middleware('staff.permissions:view_inventory')
        ->name('inventory.assignments.show');
    
    Route::get('/inventory/assignments/{assignment}/return', [InventoryAssignmentController::class, 'returnForm'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.assignments.return');
    
    Route::post('/inventory/assignments/{assignment}/return', [InventoryAssignmentController::class, 'processReturn'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.assignments.process-return');
    
    Route::post('/inventory/assignments/{assignment}/mark-lost', [InventoryAssignmentController::class, 'markAsLost'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.assignments.mark-lost');
    
    Route::post('/inventory/assignments/{assignment}/send-reminder', [InventoryAssignmentController::class, 'sendReminder'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.assignments.send-reminder');
    
    Route::post('/inventory/assignments/bulk-return', [InventoryAssignmentController::class, 'bulkReturn'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.assignments.bulk-return');
    
    // Inventory item management (parameterized routes after specific ones)
    Route::get('/inventory/{item}', [InventoryController::class, 'show'])
        ->middleware('staff.permissions:view_inventory')
        ->name('inventory.show');
    
    Route::get('/inventory/{item}/edit', [InventoryController::class, 'edit'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.edit');
    
    Route::put('/inventory/{item}', [InventoryController::class, 'update'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.update');
    
    Route::delete('/inventory/{item}', [InventoryController::class, 'destroy'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.destroy');
    
    // Inventory quantity adjustments (super admin only)
    Route::post('/inventory/{item}/adjust-quantity', [InventoryController::class, 'adjustQuantity'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.adjust-quantity');
    
    Route::post('/inventory/{item}/generate-barcode', [InventoryController::class, 'generateBarcode'])
        ->middleware('staff.permissions:manage_inventory')
        ->name('inventory.generate-barcode');
});
    

require __DIR__.'/auth.php';