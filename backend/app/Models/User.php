<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Provider;
use App\Models\Appointment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\URL;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail

{
    use HasApiTokens, HasFactory, Notifiable;
    // Define valid user types as constants
    const USER_TYPE_ADMIN = 'admin';
    const USER_TYPE_PROVIDER = 'provider';
    const USER_TYPE_CLIENT = 'client';

    const VALID_USER_TYPES = [
        self::USER_TYPE_ADMIN,
        self::USER_TYPE_PROVIDER,
        self::USER_TYPE_CLIENT,
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    // A simple method to create a user
    public static function createUser($name, $email, $password)
    {
        return self::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }

    // Validation rules
    public static function validationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:' . implode(',', self::VALID_USER_TYPES),
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if (!in_array($user->user_type, self::VALID_USER_TYPES)) {
                throw new \InvalidArgumentException('Invalid user type: ' . $user->user_type);
            }
        });
    }


    // Role checking methods (same as before)
    public function isAdmin() { return $this->user_type === self::USER_TYPE_ADMIN; }
    public function isProvider() { return $this->user_type === self::USER_TYPE_PROVIDER; }
    public function isClient() { return $this->user_type === self::USER_TYPE_CLIENT; }

    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

     // Relationships 
     // User can be a provider
     public function provider()
     {
         return $this->hasOne(Provider::class);
     }
 
     public function clientAppointments()
     {
         return $this->hasMany(Appointment::class, 'client_id');
     }
 
     // Scopes
     public function scopeProviders($query)
     {
         return $query->where('user_type', 'provider');
     }
 
     public function scopeClients($query)
     {
         return $query->where('user_type', 'client');
     }
 
     public function scopeActive($query)
     {
         return $query->where('is_active', true);
     }

    // public function sendEmailVerificationNotification()
    // {
    //     $this->notify(new CustomVerifyEmail);
    // }
}
