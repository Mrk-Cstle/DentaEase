<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

     protected static function booted()
    {
        static::created(function ($user) {
            // Automatically generate QR when user is created
            app(\App\Http\Controllers\QrController::class)->generateUserQr($user);
        });
    }

    protected $appends = ['full_name'];
    public function getFullNameAttribute()
{
    $lastname   = $this->lastname ?? '';
    $firstname  = $this->name ?? '';
    $middlename = $this->middlename ?? '';
    $suffix     = $this->suffix ?? '';

    return trim("{$lastname}, {$firstname} {$middlename} {$suffix}");
}

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
    'profile_image',
    'qr_code',
    'qr_token',
    'is_consent',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

public function stores()
{
    return $this->belongsToMany(Store::class, 'store_staff')
                ->using(StoreStaff::class)     
                ->withPivot('position')        
                ->withTimestamps();           
}

public function appointment()
{
    return $this->hasMany(Appointment::class);
}

public function medicalForm()
{
    return $this->hasOne(MedicalForm::class);
}
}
