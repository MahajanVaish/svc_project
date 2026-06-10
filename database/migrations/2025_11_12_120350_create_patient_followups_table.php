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
        Schema::create('patient_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inquiry_id'); 
            $table->foreign('inquiry_id')->references('id')->on('patient_inquiry')->onDelete('cascade');
        
            $table->date('followup_date')->nullable();
            $table->string('delete_status')->nullable(); 
            $table->string('delete_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_followups');
    }
};
