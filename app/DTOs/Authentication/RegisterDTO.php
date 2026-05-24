<?php

namespace App\DTOs\Authentication;

class RegisterDTO{

    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $role_id;

    public function __construct($first_name,$last_name,$email,$password,$role_id)
    {
        $this->first_name=$first_name;
        $this->last_name=$last_name;
        $this->email=$email;
        $this->password=$password;
        $this->role_id=$role_id;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("first_name"),$request->input("last_name"),$request->input("email"),$request->input("password"),$request->input("role_id"));
    }
}