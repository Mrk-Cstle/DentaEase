<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class newuser extends Model
{
    //
    use HasFactory;


    protected $fillable = [
        'first_name',
        'last_name',
       
        
        'birth_date',
        'email',
        'contact_number',
        'user',
        'password',
    ];

    protected $casts = [      
    'birth_date' => 'date',
    ];
        
    
}
