<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueSession extends Model
{
    protected $fillable = [
        'queue_id',
        'last_ticket_number',
        'status',
        'date',
    ];
    
    public function queue()
    {
        return $this->belongsTo(Queue::class,"queue_id","id");
    }
}
