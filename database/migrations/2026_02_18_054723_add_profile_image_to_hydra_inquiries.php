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
        Schema::table('hydra_inquiries', function (Blueprint $table) {
            $table->string('profile_image')->nullable()->after('patient_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hydra_inquiries', function (Blueprint $table) {
            $table->dropColumn('profile_image');
        });
    }
};
