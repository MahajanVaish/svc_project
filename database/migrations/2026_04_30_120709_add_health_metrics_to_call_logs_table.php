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
        Schema::table('call_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('call_logs', 'diet')) {
                $table->string('diet')->nullable()->after('remarks');
            }
            if (!Schema::hasColumn('call_logs', 'exercise')) {
                $table->string('exercise')->nullable()->after('diet');
            }
            if (!Schema::hasColumn('call_logs', 'sleep')) {
                $table->string('sleep')->nullable()->after('exercise');
            }
            if (!Schema::hasColumn('call_logs', 'water')) {
                $table->string('water')->nullable()->after('sleep');
            }
        });
    }

    public function down(): void
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropColumn(['diet', 'exercise', 'sleep', 'water']);
        });
    }
};
