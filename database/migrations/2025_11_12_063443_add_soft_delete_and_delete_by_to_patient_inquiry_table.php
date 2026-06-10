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
        Schema::table('patient_inquiry', function (Blueprint $table) {
            $table->softDeletes(); // Adds deleted_at column for soft delete
            $table->string('delete_status')->nullable(); // 'deleted' or null
            $table->string('delete_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_inquiry', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('delete_status');
            $table->dropColumn('delete_by');
        });
    }
};
