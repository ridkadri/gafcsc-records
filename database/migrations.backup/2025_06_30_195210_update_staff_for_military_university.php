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
            // Add appointment column for military/civilian positions
            $table->string('appointment')->nullable()->after('rank');
            
            // Rename office to department
            $table->renameColumn('office', 'department');
            
            // Rename staff_id to service_number for clarity
            $table->renameColumn('staff_id', 'service_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('appointment');
            $table->renameColumn('department', 'office');
            $table->renameColumn('service_number', 'staff_id');
        });
    }
};