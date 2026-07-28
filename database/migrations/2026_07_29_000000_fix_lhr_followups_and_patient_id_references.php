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
        // 1. Add inquiry_id column to lhr_followups table if it doesn't exist
        if (Schema::hasTable('lhr_followups')) {
            Schema::table('lhr_followups', function (Blueprint $table) {
                if (!Schema::hasColumn('lhr_followups', 'inquiry_id')) {
                    $table->unsignedBigInteger('inquiry_id')->nullable()->after('id');
                }
            });
        }

        // 2. Safely add indexes on patient_id for fast queries on live and local
        $tablesWithPatientId = ['lhr_followups', 'opts', 'progress_report', 'diet_plans', 'monthly_assessments'];
        foreach ($tablesWithPatientId as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'patient_id')) {
                try {
                    Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                        $table->index('patient_id', $tableName . '_patient_id_idx');
                    });
                } catch (\Throwable $e) {
                    // Index already exists, ignore safely
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lhr_followups') && Schema::hasColumn('lhr_followups', 'inquiry_id')) {
            Schema::table('lhr_followups', function (Blueprint $table) {
                $table->dropColumn('inquiry_id');
            });
        }
    }
};
