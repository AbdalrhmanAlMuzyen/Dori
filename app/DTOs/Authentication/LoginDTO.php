<?php

namespace App\DTOs\Authentication;


class LoginDTO{
    public string $email;
    public string $password;
    public string $device_id;
    public string $device;
    public string $platform;
    public ?string $browser;
    public string $fcm_token;

    public function __construct(string $email,string $password,string $device_id,string $device,string $platform,string $browser = null,string $fcm_token)
    {
        $this->email=$email;
        $this->password=$password;
        $this->device_id=$device_id;
        $this->device=$device;
        $this->platform=$platform;
        $this->browser=$browser;
        $this->fcm_token=$fcm_token;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("email"),$request->input("password"),$request->input("device_id"),$request->input("device"),$request->input("platform"),$request->input("browser"),$request->input("fcm_token"));
    }    
}