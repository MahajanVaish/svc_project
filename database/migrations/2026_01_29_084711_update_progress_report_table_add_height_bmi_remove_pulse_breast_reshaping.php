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
        Schema::table('progress_report', function (Blueprint $table) {
            // Add new columns
            $table->decimal('height', 5, 2)->nullable()->after('weight');
            $table->decimal('bmi', 5, 2)->nullable()->after('height');
            
            // Remove old columns
            $table->dropColumn(['pulse', 'breast_reshaping']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_report', function (Blueprint $table) {
            // Add back old columns
            $table->string('pulse')->nullable()->after('bp_p');
            $table->string('breast_reshaping')->nullable()->after('detox');
            
            // Remove new columns
            $table->dropColumn(['height', 'bmi']);
        });
    }
};
