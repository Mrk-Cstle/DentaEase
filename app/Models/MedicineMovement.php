<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineMovement extends Model
{
    protected $fillable = [
        'medicine_id',
        'store_id',
        'medicine_batch_id',
        'type',
        'quantity',
        'remarks',
    ];

    public function medicine()
    {
        return $this->belongsTo(medicines::class, 'medicine_id');
    }

    public function batch()
    {
        return $this->belongsTo(medicine_batches::class, 'medicine_batch_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}

