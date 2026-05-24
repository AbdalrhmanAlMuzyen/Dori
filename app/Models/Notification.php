<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        "user_id",
        "title",
        "body",
        "read_at",
        "target_id",
        "type"
    ];

    public function user()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }    
}
