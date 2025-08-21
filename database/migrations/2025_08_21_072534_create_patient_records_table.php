<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('patient_records', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('sex', 1)->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();
            $table->string('home_address')->nullable();
            $table->string('office_address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();

            // Dental history
            $table->string('referred_by')->nullable();
            $table->string('reason_for_consultation')->nullable();
            $table->string('previous_dentist')->nullable();
            $table->string('last_dental_visit')->nullable();

            // Medical history
            $table->string('physician_name')->nullable();
            $table->string('physician_specialty')->nullable();
            $table->string('physician_contact')->nullable();

            // Checkboxes (booleans)
            $table->boolean('in_good_health')->default(false);
            $table->boolean('under_treatment')->default(false);
            $table->boolean('had_illness_operation')->default(false);
            $table->boolean('hospitalized')->default(false);
            $table->boolean('taking_medication')->default(false);
            $table->boolean('allergic')->default(false);
            $table->boolean('bleeding_time')->default(false);
            $table->boolean('pregnant')->default(false);
            $table->boolean('nursing')->default(false);
            $table->boolean('birth_control_pills')->default(false);

            $table->string('blood_type')->nullable();

            // Arrays (json)
            $table->json('health_conditions')->nullable();
            $table->json('medical_conditions')->nullable();

            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_records');
    }
};
