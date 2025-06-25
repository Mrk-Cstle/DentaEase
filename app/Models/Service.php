<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $table = 'services';
    //
    protected $fillable = ['name', 'approx_time', 'approx_price', 'description', 'image', 'type'];
}
