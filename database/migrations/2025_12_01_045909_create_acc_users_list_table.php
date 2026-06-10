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
        Schema::create('acc_users_list', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');    
            $table->string('email')->unique();
            $table->string('password');
            $table->string('user_role');   
            $table->string('user_branch');  
            $table->timestamps();
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_users_list');
    }
};
