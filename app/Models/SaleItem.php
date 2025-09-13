<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    //
    protected $fillable = [
        'sale_id',
        'medicine_id',
        'medicine_batch_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function medicine()
    {
        return $this->belongsTo(medicines::class, 'medicine_id');
    }

    public function batch()
    {
        return $this->belongsTo(medicine_batches::class, 'medicine_batch_id');
    }
}
