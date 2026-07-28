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
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'cash_payment')) {
                $table->decimal('cash_payment', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('invoices', 'gpay_payment')) {
                $table->decimal('gpay_payment', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('invoices', 'cheque_payment')) {
                $table->decimal('cheque_payment', 10, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['cash_payment', 'gpay_payment', 'cheque_payment']);
        });
    }
};
