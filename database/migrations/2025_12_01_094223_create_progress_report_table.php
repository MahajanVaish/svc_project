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
        Schema::create('progress_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('patient_id', 250);
            $table->string('branch_name', 250);
            $table->string('branch_id', 255);
            $table->string('patient_name', 250);
            $table->string('date', 255);
            $table->string('time', 250);
            $table->string('body_part', 250);
            $table->string('bp_p', 250);
            $table->string('pulse', 255);
            $table->string('detox', 255);
            $table->string('breast_reshaping', 255);
            $table->string('face_program', 255);
            $table->string('relaxation', 255);
            $table->string('lypolysis_treatment', 255);
            $table->string('weight', 250);
            $table->string('councilor_doctor', 250);
            $table->string('exercise', 255);
            $table->string('delete_status', 255);
            $table->string('delete_by', 250);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_report');
    }
};
