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
        Schema::create('manage_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name');
            $table->string('program_short_name');
            $table->enum('gender', ['Male', 'Female', 'Both']);
            $table->string('branch')->nullable();
            $table->decimal('program_price', 10, 2);
            $table->string('profram_log')->nullable();
            $table->boolean('delete_status')->default(0);
            $table->unsignedBigInteger('delete_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_programs');
    }
};
