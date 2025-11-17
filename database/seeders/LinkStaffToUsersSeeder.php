<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LinkStaffToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates user accounts for all staff members who don't have one yet.
     * Default role: 'viewer' (regular staff who can only view their own profile)
     */
    public function run(): void
    {
        $this->command->info('Starting to link staff to user accounts...');

        // Get all staff IDs that already have user accounts
        $staffIdsWithUsers = User::whereNotNull('staff_id')->pluck('staff_id')->toArray();

        // Get all staff that have date_of_birth and DON'T have a user account yet
        $staffMembers = Staff::whereNotNull('date_of_birth')
            ->whereNotIn('id', $staffIdsWithUsers)
            ->get();

        if ($staffMembers->isEmpty()) {
            $this->command->warn('No staff members without user accounts found.');
            $this->command->info('All staff members already have user accounts!');
            return;
        }

        $this->command->info("Found {$staffMembers->count()} staff members without user accounts.");

        $created = 0;
        $skipped = 0;

        foreach ($staffMembers as $staff) {
            try {
                // Create a user account for this staff member
                // We use a random impossible password since we're using name + DOB for login
                $user = User::create([
                    'name' => $staff->name,
                    'email' => null,
                    'date_of_birth' => $staff->date_of_birth,
                    'staff_id' => $staff->id,
                    'password' => Hash::make(uniqid('DISABLED_', true)), // Dummy password - can't be guessed
                    'role' => User::ROLE_VIEWER, // Default role
                ]);

                $this->command->info("✓ Created user account for: {$staff->name} (Role: viewer)");
                $created++;

            } catch (\Exception $e) {
                $this->command->error("✗ Failed to create user for: {$staff->name}");
                $this->command->error("  Error: " . $e->getMessage());
                $skipped++;
            }
        }

        $this->command->newLine();
        $this->command->info("=== Summary ===");
        $this->command->info("Created: {$created} user accounts");
        if ($skipped > 0) {
            $this->command->warn("Skipped: {$skipped} (see errors above)");
        }
        $this->command->newLine();
        
        $this->command->info('Staff-User linking completed!');
        $this->command->newLine();
        
        $this->command->info('✓ All users can log in with name + date of birth');
        $this->command->info('✓ Password field is set but won\'t be used (name+DOB authentication)');
        $this->command->info('✓ All users have "viewer" role by default');
        $this->command->newLine();
        
        $this->command->info('Next: Promote users to admin/super_admin:');
        $this->command->info('  php artisan tinker');
        $this->command->info('  >>> $user = User::where(\'name\', \'John Doe\')->first();');
        $this->command->info('  >>> $user->role = User::ROLE_ADMIN;');
        $this->command->info('  >>> $user->save();');
    }
}