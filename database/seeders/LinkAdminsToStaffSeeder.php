<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LinkAdminsToStaffSeeder extends Seeder
{
    /**
     * Link all admin users to their staff profiles.
     * Creates staff profiles if they don't exist.
     */
    public function run(): void
    {
        $this->command->info('🔗 Linking admin users to staff profiles...');
        
        // Get all users who don't have staff_id
        $usersWithoutStaff = User::whereNull('staff_id')->get();
        
        if ($usersWithoutStaff->isEmpty()) {
            $this->command->info('✅ All users already have staff profiles!');
            return;
        }
        
        $linked = 0;
        $created = 0;
        
        foreach ($usersWithoutStaff as $user) {
            // Try to find existing staff by name
            $staff = Staff::where('name', 'LIKE', $user->name)->first();
            
            if ($staff) {
                // Link existing staff to user
                $user->update(['staff_id' => $staff->id]);
                
                // Update staff DOB if user has one
                if ($user->date_of_birth && !$staff->date_of_birth) {
                    $staff->update(['date_of_birth' => $user->date_of_birth]);
                }
                
                $this->command->info("✓ Linked {$user->name} to existing staff record (ID: {$staff->id})");
                $linked++;
            } else {
                // Create new staff profile for admin
                $staffData = [
                    'name' => $user->name,
                    'service_number' => 'ADMIN-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'type' => 'civilian', // Default to civilian for admins
                    'date_of_birth' => $user->date_of_birth,
                    'department' => $this->getDepartmentForRole($user->role),
                    'present_grade' => $this->getGradeForRole($user->role),
                    'location' => 'GAFCSC Headquarters',
                    'date_of_employment' => now(),
                    'job_description' => $this->getJobDescriptionForRole($user->role),
                ];
                
                $staff = Staff::create($staffData);
                $user->update(['staff_id' => $staff->id]);
                
                $this->command->info("✓ Created staff profile for {$user->name} (ID: {$staff->id})");
                $created++;
            }
        }
        
        $this->command->info('');
        $this->command->info("✅ Complete!");
        $this->command->info("   Linked to existing: {$linked}");
        $this->command->info("   Created new: {$created}");
        $this->command->info("   Total processed: " . ($linked + $created));
    }
    
    /**
     * Get department based on user role
     */
    private function getDepartmentForRole(string $role): string
    {
        return match($role) {
            User::ROLE_SUPER_ADMIN => 'Administration',
            User::ROLE_MILITARY_ADMIN => 'Military Administration',
            User::ROLE_CHIEF_CLERK => 'Military Records',
            User::ROLE_CAPO => 'Civilian Personnel',
            User::ROLE_PEO => 'Personnel & Establishment',
            default => 'General Administration',
        };
    }
    
    /**
     * Get grade based on user role
     */
    private function getGradeForRole(string $role): string
    {
        return match($role) {
            User::ROLE_SUPER_ADMIN => 'COMMANDANT',
            User::ROLE_MILITARY_ADMIN => 'MILITARY ADMINISTRATOR',
            User::ROLE_CHIEF_CLERK => 'CHIEF CLERK',
            User::ROLE_CAPO => 'CAPO',
            User::ROLE_PEO => 'PEO',
            default => 'ADMIN OFFR',
        };
    }
    
    /**
     * Get job description based on user role
     */
    private function getJobDescriptionForRole(string $role): string
    {
        return match($role) {
            User::ROLE_SUPER_ADMIN => 'Overall administration and management of GAFCSC',
            User::ROLE_MILITARY_ADMIN => 'Military personnel and operations management',
            User::ROLE_CHIEF_CLERK => 'Military records and documentation',
            User::ROLE_CAPO => 'Civilian personnel administration',
            User::ROLE_PEO => 'Personnel and establishment operations',
            default => 'Administrative duties',
        };
    }
}