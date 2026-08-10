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
        Schema::table('patient_medicine_treatments', function (Blueprint $table) {
            $table->string('temp')->nullable()->after('time');
            $table->string('pulse')->nullable()->after('temp');
            $table->string('bp')->nullable()->after('pulse');
            $table->string('spo2')->nullable()->after('bp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_medicine_treatments', function (Blueprint $table) {
            $table->dropColumn(['temp', 'pulse', 'bp', 'spo2']);
        });
    }
};
