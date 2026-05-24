<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as AuthUser;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends AuthUser implements JWTSubject
{
    protected $fillable = [
        "role_id",
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
        'status',
        'verification_token',
        'expires_at',
    ];

    protected $hidden = [
        'verification_token',
        'expires_at'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class,"user_id","id");
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class,"user_id","id");
    }

    public function role()
    {
        return $this->belongsTo(Role::class,"role_id","id");
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class,"user_id","id");
    }

    public function resetPasswords()
    {
        return $this->hasMany(ResetPassword::class,"user_id","id");
    }

    public function userDevices()
    {
        return $this->hasMany(UserDevice::class,"user_id","id");
    }

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class,"user_id","id");
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function getJWTCustomClaims()
    {
        return[

        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
}
