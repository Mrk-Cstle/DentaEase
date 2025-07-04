<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Appointment extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'service_name',
        'dentist_id',
        'appointment_date',
        'appointment_time',
        'booking_end_time',
        'work_done',
        'total_price',
        'payment_type',
        'payment_image',
        'desc',
        'status', // pending, approved, cancelled
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
    ];

    // Relationship: belongs to a store (branch)
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Relationship: belongs to a user (customer/patient)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function dentist()
{
    return $this->belongsTo(User::class, 'dentist_id');
}
}
