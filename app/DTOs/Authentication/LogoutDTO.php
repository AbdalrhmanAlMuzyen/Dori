<?php

namespace App\DTOs\Authentication;

class LogoutDTO{

    public string $device_id;
    public string $refresh_token;
    public string $fcm_token;

    public function __construct(string $device_id,string $refresh_token,string $fcm_token)
    {
        $this->device_id=$device_id;
        $this->refresh_token=$refresh_token;
        $this->fcm_token=$fcm_token;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("device_id"),$request->input("refresh_token"),$request->input("fcm_token"));
    }
}