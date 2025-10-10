<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
class StoreStaff extends Pivot
{
    protected $table = 'store_staff';

    protected $fillable = [
        'position',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
