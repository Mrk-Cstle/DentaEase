<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dental_charts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            
            // Permanent teeth (11–48 FDI notation)
            foreach (range(11, 18) as $tooth) {
                $table->text("tooth_{$tooth}_condition")->nullable();
                $table->text("tooth_{$tooth}_treatment")->nullable();
            }
            foreach (range(21, 28) as $tooth) {
                $table->text("tooth_{$tooth}_condition")->nullable();
                $table->text("tooth_{$tooth}_treatment")->nullable();
            }
            foreach (range(31, 38) as $tooth) {
                $table->text("tooth_{$tooth}_condition")->nullable();
                $table->text("tooth_{$tooth}_treatment")->nullable();
            }
            foreach (range(41, 48) as $tooth) {
                $table->text("tooth_{$tooth}_condition")->nullable();
                $table->text("tooth_{$tooth}_treatment")->nullable();
            }

            // Temporary teeth (51–85 FDI notation)
            foreach (range(51, 55) as $tooth) {
                $table->text("tooth_{$tooth}_condition")->nullable();
                $table->text("tooth_{$tooth}_treatment")->nullable();
            }
            foreach (range(61, 65) as $tooth) {
                $table->text("tooth_{$tooth}_condition")->nullable();
                $table->text("tooth_{$tooth}_treatment")->nullable();
            }
            foreach (range(71, 75) as $tooth) {
                $table->text("tooth_{$tooth}_condition")->nullable();
                $table->text("tooth_{$tooth}_treatment")->nullable();
            }
            foreach (range(81, 85) as $tooth) {
                $table->text("tooth_{$tooth}_condition")->nullable();
                $table->text("tooth_{$tooth}_treatment")->nullable();
            }

            // Periodontal Screening
            $table->boolean('gingivitis')->default(false);
            $table->boolean('early_periodontitis')->default(false);
            $table->boolean('moderate_periodontitis')->default(false);
            $table->boolean('advanced_periodontitis')->default(false);
    
            // Occlusion
            $table->boolean('occlusion_class_molar')->default(false);
            $table->boolean('overjet')->default(false);
            $table->boolean('overbite')->default(false);
            $table->boolean('midline_deviation')->default(false);
            $table->boolean('crossbite')->default(false);
    
            // Appliances
            $table->boolean('appliance_orthodontic')->default(false);
            $table->boolean('appliance_stayplate')->default(false);
            $table->boolean('appliance_others')->default(false);
    
            // TMD
            $table->boolean('tmd_clenching')->default(false);
            $table->boolean('tmd_clicking')->default(false);
            $table->boolean('tmd_trismus')->default(false);
            $table->boolean('tmd_muscle_spasm')->default(false);
            
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dental_charts');
    }
};
