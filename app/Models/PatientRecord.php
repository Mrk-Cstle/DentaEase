<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    protected $fillable = [
        'user_id',
        'last_name', 'first_name', 'middle_name', 'birthdate', 'sex',
        'nationality', 'religion', 'occupation', 'home_address', 'office_address',
        'contact_number', 'email', 'referred_by', 'reason_for_consultation',
        'previous_dentist', 'last_dental_visit',
        'physician_name', 'physician_specialty', 'physician_contact',
        'in_good_health', 'under_treatment', 'had_illness_operation',
        'hospitalized', 'taking_medication', 'allergic', 'bleeding_time',
        'pregnant', 'nursing', 'birth_control_pills', 'blood_type', 'health_conditions','medical_conditions'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'health_conditions' => 'array',
        'in_good_health' => 'boolean',
        'under_treatment' => 'boolean',
        'had_illness_operation' => 'boolean',
        'hospitalized' => 'boolean',
        'taking_medication' => 'boolean',
        'allergic' => 'boolean',
        'bleeding_time' => 'boolean',
        'pregnant' => 'boolean',
        'nursing' => 'boolean',
        'birth_control_pills' => 'boolean',
        'medical_conditions' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
