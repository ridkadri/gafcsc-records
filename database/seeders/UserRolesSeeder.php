<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Set the first user as admin
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->update(['role' => 'admin']);
        }

        // Set all other users as viewers
        User::where('id', '!=', $firstUser?->id)->update(['role' => 'viewer']);
        
        // Or create a specific admin user
        // User::create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('password'),
        //     'role' => 'admin',
        // ]);
    }
}