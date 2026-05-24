<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        "name",
        "email",
        "phone_number",
        "logo",
        "is_active"
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class,"company_id","id");
    }
}
