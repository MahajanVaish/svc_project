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
            $table->string('diet')->nullable()->after('status_name');
            $table->string('exercise')->nullable()->after('diet');
            $table->string('sleep')->nullable()->after('exercise');
            $table->string('water')->nullable()->after('sleep');
        });

        Schema::table('hydra_inquiries', function (Blueprint $table) {
            $table->string('diet')->nullable()->after('status_name');
            $table->string('exercise')->nullable()->after('diet');
            $table->string('sleep')->nullable()->after('exercise');
            $table->string('water')->nullable()->after('sleep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhr_inquiries', function (Blueprint $table) {
            $table->dropColumn(['diet', 'exercise', 'sleep', 'water']);
        });

        Schema::table('hydra_inquiries', function (Blueprint $table) {
            $table->dropColumn(['diet', 'exercise', 'sleep', 'water']);
        });
    }
};
