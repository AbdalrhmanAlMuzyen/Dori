<?php

namespace App\DTOs\Company;

class CreateCompanyDTO{
    public string $name;
    public string $email;
    public string $phone_number;
    public string $logo;
    public string $booking_mode;

    public function __construct(string $name,string $email,string $phone_number,string $logo,string $booking_mode)
    {
        $this->name=$name;
        $this->email=$email;
        $this->phone_number=$phone_number;
        $this->logo=$logo;
        $this->booking_mode=$booking_mode;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("name"),$request->input("email"),$request->input("phone_number"),$request->file("logo"),$request->input("booking_mode"));
    }
}