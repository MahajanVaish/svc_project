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
        Schema::table('lhr_inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('lhr_inquiries', 'mobile_no')) {
                $table->string('mobile_no')->nullable()->after('patient_name');
            }
            if (!Schema::hasColumn('lhr_inquiries', 'email')) {
                $table->string('email')->nullable()->after('mobile_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhr_inquiries', function (Blueprint $table) {
            $table->dropColumn(['mobile_no', 'email']);
        });
    }
};
