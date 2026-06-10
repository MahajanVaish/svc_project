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
        Schema::table('hydra_inquiries', function (Blueprint $table) {
            $table->string('patient_id')->nullable()->after('id');
            // Add branch column
            $table->string('branch')->nullable()->after('patient_id');
            // Add branch_id column
            $table->string('branch_id')->nullable()->after('branch');

        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hydra_inquiries', function (Blueprint $table) {

            // Drop the columns
            $table->dropColumn(['patient_id', 'branch', 'branch_id']);
        });
    }
};