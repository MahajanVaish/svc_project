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
        Schema::table('monthly_assessments', function (Blueprint $table) {
            $table->string('diet')->nullable()->after('muscle_legs');
            $table->string('exercise')->nullable()->after('diet');
            $table->string('sleep')->nullable()->after('exercise');
            $table->string('water')->nullable()->after('sleep');
        });

        Schema::table('diet_plans', function (Blueprint $table) {
            $table->string('diet')->nullable()->after('general_notes');
            $table->string('exercise')->nullable()->after('diet');
            $table->string('sleep')->nullable()->after('exercise');
            $table->string('water')->nullable()->after('sleep');
        });

        Schema::table('progress_report', function (Blueprint $table) {
            $table->string('diet')->nullable()->after('exercise');
            $table->string('sleep')->nullable()->after('diet');
            $table->string('water')->nullable()->after('sleep');
            // exercise already exists in progress_report
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_assessments', function (Blueprint $table) {
            $table->dropColumn(['diet', 'exercise', 'sleep', 'water']);
        });

        Schema::table('diet_plans', function (Blueprint $table) {
            $table->dropColumn(['diet', 'exercise', 'sleep', 'water']);
        });

        Schema::table('progress_report', function (Blueprint $table) {
            $table->dropColumn(['diet', 'sleep', 'water']);
        });
    }
};
