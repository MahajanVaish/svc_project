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
        Schema::create('lhr_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');             // string patient id
            $table->string('branch');                 // branch name
            $table->string('branch_id');              // branch id
            $table->string('patient_name');
            $table->date('inquiry_date');
            $table->text('address')->nullable();
            $table->enum('gender', ['male','female','other']);
            $table->integer('age');
            $table->string('year')->nullable();
            $table->string('area');
            $table->string('session')->nullable();
            $table->string('area_code')->nullable();
            $table->string('energy')->nullable();
            $table->string('frequency')->nullable();
            $table->string('shot')->nullable();
            $table->string('staff_name')->nullable();
            $table->enum('status_name', ['pending','joined'])->default('pending');
            $table->enum('hormonal_issues', ['yes','no'])->default('no');
            $table->enum('medication', ['yes','no'])->default('no');
            $table->enum('previous_treatment', ['yes','no'])->default('no');
            $table->enum('pcod_thyroid', ['yes','no'])->default('no');
            $table->enum('skin_conditions', ['yes','no'])->default('no');
            $table->enum('ongoing_treatments', ['yes','no'])->default('no');
            $table->enum('implants_tattoos', ['yes','no'])->default('no');
    
            // JSON column with validation equivalent
            $table->json('procedure')->nullable();
    
            $table->string('reference_by')->nullable();
            $table->string('statement_1')->nullable();
            $table->string('statement_2')->nullable();
            $table->date('next_follow_up')->nullable();
            $table->text('notes')->nullable();
    
            $table->boolean('foc')->default(0);
    
            $table->decimal('total_payment',10,2)->default(0.00);
            $table->decimal('discount_payment',10,2)->default(0.00);
            $table->decimal('given_payment',10,2)->default(0.00);
            $table->decimal('due_payment',10,2)->default(0.00);
            $table->decimal('cash_payment',10,2)->default(0.00);
            $table->decimal('google_pay',10,2)->default(0.00);
            $table->decimal('cheque_payment',10,2)->default(0.00);
    
            $table->string('before_picture_1')->nullable();
            $table->string('after_picture_1')->nullable();
            $table->string('account')->nullable();
    
            $table->time('time')->default('13:00:00');
    
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lhr_inquiries');
    }
};
