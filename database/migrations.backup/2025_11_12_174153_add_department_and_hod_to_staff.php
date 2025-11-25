<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // Only add department if it doesn't exist
            if (!Schema::hasColumn('staff', 'department')) {
                $table->string('department')->nullable()->after('deployment');
                $table->index('department'); // Add index for faster queries
            }
            
            // Ensure HOD columns exist
            if (!Schema::hasColumn('staff', 'is_hod')) {
                $table->boolean('is_hod')->default(false)->after('department');
            }
            
            if (!Schema::hasColumn('staff', 'head_of_department_id')) {
                $table->foreignId('head_of_department_id')
                      ->nullable()
                      ->after('is_hod')
                      ->constrained('staff')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            if (Schema::hasColumn('staff', 'head_of_department_id')) {
                $table->dropForeign(['head_of_department_id']);
                $table->dropColumn('head_of_department_id');
            }
            
            if (Schema::hasColumn('staff', 'is_hod')) {
                $table->dropColumn('is_hod');
            }
            
            if (Schema::hasColumn('staff', 'department')) {
                $table->dropIndex(['department']);
                $table->dropColumn('department');
            }
        });
    }
};