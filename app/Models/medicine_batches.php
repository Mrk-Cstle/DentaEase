<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class medicine_batches extends Model
{
    //
      protected $fillable = [
        'medicine_id',
        'store_id',
        'quantity',
        'expiration_date',
        'status',
    ];

    public function medicine()
    {
        return $this->belongsTo(medicines::class, 'medicine_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id'); 
    }

    public function movements()
    {
        return $this->hasMany(MedicineMovement::class, 'medicine_batch_id');
    }
}
