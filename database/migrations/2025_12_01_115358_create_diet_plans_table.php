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
        Schema::create('diet_plans', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id');
            $table->string('patient_id');
            $table->string('patient_name')->nullable();
            $table->date('date');
            $table->string('diet_name');
            $table->json('time_search_menus')->nullable();
            $table->text('general_notes')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            // Add foreign key for created_by
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Add indexes for better performance
            $table->index('branch_id');
            $table->index('patient_id');
            $table->index('date');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_plans');
    }
};
