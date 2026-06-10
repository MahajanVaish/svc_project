<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('acc_inquirys', function (Blueprint $table) {
            if (!Schema::hasColumn('acc_inquirys', 'discount_payment')) {
                $table->decimal('discount_payment', 10, 2)->default(0)->after('payment');
            }
            if (!Schema::hasColumn('acc_inquirys', 'is_freesession')) {
                $table->integer('is_freesession')->default(0)->after('inquiry_foc');
            }
        });
    }

    public function down()
    {
        Schema::table('acc_inquirys', function (Blueprint $table) {
            $table->dropColumn(['discount_payment', 'is_freesession']);
        });
    }
};
