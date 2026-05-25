<?php
namespace App\DTOs\Company;

class CreateCompanyDTO{
    public string $name;
    public string $email;
    public string $phone_number;

    public function __construct(string $name,string $email,string $phone_number)
    {
        $this->name=$name;
        $this->email=$email;
        $this->phone_number=$phone_number;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("name"),$request->input("email"),$request->input("phone_number"));
    }
}