<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id'); // Literal ID
            $table->unsignedBigInteger('inquiry_id'); // Internal ID
            $table->string('branch_id');
            $table->unsignedBigInteger('user_id'); // Receptionist
            $table->date('call_date');
            $table->string('time_slot'); // Morning, Afternoon, Evening, Night, Mid-night
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
