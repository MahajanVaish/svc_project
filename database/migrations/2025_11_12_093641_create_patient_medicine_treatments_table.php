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
        Schema::create('patient_medicine_treatments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->string('type'); 
            $table->string('medicine')->nullable();
            $table->string('dose')->nullable();
            $table->string('timing')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_medicine_treatments');
    }
};
