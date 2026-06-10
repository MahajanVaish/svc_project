<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acc_inquirys', function (Blueprint $table) {
            if (!Schema::hasColumn('acc_inquirys', 'complain')) {
                $table->text('complain')->nullable()->after('diagnosis');
            }
        });
    }

    public function down(): void
    {
        Schema::table('acc_inquirys', function (Blueprint $table) {
            $table->dropColumn('complain');
        });
    }
};
