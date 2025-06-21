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
        Schema::table('newusers', function (Blueprint $table) {
            //
              $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('birthplace')->nullable();
            $table->text('current_address')->nullable();
            $table->string('verification_id')->nullable(); 
             $table->string('account_type')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newusers', function (Blueprint $table) {
            //
        });
    }
};
