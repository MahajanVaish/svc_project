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
        Schema::create('patient_inquiry', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->unique();
            $table->string('branch')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
    
          
            $table->string('patient_name');
            $table->string('address')->nullable();
            $table->integer('age')->nullable();
            $table->string('diagnosis')->nullable();
            $table->date('inquiry_date')->nullable();
            $table->date('next_follow_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_inquiry');
    }
};
