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
        Schema::create('medical_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Medical History
            $table->boolean('allergies')->nullable();
            $table->string('allergies_details')->nullable();
            $table->boolean('heart_condition')->nullable();
            $table->string('heart_condition_details')->nullable();
            $table->boolean('asthma')->nullable();
            $table->string('asthma_details')->nullable();

            // Surgeries
            $table->boolean('had_surgeries')->nullable();
            $table->string('surgery_type')->nullable();
            $table->date('surgery_date')->nullable();
            $table->string('surgery_location')->nullable();
            $table->string('surgery_remarks')->nullable();

            // Medications
            $table->string('medication_name')->nullable();
            $table->string('medication_dosage')->nullable();
            $table->string('medication_reason')->nullable();

            // Dental History
            $table->string('visit_reason')->nullable();
            $table->date('last_dental_visit')->nullable();
            $table->boolean('had_dental_issues')->nullable();
            $table->string('dental_issue_description')->nullable();
            $table->boolean('dental_anxiety')->nullable();

            // Emergency Contact
            $table->string('emergency_name')->nullable();
            $table->string('emergency_relationship')->nullable();
            $table->string('emergency_contact')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_forms');
    }
};
