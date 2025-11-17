<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUsersForExistingStaffSeederFixed extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates viewer user accounts for all staff members
     * who don't already have user accounts.
     */
    public function run(): void
    {
        $this->command->info('Creating user accounts for staff without users...');
        
        // Get all staff who don't have user accounts yet
        $staffWithoutUsers = Staff::whereDoesntHave('user')->get();
        
        $this->command->info("Found {$staffWithoutUsers->count()} staff members without user accounts.");
        
        $created = 0;
        $skipped = 0;
        
        foreach ($staffWithoutUsers as $staff) {
            // Skip if no date of birth (required for login)
            if (!$staff->date_of_birth) {
                $this->command->warn("⚠️  Skipping {$staff->name} - No date of birth");
                $skipped++;
                continue;
            }
            
            // Create user account
            try {
                User::create([
                    'name' => $staff->name,
                    'email' => null, // Optional - they login with DOB
                    'password' => Hash::make('dummy_password_never_used'), // Dummy password (they login with DOB)
                    'role' => User::ROLE_VIEWER,
                    'staff_id' => $staff->id,
                    'date_of_birth' => $staff->date_of_birth,
                ]);
                
                $this->command->info("✅ Created user for: {$staff->name}");
                $created++;
                
            } catch (\Exception $e) {
                $this->command->error("❌ Failed to create user for {$staff->name}: {$e->getMessage()}");
                $skipped++;
            }
        }
        
        $this->command->info("\n=== SUMMARY ===");
        $this->command->info("✅ Created: {$created} user accounts");
        if ($skipped > 0) {
            $this->command->warn("⚠️  Skipped: {$skipped} staff members");
        }
        $this->command->info("\n🎉 Done! All staff with DOB now have user accounts.");
        $this->command->info("ℹ️  Note: Password is set but NEVER used - they login with name + DOB only");
        
        // Show staff without DOB
        $staffWithoutDOB = Staff::whereNull('date_of_birth')->get();
        if ($staffWithoutDOB->count() > 0) {
            $this->command->warn("\n⚠️  WARNING: {$staffWithoutDOB->count()} staff members have no date of birth:");
            foreach ($staffWithoutDOB as $staff) {
                $this->command->warn("   - {$staff->name} (ID: {$staff->id})");
            }
            $this->command->info("\nPlease add date of birth for these staff members to enable their login.");
        }
    }
}