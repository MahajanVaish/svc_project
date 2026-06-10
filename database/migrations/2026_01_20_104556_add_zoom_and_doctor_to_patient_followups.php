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
        Schema::table('patient_followups', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->nullable()->after('inquiry_id');
            $table->string('zoom_meeting_id')->nullable();
            $table->text('zoom_start_url')->nullable();
            $table->text('zoom_join_url')->nullable();

            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_followups', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn(['doctor_id', 'zoom_meeting_id', 'zoom_start_url', 'zoom_join_url']);
        });
    }
};
