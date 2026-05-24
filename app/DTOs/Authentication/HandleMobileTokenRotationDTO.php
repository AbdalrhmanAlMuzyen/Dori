<?php

namespace App\DTOs\Authentication;

class HandleMobileTokenRotationDTO{
    public string $refresh_token;
    public string $device_id;

    public function __construct(string $refresh_token,string $device_id)
    {
        $this->refresh_token=$refresh_token;
        $this->device_id=$device_id;
    }

    public static function FromRequest($request)
    {
        return new self($request->input("refresh_token"),$request->input("device_id"));
    }
}