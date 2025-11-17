<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // HOD Dashboard - show subordinates
        if ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            // Get the staff record for this user
            $hodStaff = $user->staff;
            
            if ($hodStaff && $hodStaff->is_hod) {
                $department = $hodStaff->department;
                $subordinates = Staff::where('head_of_department_id', $hodStaff->id)->get();
                
                // Get statistics
                $totalSubordinates = $subordinates->count();
                $militaryCount = $subordinates->where('type', 'military')->count();
                $civilianCount = $subordinates->where('type', 'civilian')->count();
                
                return view('dashboard', compact(
                    'subordinates', 
                    'department', 
                    'totalSubordinates', 
                    'militaryCount', 
                    'civilianCount'
                ));
            }
            
            // If HOD but no staff record, show empty
            return view('dashboard', [
                'subordinates' => collect(),
                'department' => null,
                'totalSubordinates' => 0,
                'militaryCount' => 0,
                'civilianCount' => 0
            ]);
        }
        
        // Admin/Super Admin/Other Roles Dashboard
        if ($user->hasAdminPrivileges()) {
            $totalStaff = Staff::count();
            $militaryStaff = Staff::where('type', 'military')->count();
            $civilianStaff = Staff::where('type', 'civilian')->count();
            $totalHods = Staff::where('is_hod', true)->count();
            
            $recentStaff = Staff::latest()->take(5)->get();
            
            return view('dashboard', compact(
                'totalStaff', 
                'militaryStaff', 
                'civilianStaff', 
                'totalHods',
                'recentStaff'
            ));
        }
        
        // Role-specific dashboards
        if ($user->canViewMilitaryStaff() && !$user->canViewCivilianStaff()) {
            $staff = Staff::where('type', 'military')->get();
            $staffType = 'military';
        } elseif ($user->canViewCivilianStaff() && !$user->canViewMilitaryStaff()) {
            $staff = Staff::where('type', 'civilian')->get();
            $staffType = 'civilian';
        } elseif ($user->canViewStaff()) {
            $staff = Staff::all();
            $staffType = 'all';
        } else {
            // Viewer role - limited view
            $staff = collect();
            $staffType = 'none';
        }
        
        return view('dashboard', compact('staff', 'staffType'));
    }
}