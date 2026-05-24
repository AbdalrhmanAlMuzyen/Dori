<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'branch_id',
        'name',
        'description',
        'avg_service_time',
        'is_active',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class,"branch_id","id");
    }

    public function queueSessions()
    {
        return $this->hasMany(QueueSession::class,"queue_id","id");
    }
}
