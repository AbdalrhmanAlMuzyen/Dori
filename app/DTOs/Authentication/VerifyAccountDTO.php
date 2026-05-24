<?php

namespace App\DTOs\Authentication;

class VerifyAccountDTO{
    public string $email;
    public string $verification_token;

    public function __construct(string $email , string $verification_token)
    {
        $this->email=$email;
        $this->verification_token=$verification_token;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("email"),$request->input("verification_token"));
    }
}