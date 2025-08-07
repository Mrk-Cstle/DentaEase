<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class medicine_batches extends Model
{
    //
      protected $fillable = [
        'medicine_id',
        'branch_id',
        'quantity',
        'expiration_date',
    ];

    public function medicines()
    {
        return $this->belongsTo(medicines::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function movements()
{
    return $this->hasMany(MedicineMovement::class, 'medicine_batch_id');
}
}
