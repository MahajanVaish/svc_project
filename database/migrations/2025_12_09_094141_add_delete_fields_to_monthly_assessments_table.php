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
            $table->tinyInteger('delete_status')->default(0);
            $table->unsignedBigInteger('delete_by')->nullable();
            $table->timestamp('delete_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_assessments', function (Blueprint $table) {
            //
        });
    }
};
