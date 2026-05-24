<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBranch extends Model
{
    protected $fillable = [
        'employee_id',
        'branch_id',
        'role',
        'start',
        'end',
    ];

    
}
