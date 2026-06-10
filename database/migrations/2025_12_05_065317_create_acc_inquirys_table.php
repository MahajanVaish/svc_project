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
        Schema::create('acc_inquirys', function (Blueprint $table) {
            $table->increments('id'); // int(11) AUTO_INCREMENT
     
            $table->string('patient_id', 250);
            $table->string('branch', 50);
            $table->string('branch_id', 255);
            $table->string('patient_name', 250);
            $table->string('gender', 255);
            $table->string('phone_no', 50);
            $table->string('age', 50);
            $table->string('height', 250);
            $table->string('weight', 250);
            $table->string('bmi', 250);
            $table->string('address', 250);
            $table->string('refrance', 250);
            $table->string('email', 50);
            $table->string('inquiry_date', 255);
            $table->string('inquiry_time', 255);
            $table->string('inquery_given_by', 250);
            $table->string('payment', 250);
            $table->string('inquiry_foc', 255);
            $table->string('diagnosis', 250);
            $table->string('user_status', 255);
            $table->string('client_old_new', 50);
            $table->string('delete_status', 255);
            $table->string('delete_by', 255)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_inquirys');
    }
};
