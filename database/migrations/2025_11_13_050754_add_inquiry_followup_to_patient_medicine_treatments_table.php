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
            $table->unsignedBigInteger('inquiry_id')->nullable()->after('patient_id');
            $table->unsignedBigInteger('followup_id')->nullable()->after('inquiry_id');
            $table->foreign('inquiry_id')->references('id')->on('patient_inquiry')->onDelete('cascade');
            $table->foreign('followup_id')->references('id')->on('patient_followups')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_medicine_treatments', function (Blueprint $table) {
            $table->dropForeign(['inquiry_id']);
            $table->dropForeign(['followup_id']);
            $table->dropColumn(['inquiry_id', 'followup_id']);
        });
    }
};
    