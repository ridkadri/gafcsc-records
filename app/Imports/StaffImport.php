<?php

namespace App\Imports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class StaffImport implements ToModel, WithStartRow
{
    protected $imported = 0;
    protected $updated = 0;

    public function startRow(): int
    {
        return 14;
    }

    public function model(array $row)
    {
        // Skip empty rows or section headers
        if (empty($row[6]) || !is_numeric($row[0])) {
            return null;
        }

        // Clean service number
        $serviceNumber = str_replace(['GH/', 'GH', ' '], '', trim($row[6]));
        
        // Parse date
        $dateOfEnrollment = null;
        if (!empty($row[10]) && $row[10] !== 'N/A') {
            try {
                if (strpos($row[10], '/') !== false) {
                    $parts = explode('/', trim($row[10]));
                    if (count($parts) === 3) {
                        $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                        $month = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                        $year = strlen($parts[2]) === 2 ? '20' . $parts[2] : $parts[2];
                        $dateOfEnrollment = Carbon::createFromFormat('d/m/Y', "$day/$month/$year");
                    }
                }
            } catch (\Exception $e) {
                // Leave as null
            }
        }

        // Parse department from appointment
        $appointment = trim($row[1] ?? '');
        $department = $this->extractDepartment($appointment);

        // Check if staff exists
        $existingStaff = Staff::where('service_number', $serviceNumber)->first();

        $data = [
            'name' => trim($row[4] ?? ''),
            'rank' => $this->normalizeRank(trim($row[3] ?? '')),
            'appointment' => $appointment,
            'arm_of_service' => trim($row[8] ?? ''),
            'date_of_enrollment' => $dateOfEnrollment,
            'type' => 'military',
            'department' => $department,
            'deployment' => 'On Ground', // Default status
        ];

        if ($existingStaff) {
            $existingStaff->update($data);
            $this->updated++;
            return null;
        }

        $this->imported++;
        return new Staff(array_merge($data, [
            'service_number' => $serviceNumber,
            'sex' => 'Male', // Default
        ]));
    }

    /**
     * Extract department from appointment
     */
    private function extractDepartment($appointment)
    {
        $appointment = strtoupper($appointment);
        
        // Map common appointment keywords to departments
        $departmentMap = [
            'COMDT' => 'Commandant Office',
            'D/COMDT' => 'Deputy Commandant Office',
            'ASCOM' => 'ASCOM',
            'COORD' => 'Coordination',
            'DIR' => 'Directorate',
            'ACADEMIC' => 'Academic',
            'ADMIN' => 'Admin',
            'G1' => 'G1',
            'G2' => 'G2',
            'G3' => 'G3',
            'G4' => 'G4',
            'G5' => 'G5',
            'G6' => 'G6',
            'G7' => 'G7',
            'G8' => 'G8',
            'G9' => 'G9',
            'IT' => 'IT',
            'PROTOCOL' => 'Protocol',
            'REGISTRY' => 'Registry',
            'LIBRARY' => 'Library',
        ];

        foreach ($departmentMap as $keyword => $dept) {
            if (strpos($appointment, $keyword) !== false) {
                return $dept;
            }
        }

        return 'GAFCSC HQ'; // Default
    }

    /**
     * Normalize rank from Excel to abbreviated format
     */
    private function normalizeRank($rank)
    {
        $rank = strtoupper(trim($rank));
        
        // Direct mapping from Excel abbreviations to system abbreviations
        $rankMap = [
            // Excel format → System format
            'BRIG GEN' => 'BRIG GEN',
            'LT GEN' => 'LT GEN',
            'MAJ GEN' => 'MAJ GEN',
            'GENERAL' => 'GEN',
            'COLONEL' => 'COL',
            'LT COLONEL' => 'LT COL',
            'MAJOR' => 'MAJ',
            'CAPTAIN' => 'CAPT',
            'LIEUTENANT' => 'LT',
            '2ND LIEUTENANT' => '2LT',
            
            // Keep Excel abbreviations as-is
            'COL' => 'COL',
            'CAPT' => 'CAPT',
            'LT' => 'LT',
            'MAJ' => 'MAJ',
            '2LT' => '2LT',
        ];

        return $rankMap[$rank] ?? $rank; // If not in map, return as-is
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getUpdated()
    {
        return $this->updated;
    }
}