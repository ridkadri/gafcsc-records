<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $search;
    protected $department;
    protected $rank;
    protected $appointment;

    public function __construct($search = null, $department = null, $rank = null, $appointment = null)
    {
        $this->search = $search;
        $this->department = $department;
        $this->rank = $rank;
        $this->appointment = $appointment;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Staff::query();
        
        // Apply same filters as the index page
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('service_number', 'like', "%{$this->search}%")
                  ->orWhere('rank', 'like', "%{$this->search}%")
                  ->orWhere('appointment', 'like', "%{$this->search}%")
                  ->orWhere('department', 'like', "%{$this->search}%");
        }
        
        if ($this->department) {
            $query->where('department', $this->department);
        }
        
        if ($this->rank) {
            $query->where('rank', $this->rank);
        }
        
        if ($this->appointment) {
            $query->where('appointment', $this->appointment);
        }
        
        return $query->orderBy('name')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Service Number',
            'Full Name',
            'Rank/Title',
            'Appointment',
            'Department',
            'Date Added',
        ];
    }

    /**
     * @param Staff $staff
     * @return array
     */
    public function map($staff): array
    {
        return [
            $staff->service_number,
            $staff->name,
            $staff->rank ?? 'Not Specified',
            $staff->appointment ?? 'Not Specified',
            $staff->department,
            $staff->created_at->format('Y-m-d'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}