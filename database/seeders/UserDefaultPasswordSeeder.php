<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserDefaultPasswordSeeder extends Seeder
{
    public function run(): void
    {
        // Set default password for any users without a password_changed_at timestamp
        User::whereNull('password_changed_at')->update([
            'password' => Hash::make('gafcsc@123'),
        ]);

        $this->command->info('Default passwords set for users who need to change them.');
    }
}