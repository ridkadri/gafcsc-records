<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'sex')) {
                $table->enum('sex', ['Male', 'Female'])->nullable();
            }
            if (!Schema::hasColumn('staff', 'trade')) {
                $table->string('trade')->nullable();
            }
            if (!Schema::hasColumn('staff', 'arm_of_service')) {
                $table->string('arm_of_service')->nullable();
            }
            if (!Schema::hasColumn('staff', 'deployment')) {
                $table->string('deployment')->nullable();
            }
            if (!Schema::hasColumn('staff', 'date_of_enrollment')) {
                $table->date('date_of_enrollment')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['sex', 'trade', 'arm_of_service', 'deployment', 'date_of_enrollment']);
        });
    }
};