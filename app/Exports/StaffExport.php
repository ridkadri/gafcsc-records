<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StaffExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $search;
    protected $type;
    protected $department;
    protected $rank;
    protected $deployment;

    public function __construct($search = null, $type = null, $department = null, $rank = null, $deployment = null)
    {
        $this->search = $search;
        $this->type = $type;
        $this->department = $department;
        $this->rank = $rank;
        $this->deployment = $deployment;
    }

    public function query()
    {
        $query = Staff::query();
        
        $user = auth()->user();
        
        // Apply role-based filtering
        if ($user->isHeadOfDepartment() && !$user->hasAdminPrivileges()) {
            $subordinateIds = $user->getSubordinates()->pluck('id');
            $query->whereIn('id', $subordinateIds);
        } elseif ($user->isChiefClerk()) {
            $query->where('type', 'military');
        } elseif ($user->isCapo() || $user->isPeo()) {
            $query->where('type', 'civilian');
        }
        
        // Apply filters
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('service_number', 'like', "%{$this->search}%");
            });
        }
        
        if ($this->type) {
            $query->where('type', $this->type);
        }
        
        if ($this->department) {
            $query->where('department', $this->department);
        }
        
        if ($this->rank) {
            $query->where(function($q) {
                $q->where('rank', $this->rank)
                  ->orWhere('present_grade', $this->rank);
            });
        }
        
        if ($this->deployment) {
            $query->where(function($q) {
                $q->where('deployment', $this->deployment)
                  ->orWhere('location', $this->deployment);
            });
        }
        
        return $query->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'Staff ID',
            'Service Number',
            'Name',
            'Contact',
            'Type',
            'Rank/Grade',
            'Appointment',
            'Department',
            'Status/Location',
            'Sex',
            'Trade',
            'Arm of Service',
            'Date of Enrollment',
            'Date of Birth',
            'Last Promotion',
            'Staff Category',
            'Date of Employment',
            'Date of Posting',
            'HOD',
            'Is HOD',
        ];
    }

    public function map($staff): array
    {
        return [
            $staff->staff_id,
            $staff->service_number,
            $staff->name,
            $staff->contact,
            ucfirst($staff->type),
            $staff->isMilitary() ? $staff->rank : $staff->present_grade,
            $staff->appointment,
            $staff->department,
            $staff->isMilitary() ? $staff->deployment : $staff->location,
            $staff->sex,
            $staff->trade,
            $staff->arm_of_service,
            $staff->date_of_enrollment ? $staff->date_of_enrollment->format('Y-m-d') : '',
            $staff->date_of_birth ? $staff->date_of_birth->format('Y-m-d') : '',
            $staff->last_promotion_date ? $staff->last_promotion_date->format('Y-m-d') : '',
            $staff->staff_category,
            $staff->date_of_employment ? $staff->date_of_employment->format('Y-m-d') : '',
            $staff->date_of_posting ? $staff->date_of_posting->format('Y-m-d') : '',
            $staff->headOfDepartment ? $staff->headOfDepartment->name : '',
            $staff->is_hod ? 'Yes' : 'No',
        ];
    }
}