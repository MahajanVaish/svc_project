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
       Schema::create('hydra_inquiries', function (Blueprint $table) {
            $table->id();
            
            // Patient Information
            $table->string('patient_name');
            $table->date('inquiry_date');
            $table->text('address')->nullable();
            $table->time('inquiry_time')->nullable();
            $table->string('phone_number')->nullable();
            
            // Gender & Basic Info
            $table->enum('gender', ['male', 'female', 'other']);
            $table->integer('age');
            $table->string('reference_by')->nullable();
            $table->string('session')->nullable();
            $table->date('next_follow_up')->nullable();
            
            // Payment Information
            $table->boolean('foc')->default(false);
            $table->decimal('total_payment', 10, 2)->default(0);
            $table->decimal('discount_payment', 10, 2)->default(0);
            $table->decimal('given_payment', 10, 2)->default(0);
            $table->decimal('due_payment', 10, 2)->default(0);
            $table->decimal('cash_payment', 10, 2)->default(0);
            $table->decimal('google_pay', 10, 2)->default(0);
            $table->string('payment_mode')->nullable();
            
            // Status
            $table->enum('status_name', ['pending', 'joined'])->default('pending');
            
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hydra_inquiries');
    }
};
