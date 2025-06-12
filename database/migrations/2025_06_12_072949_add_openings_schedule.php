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
        //
        Schema::table('stores', function (Blueprint $table) {
            $table->text('open_days')->nullable(); // e.g., ["mon", "tue", "fri"]
            $table->time('opening_time')->nullable(); // e.g., 09:00:00
            $table->time('closing_time')->nullable(); // e.g., 17:00:00
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
