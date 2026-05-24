<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeQueue extends Model
{
    protected $fillable = [
        'queue_id',
        'employee_id',
        'is_active',
    ];
}
