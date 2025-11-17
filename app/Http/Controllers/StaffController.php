<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Exports\StaffExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index(Request $request)
    {
        $query = Staff::query();
        
        // ROLE-BASED FILTERING: Filter by user role FIRST
        $user = auth()->user();
        
        // SPECIAL HANDLING FOR HOD + ADMIN: Check if viewing "My Department" tab
        if ($user->isHeadOfDepartment() && $user->hasAdminPrivileges() && $request->view === 'department') {
            // HOD+Admin viewing their department only
            $hodStaff = $user->staff;
            
            if ($hodStaff && $hodStaff->is_hod) {
                // Get all subordinates who report to this HOD (excluding the HOD themselves)
                $subordinateIds = Staff::where('head_of_department_id', $hodStaff->id)->pluck('id');
                $query->whereIn('id', $subordinateIds);
            } else {
                // If somehow user has HOD role but staff record isn't HOD, show nothing
                $query->whereRaw('1 = 0'); // Returns empty result
            }
        }
        // HOD-SPECIFIC FILTERING: HODs WITHOUT admin privileges see only their subordinates
        elseif ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            // Get the staff record for this user
            $hodStaff = $user->staff;
            
            if ($hodStaff && $hodStaff->is_hod) {
                // Get all subordinates who report to this HOD (excluding the HOD themselves)
                $subordinateIds = Staff::where('head_of_department_id', $hodStaff->id)->pluck('id');
                
                // DON'T include the HOD themselves - they shouldn't see their own record
                $query->whereIn('id', $subordinateIds);
            } else {
                // If somehow user has HOD role but staff record isn't HOD, show nothing
                $query->whereRaw('1 = 0'); // Returns empty result
            }
        } elseif ($user->isChiefClerk()) {
            // Chief Clerk ONLY sees military staff
            $query->where('type', 'military');
        } elseif ($user->isCapo() || $user->isPeo()) {
            // CAPO/PEO ONLY see civilian staff
            $query->where('type', 'civilian');
        }
        // Super Admin, Admin and Military Admin see all staff
        // HOD+Admin on "All Staff" tab also see all authorized staff
        
        // Search functionality - includes all fields
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('service_number', 'like', "%{$search}%")
                  ->orWhere('rank', 'like', "%{$search}%")
                  ->orWhere('present_grade', 'like', "%{$search}%")
                  ->orWhere('trade', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('appointment', 'like', "%{$search}%");
            });
        }
        
        // Filter by type (military/civilian) - respecting role restrictions
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        // Filter by department
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        // Filter by rank (military) OR present_grade (civilian)
        if ($request->has('rank') && $request->rank != '') {
            $query->where(function($q) use ($request) {
                $q->where('rank', $request->rank)
                  ->orWhere('present_grade', $request->rank);
            });
        }
        
        // Filter by deployment (military) OR location (civilian)
        if ($request->has('deployment') && $request->deployment != '') {
            $query->where(function($q) use ($request) {
                $q->where('deployment', $request->deployment)
                  ->orWhere('location', $request->deployment);
            });
        }
        
        // Filter by location (civilian)
        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }
        
        $staff = $query->orderBy('name')->paginate(15);
        
        // Get filter options
        $departments = Staff::getAllDepartments();
        $ranks = Staff::getMilitaryRanks();
        $deployments = Staff::getDeploymentOptions();
        $locations = Staff::getAllLocations();
        
        // Get allowed types based on user role
        $types = $this->getTypesForUser($user);
        
        return view('staff.index', compact('staff', 'departments', 'ranks', 'deployments', 'locations', 'types'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $user = auth()->user();
        $allowedTypes = $this->getTypesForUser($user);
        
        // Get departments list from config
        $departments = config('departments.departments', []);
        
        return view('staff.create', compact('allowedTypes', 'departments'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        // Determine validation rules based on type
        if ($request->type === 'military') {
            $validated = $request->validate(Staff::militaryValidationRules());
        } else {
            $validated = $request->validate(Staff::civilianValidationRules());
        }

        // Handle is_hod checkbox
        $validated['is_hod'] = $request->has('is_hod') ? true : false;
        
        // If being set as HOD, check for existing HOD in that department
        if ($validated['is_hod'] && !empty($validated['department'])) {
            $existingHod = Staff::where('department', $validated['department'])
                               ->where('is_hod', true)
                               ->first();
            
            if ($existingHod) {
                return redirect()->back()
                    ->withErrors(['is_hod' => "There is already a Head of Department for {$validated['department']} ({$existingHod->name}). Please uncheck the HOD box or edit the existing HOD first."])
                    ->withInput();
            }
        }

        $staff = Staff::create($validated);

        // AUTO-ASSIGN TO HOD based on department (if not HOD themselves)
        if (!$staff->is_hod) {
            $this->syncHodRelationship($staff);
        }

        // AUTO-CREATE USER ACCOUNT for the staff member
        $this->createUserForStaff($staff);

        $hodMessage = $staff->is_hod ? ' This staff member is now the Head of Department.' : '';

        return redirect()->route('staff.show', $staff)
                        ->with('success', ucfirst($staff->type) . ' staff member added successfully!' . $hodMessage . ' User account created with default password: gafcsc@123');
    }

    /**
     * Display the specified staff member.
     */
    public function show(Staff $staff)
    {
        $user = auth()->user();
        
        // Check if user can view this staff member
        if ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            // HODs can only view their subordinates
            if (!$user->getSubordinates()->contains($staff)) {
                abort(403, 'You do not have permission to view this staff member.');
            }
        } elseif ($user->isChiefClerk() && $staff->type !== 'military') {
            abort(403, 'You can only view military staff.');
        } elseif (($user->isCapo() || $user->isPeo()) && $staff->type !== 'civilian') {
            abort(403, 'You can only view civilian staff.');
        }
        
        // Eager load inventory assignments with related data
        $staff->load([
            'activeInventoryAssignments.item.category',
            'overdueInventoryAssignments'
        ]);
        
        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Staff $staff)
    {
        $user = auth()->user();
        
        // Check editing permissions
        if ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            abort(403, 'HODs cannot edit staff records. Please contact an administrator.');
        }
        
        if ($user->isChiefClerk() && $staff->type !== 'military') {
            abort(403, 'You can only edit military staff.');
        }
        
        if (($user->isCapo() || $user->isPeo()) && $staff->type !== 'civilian') {
            abort(403, 'You can only edit civilian staff.');
        }
        
        // Get departments list from config
        $departments = config('departments.departments', []);
        
        // Get allowed types for the user (for consistency with create form)
        $allowedTypes = $this->getTypesForUser($user);
        
        return view('staff.edit', compact('staff', 'departments', 'allowedTypes'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $user = auth()->user();
        
        // Check editing permissions
        if ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            abort(403, 'HODs cannot edit staff records. Please contact an administrator.');
        }
        
        // Determine validation rules based on type
        if ($request->type === 'military') {
            $validated = $request->validate(Staff::militaryValidationRules($staff->id));
        } else {
            $validated = $request->validate(Staff::civilianValidationRules($staff->id));
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($staff->profile_picture && \Storage::disk('public')->exists($staff->profile_picture)) {
                \Storage::disk('public')->delete($staff->profile_picture);
            }
            
            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        // Handle is_hod checkbox
        $validated['is_hod'] = $request->has('is_hod') ? true : false;
        
        // If being set as HOD, check for existing HOD in that department
        if ($validated['is_hod'] && !empty($validated['department'])) {
            $existingHod = Staff::where('department', $validated['department'])
                               ->where('is_hod', true)
                               ->where('id', '!=', $staff->id)
                               ->first();
            
            if ($existingHod) {
                return redirect()->back()
                    ->withErrors(['is_hod' => "There is already a Head of Department for {$validated['department']} ({$existingHod->name}). Please uncheck the HOD box or remove the existing HOD first."])
                    ->withInput();
            }
        }

        $staff->update($validated);

        // RE-SYNC HOD relationship if department changed or if not HOD
        if (!$staff->is_hod) {
            $this->syncHodRelationship($staff);
        }

        $hodMessage = $staff->is_hod ? ' This staff member is now the Head of Department.' : '';

        return redirect()->route('staff.show', $staff)
                        ->with('success', ucfirst($staff->type) . ' staff member updated successfully!' . $hodMessage);
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(Staff $staff)
    {
        $user = auth()->user();
        
        // Only Super Admin and Admin can delete staff
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'You do not have permission to delete staff.');
        }
        
        // If this staff is an HOD, clear subordinate relationships
        if ($staff->is_hod) {
            Staff::where('head_of_department_id', $staff->id)
                 ->update(['head_of_department_id' => null]);
        }
        
        $staff->delete();

        return redirect()->route('staff.index')
                        ->with('success', 'Staff member deleted successfully!');
    }

    /**
     * Export staff to CSV format.
     */
    public function exportCsv(Request $request)
    {
        $query = Staff::query();
        
        $user = auth()->user();
        
        // Apply role-based filtering for exports
        if ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            $subordinateIds = $user->getSubordinates()->pluck('id');
            $query->whereIn('id', $subordinateIds);
        } elseif ($user->isChiefClerk()) {
            $query->where('type', 'military');
        } elseif ($user->isCapo() || $user->isPeo()) {
            $query->where('type', 'civilian');
        }
        
        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('service_number', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        $staff = $query->orderBy('name')->get();
        
        // Create CSV headers
        $csvData = "Service Number,Name,Type,";
        
        // Add military-specific headers
        $csvData .= "Rank,Sex,Trade,Arm of Service,Deployment,Date of Enrollment,";
        
        // Add civilian-specific headers
        $csvData .= "Present Grade,Date of Employment,Date of Posting,Location,";
        
        // Add common headers
        $csvData .= "Date of Birth,Last Promotion Date,Department\n";
        
        // Add data rows
        foreach ($staff as $member) {
            $csvData .= "\"{$member->service_number}\",";
            $csvData .= "\"{$member->name}\",";
            $csvData .= "\"{$member->type}\",";
            
            // Military fields
            $csvData .= "\"{$member->rank}\",";
            $csvData .= "\"{$member->sex}\",";
            $csvData .= "\"{$member->trade}\",";
            $csvData .= "\"{$member->arm_of_service}\",";
            $csvData .= "\"{$member->deployment}\",";
            $csvData .= "\"" . ($member->date_of_enrollment ? $member->date_of_enrollment->format('Y-m-d') : '') . "\",";
            
            // Civilian fields
            $csvData .= "\"{$member->present_grade}\",";
            $csvData .= "\"" . ($member->date_of_employment ? $member->date_of_employment->format('Y-m-d') : '') . "\",";
            $csvData .= "\"" . ($member->date_of_posting ? $member->date_of_posting->format('Y-m-d') : '') . "\",";
            $csvData .= "\"{$member->location}\",";
            
            // Common fields
            $csvData .= "\"" . ($member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') . "\",";
            $csvData .= "\"" . ($member->last_promotion_date ? $member->last_promotion_date->format('Y-m-d') : '') . "\",";
            $csvData .= "\"{$member->department}\"\n";
        }
        
        $filename = 'gafcsc_staff_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Export staff to Excel format.
     */
    public function exportExcel(Request $request)
    {
        $filename = 'gafcsc_staff_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        
        return Excel::download(
            new StaffExport($request->search, $request->type, $request->department, $request->rank, $request->deployment), 
            $filename
        );
    }

    /**
     * Preview staff PDF before downloading.
     */
    public function previewPdf(Request $request)
    {
        $query = Staff::query();
        
        $user = auth()->user();
        
        // Apply role-based filtering for PDF
        if ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            $subordinateIds = $user->getSubordinates()->pluck('id');
            $query->whereIn('id', $subordinateIds);
        } elseif ($user->isChiefClerk()) {
            $query->where('type', 'military');
        } elseif ($user->isCapo() || $user->isPeo()) {
            $query->where('type', 'civilian');
        }
        
        // Apply same filters as the index page
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('service_number', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        if ($request->has('rank') && $request->rank != '') {
            $query->where('rank', $request->rank);
        }
        
        if ($request->has('deployment') && $request->deployment != '') {
            $query->where('deployment', $request->deployment);
        }
        
        $staff = $query->orderBy('name')->get();
        
        // Separate military and civilian staff
        $militaryStaff = $staff->where('type', 'military');
        $civilianStaff = $staff->where('type', 'civilian');
        
        // Additional stats for PDF
        $stats = [
            'total_staff' => $staff->count(),
            'military_count' => $militaryStaff->count(),
            'civilian_count' => $civilianStaff->count(),
            'total_departments' => $staff->pluck('department')->unique()->filter()->count(),
            'staff_with_ranks' => $staff->whereNotNull('rank')->where('rank', '!=', '')->count(),
            'military_civilian' => Staff::getMilitaryCivilianStats(),
        ];
        
        $pdf = Pdf::loadView('staff.export-pdf', compact('staff', 'militaryStaff', 'civilianStaff', 'stats'))
                  ->setPaper('a4', 'landscape');
        
        // Use stream() instead of download() for preview
        return $pdf->stream('gafcsc_staff_preview.pdf');
    }

    /**
     * Export staff to PDF format.
     */
    public function exportPdf(Request $request)
    {
        $query = Staff::query();
        
        $user = auth()->user();
        
        // Apply role-based filtering for PDF
        if ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            $subordinateIds = $user->getSubordinates()->pluck('id');
            $query->whereIn('id', $subordinateIds);
        } elseif ($user->isChiefClerk()) {
            $query->where('type', 'military');
        } elseif ($user->isCapo() || $user->isPeo()) {
            $query->where('type', 'civilian');
        }
        
        // Apply same filters as the index page
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('service_number', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        if ($request->has('rank') && $request->rank != '') {
            $query->where('rank', $request->rank);
        }
        
        if ($request->has('deployment') && $request->deployment != '') {
            $query->where('deployment', $request->deployment);
        }
        
        $staff = $query->orderBy('name')->get();
        
        // Separate military and civilian staff
        $militaryStaff = $staff->where('type', 'military');
        $civilianStaff = $staff->where('type', 'civilian');
        
        // Additional stats for PDF
        $stats = [
            'total_staff' => $staff->count(),
            'military_count' => $militaryStaff->count(),
            'civilian_count' => $civilianStaff->count(),
            'total_departments' => $staff->pluck('department')->unique()->filter()->count(),
            'staff_with_ranks' => $staff->whereNotNull('rank')->where('rank', '!=', '')->count(),
            'military_civilian' => Staff::getMilitaryCivilianStats(),
        ];
        
        $pdf = Pdf::loadView('staff.export-pdf', compact('staff', 'militaryStaff', 'civilianStaff', 'stats'))
                  ->setPaper('a4', 'landscape');
        
        $filename = 'gafcsc_staff_export_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Get staff statistics for dashboard or reports.
     */
    public function getStats()
    {
        $militaryCivilian = Staff::getMilitaryCivilianStats();
        
        $stats = [
            'total_staff' => Staff::count(),
            'military_staff' => $militaryCivilian['military'],
            'civilian_staff' => $militaryCivilian['civilian'],
            'total_departments' => Staff::getAllDepartments()->count(),
            'on_deployment' => Staff::military()->whereNotNull('deployment')->count(),
            'location_stats' => Staff::getAllLocations()->count(),
            'total_hods' => Staff::where('is_hod', true)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get allowed types based on user role
     */
    private function getTypesForUser($user): array
    {
        if ($user->isChiefClerk()) {
            return ['military' => 'Military'];
        } elseif ($user->isCapo() || $user->isPeo()) {
            return ['civilian' => 'Civilian'];
        }
        // Super Admin, Admin and Military Admin see both
        return ['military' => 'Military', 'civilian' => 'Civilian'];
    }

    /**
     * Auto-create user account for staff member with friendly username format
     */
    private function createUserForStaff(Staff $staff): void
    {
        // Check if user already exists for this staff
        if (\App\Models\User::where('staff_id', $staff->id)->exists()) {
            return;
        }

        // Generate friendly username: firstname.lastname (first 2 name parts)
        $nameParts = explode(' ', trim($staff->name));
        
        if (count($nameParts) >= 2) {
            // Use first and last name
            $firstName = strtolower(preg_replace('/[^a-zA-Z]/', '', $nameParts[0]));
            $lastName = strtolower(preg_replace('/[^a-zA-Z]/', '', end($nameParts)));
            $baseUsername = $firstName . '.' . $lastName;
        } else {
            // Only one name part, use it as is
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z]/', '', $nameParts[0]));
        }
        
        $username = $baseUsername;
        $counter = 1;

        // Ensure username uniqueness
        while (\App\Models\User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        // Create viewer account for the staff member
        \App\Models\User::create([
            'name' => $staff->name,
            'email' => null,
            'username' => $username,
            'password' => \Illuminate\Support\Facades\Hash::make('gafcsc@123'),
            'role' => \App\Models\User::ROLE_VIEWER,
            'staff_id' => $staff->id,
            'password_changed_at' => null,
        ]);
    }

    /**
     * Automatically sync HOD relationship based on department (NEW METHOD)
     * 
     * @param Staff $staff
     * @return void
     */
    private function syncHodRelationship(Staff $staff)
    {
        // Don't assign HOD if staff doesn't have department or if they are an HOD themselves
        if (!$staff->department || $staff->is_hod) {
            return;
        }
        
        // Find HOD for this department
        $hod = Staff::where('is_hod', true)
                   ->where('department', $staff->department)
                   ->where('id', '!=', $staff->id)
                   ->first();
        
        if ($hod) {
            $staff->head_of_department_id = $hod->id;
        } else {
            // No HOD found for this department, clear the relationship
            $staff->head_of_department_id = null;
        }
        
        // Save without triggering events to avoid infinite loops
        $staff->saveQuietly();
    }
}