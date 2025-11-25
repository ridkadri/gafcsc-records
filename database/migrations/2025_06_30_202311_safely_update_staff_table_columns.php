<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only add columns that don't exist
        Schema::table('staff', function (Blueprint $table) {
            // Add service_number if it doesn't exist
            if (!Schema::hasColumn('staff', 'service_number')) {
                $table->string('service_number')->nullable();
            }
            // Add appointment if it doesn't exist
	    if (!Schema::hasColumn('staff', 'appointment')) {
    		$table->string('appointment')->nullable();
	    }


            // Add department if it doesn't exist  
            if (!Schema::hasColumn('staff', 'department')) {
                $table->string('department')->nullable();
            }
        });

        // Copy data from old columns if they exist - SQLite compatible
        if (Schema::hasColumn('staff', 'staff_id') && Schema::hasColumn('staff', 'service_number')) {
            DB::statement('UPDATE staff SET service_number = staff_id WHERE service_number IS NULL');
        }
        
        if (Schema::hasColumn('staff', 'office') && Schema::hasColumn('staff', 'department')) {
            DB::statement('UPDATE staff SET department = office WHERE department IS NULL');
        }

        // Handle missing service_numbers with cross-database compatible syntax
        if (Schema::hasColumn('staff', 'service_number')) {
            // Use CONCAT for better cross-database compatibility
            DB::statement("UPDATE staff SET service_number = CONCAT('TEMP', id) WHERE service_number IS NULL OR service_number = ''");
            
            Schema::table('staff', function (Blueprint $table) {
                $table->string('service_number')->nullable(false)->unique()->change();
            });
        }

        // Handle missing departments
        if (Schema::hasColumn('staff', 'department')) {
            DB::statement("UPDATE staff SET department = 'General' WHERE department IS NULL OR department = ''");
            
            Schema::table('staff', function (Blueprint $table) {
                $table->string('department')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // Only drop columns that we added
            if (Schema::hasColumn('staff', 'service_number')) {
                $table->dropColumn('service_number');
            }
            if (Schema::hasColumn('staff', 'department')) {
                $table->dropColumn('department');
            }
		if (Schema::hasColumn('staff', 'appointment')) {
    		$table->dropColumn('appointment');
	    }
        });
    }
};
