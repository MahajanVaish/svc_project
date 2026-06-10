<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('patient_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_inquiry')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('manage_programs')->onDelete('set null');
            $table->enum('type', ['credit', 'debit']); // debit = invoice created (owed), credit = payment made
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_transactions');
    }
};
