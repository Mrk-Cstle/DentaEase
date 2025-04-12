<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class newuser extends Model
{
    //

    protected $fillable = [
        'first_name',
        'last_name',
       
        
        'birth_date',
        'email',
        'contact_number',
        'user',
        'password',
    ];
}
