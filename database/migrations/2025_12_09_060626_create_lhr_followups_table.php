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
        Schema::create('lhr_followups', function (Blueprint $table) {
            $table->increments('id'); 
 
            $table->string('patient_id',250);
            $table->string('branch_id',255);
            $table->string('branch',250);
            $table->string('patient_name',250);
            $table->string('address',250);
            $table->string('inquiry_date',50);
            $table->string('inquiry_time',15);
            $table->string('gender',250);
            $table->string('age',250);
            $table->string('afra_code',255);
            $table->string('energy',255);
            $table->string('frequency',255);
            $table->string('shot',255);
            $table->string('staff_name',255);
            $table->string('month_year',255);   
            $table->string('refranceby',250);
            $table->string('next_follow_date',250);
            $table->string('notes',255);
            $table->string('payment_method',255);
            $table->string('total_payment',250);
            $table->string('discount_payment',250);
            $table->string('given_payment',250);
            $table->string('due_payment',250);
            $table->string('foc',250);
            $table->string('cash_price',255);
            $table->string('gpay_price',250);
            $table->string('cheque_price',250);
            $table->string('delete_status',255);
 
            $table->string('delete_by',255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lhr_followups');
    }
};
