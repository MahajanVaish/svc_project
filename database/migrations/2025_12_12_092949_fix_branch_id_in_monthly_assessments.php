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
    Schema::table('monthly_assessments', function (Blueprint $table) {
        $table->string('branch_id')->change();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('monthly_assessments', function (Blueprint $table) {

            // Revert back to bigint
            $table->unsignedBigInteger('branch_id')->change();
        });
    }
};
