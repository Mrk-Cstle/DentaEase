<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalForm extends Model
{
    //

      use HasFactory;

   protected $fillable = [
        'user_id',
        'allergies',
        'allergies_details',
        'heart_condition',
        'heart_condition_details',
        'asthma',
        'asthma_details',
        'had_surgeries',
        'surgery_type',
        'surgery_date',
        'surgery_location',
        'surgery_remarks',
        'medication_name',
        'medication_dosage',
        'medication_reason',
        'visit_reason',
        'last_dental_visit',
        'had_dental_issues',
        'dental_issue_description',
        'dental_anxiety',
        'emergency_name',
        'emergency_relationship',
        'emergency_contact',
    ];

    public function user()
{
    return $this->belongsTo(User::class)->withTrashed();
}
}
