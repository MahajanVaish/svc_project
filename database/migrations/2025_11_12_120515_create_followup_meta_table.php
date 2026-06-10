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
        Schema::create('followup_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('followup_id');
            $table->string('meta_key');
            $table->text('meta_value')->nullable();
        
            $table->foreign('followup_id')->references('id')->on('patient_followups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followup_meta');
    }
};
