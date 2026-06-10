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
        Schema::create('opt_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opt_id');
            $table->string('meta_key');   // e.g. pod_bmr, pod_calories, etc
            $table->longText('meta_value')->nullable();
            $table->timestamps();
 
            $table->foreign('opt_id')->references('id')->on('opts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opt_meta');
    }
};
