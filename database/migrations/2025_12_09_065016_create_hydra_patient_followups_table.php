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
        Schema::create('hydra_patient_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hydra_inquiry_id')->constrained('hydra_inquiries')->onDelete('cascade');
            $table->date('follow_up_date')->nullable();
            $table->time('follow_up_time')->nullable();
            $table->string('patient_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->integer('age')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->boolean('foc')->default(false);
            $table->decimal('total_payment', 10, 2)->default(0);
            $table->decimal('discount_payment', 10, 2)->default(0);
            $table->decimal('given_payment', 10, 2)->default(0);
            $table->decimal('due_payment', 10, 2)->default(0);
            $table->decimal('cash_payment', 10, 2)->default(0);
            $table->decimal('google_pay', 10, 2)->default(0);
            $table->string('phone_number')->nullable();
            $table->string('session')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('follow_up_date');
            $table->index('next_follow_up_date');
            $table->index(['hydra_inquiry_id', 'follow_up_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hydra_patient_followups');
    }
};
