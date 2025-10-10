<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class daily_logs extends Model
{
    //
     protected $fillable = ['user_id', 'appointment_id', 'scanned_at'];

     public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
