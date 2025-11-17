<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdminUser extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ridwan Kadri',
            'username' => 'ridkadri',
            'email' => 'ridwan.kadri@gafcscmil.edu.gh',
            'password' => Hash::make('gafcsc@123'),
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $this->command->info('Super admin user created:');
        $this->command->info('Username: ridkadri');
        $this->command->info('Password: gafcsc@123');
    }
}