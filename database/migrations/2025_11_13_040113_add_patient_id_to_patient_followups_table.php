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
        Schema::table('patient_followups', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->after('inquiry_id');

        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void    
    {
        Schema::table('patient_followups', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropColumn('patient_id');
        });
    }
};
