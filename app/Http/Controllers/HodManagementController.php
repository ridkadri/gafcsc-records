<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HodManagementController extends Controller
{
    /**
     * Display HOD management dashboard
     */
    public function index()
    {
        $hods = Staff::headsOfDepartment()->with('user', 'subordinates')->get();
        $departments = config('departments.departments', []);
        
        // Get staff who are not HODs and can potentially become HODs
        $availableStaff = Staff::where('is_hod', false)->get();

        return view('hod-management.index', compact('hods', 'departments', 'availableStaff'));
    }

    /**
     * Show form to assign HOD role
     */
    public function create()
    {
        $staff = Staff::where('is_hod', false)
                     ->whereNotNull('department')
                     ->where('department', '!=', '')
                     ->get();

        $departments = config('departments.departments', []);

        return view('hod-management.create', compact('staff', 'departments'));
    }

    /**
     * Assign HOD role to staff member
     * Automatically assigns all staff in the same department to this HOD
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);

        $staff = Staff::findOrFail($request->staff_id);
        
        // Check if staff has a department
        if (!$staff->department) {
            return redirect()->back()
                           ->withErrors(['staff_id' => 'Selected staff must have a department assigned before becoming HOD.'])
                           ->withInput();
        }
        
        // Check if there's already an HOD for this department
        $existingHod = Staff::where('is_hod', true)
                           ->where('department', $staff->department)
                           ->where('id', '!=', $staff->id)
                           ->first();
        
        if ($existingHod) {
            return redirect()->back()
                           ->withErrors(['staff_id' => "There is already an HOD for {$staff->department} department ({$existingHod->name}). Remove them first before assigning a new HOD."])
                           ->withInput();
        }
        
        // Update staff as HOD
        $staff->update(['is_hod' => true]);
        
        // MANUALLY assign all staff in the same department to this HOD
        // (The model event might not catch existing staff)
        $updatedCount = Staff::where('department', $staff->department)
                             ->where('id', '!=', $staff->id)
                             ->where('is_hod', false)
                             ->update(['head_of_department_id' => $staff->id]);
        
        // Create or update user account
        if ($staff->user) {
            // If user can have multiple roles (Admin/Super Admin), keep their role
            if (!$staff->user->canHaveMultipleRoles()) {
                $staff->user->update(['role' => User::ROLE_HOD]);
            }
        } else {
            // Create new user account with HOD role
            User::create([
                'name' => $staff->name,
                'username' => strtolower(str_replace(' ', '.', $staff->name)),
                'password' => bcrypt('gafcsc@123'),
                'role' => User::ROLE_HOD,
                'staff_id' => $staff->id,
                'password_changed_at' => null,
            ]);
        }

        $subordinatesCount = Staff::where('department', $staff->department)
                                  ->where('id', '!=', $staff->id)
                                  ->count();

        return redirect()->route('hod-management.index')
                        ->with('success', "HOD role assigned successfully! {$staff->name} now manages {$updatedCount} staff in {$staff->department} department.");
    }

    /**
     * Show HOD details with their subordinates
     */
    public function show(Staff $hod)
    {
        if (!$hod->is_hod) {
            return redirect()->route('hod-management.index')
                             ->with('error', 'Selected staff is not a HOD.');
        }

        $subordinates = $hod->subordinates()->with('user')->get();
        $departmentStaff = Staff::where('department', $hod->department)
                               ->where('id', '!=', $hod->id)
                               ->get();

        return view('hod-management.show', compact('hod', 'subordinates', 'departmentStaff'));
    }

    /**
     * Remove HOD role
     * Automatically removes all subordinate relationships
     */
    public function destroy(Staff $hod)
    {
        if (!$hod->is_hod) {
            return redirect()->route('hod-management.index')
                             ->with('error', 'Selected staff is not a HOD.');
        }

        $department = $hod->department;
        $subordinatesCount = $hod->subordinates()->count();

        // Remove HOD role from staff (this will automatically clear subordinate relationships via model events)
        $hod->update(['is_hod' => false]);
        
        // Update user role to viewer if exists and they're not an admin
        if ($hod->user && !$hod->user->canHaveMultipleRoles()) {
            $hod->user->update(['role' => User::ROLE_VIEWER]);
        }

        return redirect()->route('hod-management.index')
                         ->with('success', "HOD role removed from {$hod->name}. {$subordinatesCount} staff in {$department} department no longer have an assigned HOD.");
    }

    /**
     * Reassign department for HOD (which will automatically reassign all subordinates)
     */
    public function updateDepartment(Request $request, Staff $hod)
    {
        if (!$hod->is_hod) {
            return redirect()->route('hod-management.index')
                             ->with('error', 'Selected staff is not a HOD.');
        }

        $request->validate([
            'department' => 'required|string|max:255',
        ]);

        $oldDepartment = $hod->department;
        $newDepartment = $request->department;

        // Check if there's already an HOD for the new department
        $existingHod = Staff::where('is_hod', true)
                           ->where('department', $newDepartment)
                           ->where('id', '!=', $hod->id)
                           ->first();

        if ($existingHod) {
            return redirect()->back()
                           ->withErrors(['department' => "There is already an HOD for {$newDepartment} department ({$existingHod->name})."])
                           ->withInput();
        }

        // Update department (model events will handle reassigning subordinates)
        $hod->update(['department' => $newDepartment]);

        // Clear subordinates from old department
        Staff::where('department', $oldDepartment)
             ->where('head_of_department_id', $hod->id)
             ->update(['head_of_department_id' => null]);

        // Assign subordinates from new department
        Staff::where('department', $newDepartment)
             ->where('id', '!=', $hod->id)
             ->update(['head_of_department_id' => $hod->id]);

        return redirect()->route('hod-management.index')
                         ->with('success', "Department changed from {$oldDepartment} to {$newDepartment}. Staff have been automatically reassigned.");
    }
}