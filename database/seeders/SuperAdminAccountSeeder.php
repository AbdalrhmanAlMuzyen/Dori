<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "role_id"=>1,
            "first_name"=>"abdalrhman",
            "last_name"=>"almuzyen",
            "email"=>"abdalrhmanalmuzyenx@gmail.com",
            "password"=>"1234567",
            "status"=>"active",
            "email_verified_at"=>now(),
        ]);
    }
}
