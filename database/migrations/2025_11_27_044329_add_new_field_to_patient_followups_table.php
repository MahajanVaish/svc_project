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
                        $table->date('next_follow_date')->nullable()->after('followup_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_followups', function (Blueprint $table) {
                       $table->dropColumn('next_follow_date');
        });
    }
};
