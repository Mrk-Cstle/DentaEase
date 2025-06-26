<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    protected $casts = [
        'open_days' => 'array',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
    ];
    public function staff()
{
   return $this->belongsToMany(User::class, 'store_staff')
                ->using(StoreStaff::class)     
                ->withPivot('position')        
                ->withTimestamps();  
}
}
