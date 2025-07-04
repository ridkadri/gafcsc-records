<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

// Add this at the top of routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'app' => config('app.name')
    ]);
});

// Default welcome page
Route::get('/', function () {
    return view('welcome');
});

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

// User Management routes (Admin only)
Route::middleware(['auth', 'verified', 'staff.permissions:manage'])->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
});

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

require __DIR__.'/auth.php';