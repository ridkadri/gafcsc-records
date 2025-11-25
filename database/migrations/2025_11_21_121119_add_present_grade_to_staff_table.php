<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'present_grade')) {
                $table->string('present_grade')->nullable();
            }
            if (!Schema::hasColumn('staff', 'last_promotion_date')) {
                $table->date('last_promotion_date')->nullable();
            }
            if (!Schema::hasColumn('staff', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['present_grade', 'last_promotion_date', 'date_of_birth']);
        });
    }
};
