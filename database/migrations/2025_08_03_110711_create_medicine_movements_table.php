<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('medicine_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_batch_id')->nullable()->constrained()->onDelete('set null');

            $table->enum('type', ['stock_in', 'stock_out']);
            $table->integer('quantity');
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicine_movements');
    }
}

