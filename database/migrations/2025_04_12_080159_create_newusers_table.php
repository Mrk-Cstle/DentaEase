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
        Schema::create('newusers', function (Blueprint $table) {
            $table->id();
            $table->string('name');

         
            $table->integer('status')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('user')->unique()->nullable();
            $table->string('email');
            $table->string('contact_number'); 
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newusers');
    }
};
