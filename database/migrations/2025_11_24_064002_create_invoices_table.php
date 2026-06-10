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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id');
            $table->foreignId('patient_id')->constrained('patient_inquiry')->onDelete('cascade');
            $table->foreignId('charge_id')->constrained('charges')->onDelete('cascade');
            $table->string('invoice_no');
            $table->date('invoice_date');
            $table->text('address');
            $table->string('phone')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('pending_due', 10, 2)->default(0);
            $table->decimal('total_payment', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('given_payment', 10, 2)->default(0);
            $table->decimal('due_payment', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
