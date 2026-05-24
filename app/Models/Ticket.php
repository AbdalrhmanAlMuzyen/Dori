<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'queue_session_id',
        'number',
        'status',
        'called_at',
        'served_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }       
}
