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
        
        // Search functionality - includes all fields
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('service_number', 'like', "%{$search}%")
                  ->orWhere('rank', 'like', "%{$search}%")
                  ->orWhere('appointment', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }
        
        // Filter by department
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        // Filter by rank
        if ($request->has('rank') && $request->rank != '') {
            $query->where('rank', $request->rank);
        }
        
        // Filter by location
        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }
        
        $staff = $query->orderBy('name')->paginate(10);
        $departments = Staff::getAllDepartments();
        $ranks = Staff::getAllRanks();
        $appointments = Staff::getAllAppointments();
        $locations = Staff::getAllLocations();
        
        return view('staff.index', compact('staff', 'departments', 'ranks', 'appointments', 'locations'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $existingRanks = Staff::getAllRanks();
        $existingAppointments = Staff::getAllAppointments();
        $existingDepartments = Staff::getAllDepartments();
        $existingLocations = Staff::getAllLocations();
        return view('staff.create', compact('existingRanks', 'existingAppointments', 'existingDepartments', 'existingLocations'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service_number' => 'required|string|max:50|unique:staff',
            'rank' => 'nullable|string|max:255',
            'appointment' => 'nullable|string|max:255',
            'staff_type' => 'required|in:military,civilian',
            'department' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Staff::create($request->all());

        return redirect()->route('staff.index')
                        ->with('success', 'Staff member added successfully!');
    }

    /**
     * Display the specified staff member.
     */
    public function show(Staff $staff)
    {
        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Staff $staff)
    {
        $existingRanks = Staff::getAllRanks();
        $existingAppointments = Staff::getAllAppointments();
        $existingDepartments = Staff::getAllDepartments();
        $existingLocations = Staff::getAllLocations();
        return view('staff.edit', compact('staff', 'existingRanks', 'existingAppointments', 'existingDepartments', 'existingLocations'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service_number' => 'required|string|max:50|unique:staff,service_number,' . $staff->id,
            'rank' => 'nullable|string|max:255',
            'appointment' => 'nullable|string|max:255',
            'staff_type' => 'required|in:military,civilian',
            'department' => 'required|string|max:255',
        ]);

        $staff->update($request->all());

        return redirect()->route('staff.index')
                        ->with('success', 'Staff member updated successfully!');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('staff.index')
                        ->with('success', 'Staff member deleted successfully!');
    }

    /**
     * Export staff to CSV format.
     */
    public function exportCsv(Request $request)
    {
        $filename = 'university_staff_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        return Excel::download(
            new StaffExport($request->search, $request->department, $request->rank, $request->appointment), 
            $filename,
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    /**
     * Export staff to Excel format.
     */
    public function exportExcel(Request $request)
    {
        $filename = 'university_staff_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        
        return Excel::download(
            new StaffExport($request->search, $request->department, $request->rank, $request->appointment), 
            $filename
        );
    }

    /**
     * Export staff to PDF format.
     */
    public function exportPdf(Request $request)
    {
        $query = Staff::query();
        
        // Apply same filters as the index page
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('service_number', 'like', "%{$search}%")
                  ->orWhere('rank', 'like', "%{$search}%")
                  ->orWhere('appointment', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
        }
        
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        if ($request->has('rank') && $request->rank != '') {
            $query->where('rank', $request->rank);
        }
        
        if ($request->has('appointment') && $request->appointment != '') {
            $query->where('appointment', $request->appointment);
        }
        
        $staff = $query->orderBy('name')->get();
        $departments = Staff::getAllDepartments();
        $ranks = Staff::getAllRanks();
        $appointments = Staff::getAllAppointments();
        
        // Additional stats for PDF
        $stats = [
            'total_staff' => $staff->count(),
            'total_departments' => $staff->pluck('department')->unique()->count(),
            'staff_with_ranks' => $staff->whereNotNull('rank')->where('rank', '!=', '')->count(),
            'staff_with_appointments' => $staff->whereNotNull('appointment')->where('appointment', '!=', '')->count(),
            'military_civilian' => Staff::getMilitaryCivilianStats(),
            'department_breakdown' => $staff->groupBy('department')->map->count(),
            'appointment_breakdown' => $staff->filter(function($s) { return !empty($s->appointment); })
                                    ->groupBy('appointment')->map->count(),
        ];
        
        $pdf = Pdf::loadView('staff.export-pdf', compact('staff', 'departments', 'ranks', 'appointments', 'stats'))
                  ->setPaper('a4', 'landscape');
        
        $filename = 'university_staff_export_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Get staff statistics for dashboard or reports.
     */
    public function getStats()
    {
        $stats = [
            'total_staff' => Staff::count(),
            'total_offices' => Staff::distinct('office')->count(),
            'staff_with_ranks' => Staff::withRank()->count(),
            'staff_without_ranks' => Staff::withoutRank()->count(),
            'office_stats' => Staff::getOfficeStats(),
            'rank_stats' => Staff::getRankStats(),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk update ranks (for admin use).
     */
    public function bulkUpdateRanks(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:staff,id',
            'updates.*.rank' => 'nullable|string|max:255',
        ]);

        foreach ($request->updates as $update) {
            Staff::where('id', $update['id'])->update(['rank' => $update['rank']]);
        }

        return redirect()->route('staff.index')
                        ->with('success', 'Staff ranks updated successfully!');
    }

    /**
     * Get autocomplete suggestions for ranks.
     */
    public function getRankSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        $suggestions = Staff::where('rank', 'like', "%{$query}%")
                           ->whereNotNull('rank')
                           ->where('rank', '!=', '')
                           ->distinct()
                           ->pluck('rank')
                           ->take(10);

        return response()->json($suggestions);
    }
}