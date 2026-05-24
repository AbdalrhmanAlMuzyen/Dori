<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $fillable = [
        "user_device_id",
        "user_id",
        "fcm_token",
        "is_revoked"
    ];
}
