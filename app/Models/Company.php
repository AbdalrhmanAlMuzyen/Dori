<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        "user_id",
        "name",
        "email",
        "phone_number",
        "logo_path",
        "is_active"
    ];

    protected $appends = [
        "logo_url"
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class,"company_id","id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLogoUrlAttribute()
    {
        return url('/storage/' . $this->logo);
    }
}
