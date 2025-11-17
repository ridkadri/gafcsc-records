<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            // Add HOD relationship - staff can report to other staff
            $table->foreignId('head_of_department_id')
                  ->nullable()
                  ->constrained('staff') 
                  ->onDelete('set null');
                  
            // Add is_hod flag to identify who are HODs
            $table->boolean('is_hod')->default(false);
        });

        // Update users table to include HOD role
        Schema::table('users', function (Blueprint $table) {
            // This ensures the role column can accept the new HOD value
            $table->string('role')->default('viewer')->change();
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['head_of_department_id']);
            $table->dropColumn(['head_of_department_id', 'is_hod']);
        });
    }
};