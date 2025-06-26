<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class newuser extends Model
{
    //
    use HasFactory;


    protected $fillable = [
      'name',
    'middlename',
    'lastname',
    'suffix',
    'birth_date',
    'birthplace',
    'current_address',
    'email',
    'contact_number',
    'user',
    'password',
    'account_type',
    'position',
    'status',
    'verification_id',
    ];

    protected $casts = [      
    'birth_date' => 'date',
    ];
        
    
}
