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
        Schema::table('acc_inquirys', function (Blueprint $table) {
             $table->string('patient_f_name', 100)->nullable()->after('patient_id');
            $table->string('patient_m_name', 100)->nullable()->after('patient_f_name');
            $table->string('patient_l_name', 100)->nullable()->after('patient_m_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acc_inquirys', function (Blueprint $table) {
             $table->dropColumn(['patient_f_name', 'patient_m_name', 'patient_l_name']);
        });
    }
};
