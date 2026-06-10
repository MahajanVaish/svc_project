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
        Schema::table('acc_inquirys', function (Blueprint $table) {
            $table->date('next_followup_date')->nullable()->after('pod_vld_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acc_inquirys', function (Blueprint $table) {
            //
        });
    }
};
