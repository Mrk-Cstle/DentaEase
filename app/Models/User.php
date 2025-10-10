<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
  
    protected $dates = ['deleted_at'];

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

public function messages()
{
    return $this->hasMany(Message::class, 'receiver_id'); 
    // or 'sender_id' depending on what you want to show
}
public function latestMessage()
{
    $storeId = session('active_branch_id');

    // A message can be sent OR received by the user
    return $this->hasOne(\App\Models\Message::class, 'sender_id', 'id')
        ->where('store_id', $storeId)
        ->latestOfMany();
}


}
