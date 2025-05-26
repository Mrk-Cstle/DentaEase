<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    public function staff()
{
   return $this->belongsToMany(User::class, 'store_staff')
                ->using(StoreStaff::class)     
                ->withPivot('position')        
                ->withTimestamps();  
}
}
