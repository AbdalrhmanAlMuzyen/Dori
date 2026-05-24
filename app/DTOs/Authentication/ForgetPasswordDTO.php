<?php
namespace App\DTOs\Authentication;

class ForgetPasswordDTO{

    public string $email;

    public function __construct(string $email)
    {
        $this->email=$email;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("email"));
    }
}