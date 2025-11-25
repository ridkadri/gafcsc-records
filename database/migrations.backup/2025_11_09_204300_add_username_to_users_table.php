<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // First add as nullable
            $table->string('username')->nullable()->after('email');
            $table->timestamp('password_changed_at')->nullable()->after('password');
        });

        // Set default usernames and passwords for existing users
        \App\Models\User::whereNull('username')->each(function ($user) {
            $user->update([
                'username' => $this->generateUsername($user),
                'password' => Hash::make('gafcsc@123'), // Set default password
                // Leave password_changed_at as NULL to force change on first login
            ]);
        });

        // Now make username not null and unique
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'password_changed_at']);
        });
    }

    /**
     * Generate a username from user's name
     */
    private function generateUsername($user): string
    {
        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $user->name));
        $username = $baseUsername;
        $counter = 1;

        // Ensure uniqueness
        while (\App\Models\User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
};