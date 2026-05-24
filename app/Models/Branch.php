<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'address',
        'city',
        'country',
        'phone',
        'is_active',
        'opens_at',
        'closes_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,"company_id","id");
    }
    
    public function queues()
    {
        return $this->hasMany(Queue::class,"queue_id","id");
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
