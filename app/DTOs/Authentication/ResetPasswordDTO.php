<?php

namespace App\DTOs\Authentication;

class ResetPasswordDTO{
    
    public string $email;
    public string $token;
    public string $new_password;

    public function __construct(string $email,string $token,string $new_password)
    {
        $this->email=$email;
        $this->token=$token;
        $this->new_password=$new_password;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("email"),$request->input("token"),$request->input("new_password"));
    }
}