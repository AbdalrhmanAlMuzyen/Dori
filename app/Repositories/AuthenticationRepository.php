<?php

namespace App\Repositories;

use App\DTOs\Authentication\LoginDTO;
use App\DTOs\Authentication\RegisterDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationRepository{

    public function register(RegisterDTO $dto,$verification_token)
    {
        return User::create([
            "first_name"=>$dto->first_name,
            "last_name"=>$dto->last_name,
            "email"=>$dto->email,
            "password"=>Hash::make($dto->password),
            "verification_token"=>$verification_token,
            "expires_at"=>now()->addMinutes(30),
            "role_id"=>$dto->role_id
        ]);
    }   

    public function findUserDeviceByDeviceId($user,$device_id)
    {
        return $user->userDevices()->where("device_id",$device_id)->first();
    }

    public function createUserDevice($user,LoginDTO $dto)
    {
        return $user->userDevices()->create([
            "device_id"=>$dto->device_id,
            "device"=>$dto->device,
            "platform"=>$dto->platform
        ]);
    }

    public function getActiveFcmTokens($user,$user_device)
    {
        return $user->deviceTokens()->where("is_revoked",false)->where("user_device_id","!=",$user_device->id)->pluck("fcm_token")->toArray();
    }

    public function updateUserDevice($user_device,$is_active)
    {
        return $user_device->update([
            "is_active"=>$is_active
        ]);
    }

    public function createRefreshToken($user,$user_device,$refresh_token)
    {
        return $user->refreshTokens()->create([
            "user_device_id"=>$user_device->id,
            "refresh_token"=>$refresh_token,
            "expires_at"=>now()->addDays(30)
        ]);
    }

    public function createDeviceToken($user,$fcm_token,$user_device_id)
    {
        return $user->deviceTokens()->create([
            "user_device_id"=>$user_device_id->id,
            "fcm_token"=>$fcm_token,
        ]);
    }

    public function findUserByEmail($email)
    {
        return User::where("email",$email)->first();
    }

    public function verifyAccount($user)
    {
        return $user->update([
            "email_verified_at"=>now(),
            "verification_token"=>null,
            "expires_at"=>null,
            "status"=>"active"
        ]);
    }

    public function findRefreshToken($user,$refresh_token)
    {
        return $user->refreshTokens()->where("refresh_token",$refresh_token)->first();
    }

    public function findDeviceToken($user,$fcm_token)
    {
        return $user->deviceTokens()->where("fcm_token",$fcm_token)->first();
    }

    public function markRefreshTokenAsRevoked($refresh_token)
    {
        return $refresh_token->update([
            "is_revoked"=>true
        ]);
    }

    public function updateDeviceToken($device_token)
    {
        return $device_token->update([
            "is_revoked"=>true
        ]);
    }
    
    public function createResetPassword($user,$token)
    {
        return $user->resetPasswords()->create([
            "token"=>$token,
            "expires_at"=>now()->addMinutes(30)
        ]);
    }

    public function findResetPasswordToken($user,$token)
    {
        return $user->resetPasswords()->where("token",$token)->first();
    }

    public function resetPassword($user,$new_password)
    {
        return $user->update([
            "password"=>Hash::make($new_password)
        ]);
    }

    public function markResetPasswordAsUsed($reset_password)
    {
        return $reset_password->update([
            "is_used"=>true
        ]);
    }

    public function revokeAllUserSessions($user)
    {
        $devices = $user->userDevices()->update([
            "is_active" => false
        ]);

        $refresh_tokens = $user->refreshTokens()->update([
            "is_revoked" => true
        ]);

        $device_tokens = $user->deviceTokens()->update([
            "is_revoked" => true
        ]);

        return [
            "devices" => $devices,
            "refresh_tokens" => $refresh_tokens,
            "device_tokens" => $device_tokens,
        ];
    }

    public function updateVerificationToken($user, $token)
    {
        return $user->update([
            "verification_token" => $token,
            "expires_at" => now()->addMinutes(30)
        ]);
    }
}