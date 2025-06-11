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
    // protected $fillable = [
    //     'first_name',
    //     'middle_name',
    //     'last_name',
    //     'email',
    //     'password',
    //     'birth_date',
    //     'gender',
    //     'address',
    //     'role',
    // ];

     protected $guarded = [
        'id'
    ];


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

    protected static function booted(){
        static::saving(function ($user) {
            $name = $user->first_name;

            if (!empty($user->middle_name)) {
                $name .= ' ' . $user->middle_name . '.';
            }

            $name .= ' ' . $user->last_name;

            $user->name = $name;
        });
    }
}
