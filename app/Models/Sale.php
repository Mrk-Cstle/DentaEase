<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
     protected $fillable = [
        'store_id',
        'user_id',
        'total_amount',
        'status',
        'remarks',
        'patient_id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
public function patient()
{
    return $this->belongsTo(User::class, 'patient_id')->where('account_type', 'patient')->withTrashed();
}

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
