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
            $table->json('user_status')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acc_inquirys', function (Blueprint $table) {
            $table->string('user_status', 255)->nullable(false)->change();
        });
    }
};
