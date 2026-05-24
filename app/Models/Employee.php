<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        "user_id",
        "status",
        "phone_number",
        "hire_date"
    ];

    public function user()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class,"employee_branches")->using(EmployeeBranch::class)->withPivot(["role","start","end"])->withTimestamps();
    }
}