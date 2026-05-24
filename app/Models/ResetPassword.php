<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'is_used',
    ];    

    public function user()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }       
}
