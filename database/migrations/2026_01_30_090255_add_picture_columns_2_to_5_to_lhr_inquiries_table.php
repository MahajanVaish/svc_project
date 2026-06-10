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
            $table->string('before_picture_2')->nullable()->after('before_picture_1');
            $table->string('before_picture_3')->nullable()->after('before_picture_2');
            $table->string('before_picture_4')->nullable()->after('before_picture_3');
            $table->string('before_picture_5')->nullable()->after('before_picture_4');
            $table->string('after_picture_2')->nullable()->after('after_picture_1');
            $table->string('after_picture_3')->nullable()->after('after_picture_2');
            $table->string('after_picture_4')->nullable()->after('after_picture_3');
            $table->string('after_picture_5')->nullable()->after('after_picture_4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhr_inquiries', function (Blueprint $table) {
            $table->dropColumn(['before_picture_2', 'before_picture_3', 'before_picture_4', 'before_picture_5']);
            $table->dropColumn(['after_picture_2', 'after_picture_3', 'after_picture_4', 'after_picture_5']);
        });
    }
};
