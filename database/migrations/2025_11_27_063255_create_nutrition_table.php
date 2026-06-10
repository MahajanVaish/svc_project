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
        Schema::create('nutrition', function (Blueprint $table) {
            $table->id();
            $table->longText('nutrition_name');
            $table->string('energy_kcal', 250);
            $table->string('water', 250);
            $table->string('fat', 250);
            $table->string('total_fiber', 250);
            $table->string('carbohydrate', 250);
            $table->string('protein', 250);
            $table->string('vitamin_c', 255);
            $table->string('insoluable_fiber', 255);
            $table->string('soluable_fiber', 255);
            $table->string('biotin', 255);
            $table->string('total_folates', 255);
            $table->string('calcium', 255);
            $table->string('cu', 255);
            $table->string('fe', 255);
            $table->string('mg', 255);
            $table->string('p', 255);
            $table->string('k', 255);
            $table->string('se', 255);
            $table->string('na', 255);
            $table->string('za', 255);
            $table->string('delete_status', 255);
            $table->string('delete_by', 250);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition');
    }
};
