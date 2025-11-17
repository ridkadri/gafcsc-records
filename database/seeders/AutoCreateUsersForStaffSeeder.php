<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AutoCreateUsersForStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating user accounts for all staff...');
        
        $staffWithoutUsers = Staff::whereDoesntHave('user')->get();
        $created = 0;
        $skipped = 0;
        
        foreach ($staffWithoutUsers as $staff) {
            // Skip if no date of birth (required for login)
            if (!$staff->date_of_birth) {
                $this->command->warn("Skipped {$staff->name} - No date of birth");
                $skipped++;
                continue;
            }
            
            // Check if email exists (if staff has email somehow)
            $email = null;
            if (isset($staff->email) && $staff->email) {
                // Check for duplicate email
                if (User::where('email', $staff->email)->exists()) {
                    $email = null; // Will be set to null if duplicate
                } else {
                    $email = $staff->email;
                }
            }
            
            // Create user account
            $user = User::create([
                'name' => $staff->name,
                'email' => $email, // Can be null
                'password' => Hash::make('default_password'), // Won't be used for DOB login
                'date_of_birth' => $staff->date_of_birth,
                'staff_id' => $staff->id,
                'role' => User::ROLE_VIEWER, // Default role - can only view own profile
                'email_verified_at' => now(),
            ]);
            
            $this->command->info("Created user for: {$staff->name}");
            $created++;
        }
        
        $this->command->info('');
        $this->command->info("✅ Created {$created} user accounts");
        if ($skipped > 0) {
            $this->command->warn("⚠️  Skipped {$skipped} staff (missing date of birth)");
        }
        $this->command->info('');
        $this->command->info('All staff can now login with:');
        $this->command->info('- Name: Their full name (case insensitive)');
        $this->command->info('- Date of Birth: YYYY-MM-DD format');
        $this->command->info('');
        $this->command->info('Staff have "viewer" role by default (can only access their profile)');
    }
}