<?php

namespace App\Services;

use App\DTOs\Authentication\ForgetPasswordDTO;
use App\DTOs\Authentication\HandleMobileTokenRotationDTO;
use App\DTOs\Authentication\LoginDTO;
use App\DTOs\Authentication\LogoutDTO;
use App\DTOs\Authentication\RegisterDTO;
use App\DTOs\Authentication\ResendVerificationEmailDTO;
use App\DTOs\Authentication\ResetPasswordDTO;
use App\DTOs\Authentication\VerifyAccountDTO;
use App\Events\RegisteredEvent;
use App\Events\ResendVerificationEmailEvent;
use App\Events\SendFireBaseNotificationEvent;
use App\Events\SendResetPasswordEmailEvent;
use App\Repositories\AuthenticationRepository;
use App\Repositories\NotificationRepository;
use App\ReturnTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationService{
    use ReturnTrait;

    protected $authenticationRepository;
    protected $notificationRepository;

    public function __construct(AuthenticationRepository $authenticationRepository , NotificationRepository $notificationRepository)
    {
        $this->authenticationRepository=$authenticationRepository;
        $this->notificationRepository=$notificationRepository;
    }

    public function register(RegisterDTO $dto)
    {
        try{
            $verification_token=Str::random(64);
            $user=$this->authenticationRepository->register($dto,$verification_token);
            $link="https://tux-hummus-crunchy.ngrok-free.dev/email-verification?verification_token=".$verification_token."&email=".urlencode($user->email);
            event(new RegisteredEvent($user->email,$user->first_name,$user->last_name,$link));
            return $this->success(true,"Registration successful. Please check your email and verify your account.",["user"=>$user],201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,"Something went wrong. Please try again later.".$e->getMessage(),null,500);
        }
    }

    public function resendVerificationEmail(ResendVerificationEmailDTO $dto)
    {
        try {

            $user = $this->authenticationRepository->findUserByEmail($dto->email);

            if (!$user || $user->email_verified_at) {
                return $this->success(false,"User not found or email already verified",[],200);
            }

            $verification_token = Str::random(64);

            $this->authenticationRepository->updateVerificationToken($user, $verification_token);

            $link = "https://yourfrontend.com/verify-email?verification_token=". $verification_token ."&email=" . urlencode($user->email);

            event(new ResendVerificationEmailEvent($user->email,$user->first_name,$user->last_name,$link));

            return $this->success(true,"Verification email resent successfully.",[],200);
        } 
        catch (\Exception $e) {
            return $this->error(false,"Something went wrong. Please try again later.".$e->getMessage(),null,500);
        }
    }

    public function verifyAccount(VerifyAccountDTO $dto)
    {
        try {

            $user = $this->authenticationRepository->findUserByEmail($dto->email);

            if (!$user) {
                return $this->error(false, "User not found", [], 404);
            }

            if (Carbon::parse($user->expires_at)->isPast()) {
                return $this->error(false, "Verification token expired", [], 410);
            }

            if ($user->verification_token !== $dto->verification_token) {
                return $this->error(false, "Invalid verification token", [], 401);
            }

            $this->authenticationRepository->verifyAccount($user);
            return $this->success(true, "Account verified successfully", [], 200);

        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage(), [], 500);
        }
    }

    public function forgetPassword(ForgetPasswordDTO $dto)
    {
        try {

            $user = $this->authenticationRepository->findUserByEmail($dto->email);

            if (!$user) {
                return $this->error(false, "User not found", [], 404);
            }

            $token = Str::random(36);

            $this->authenticationRepository->createResetPassword($user, $token);

            $link = "https://react-native.com/reset/password?token=" . $token . "&email=" . urlencode($user->email);

            event(new SendResetPasswordEmailEvent($user->email,$user->first_name,$user->last_name,$link));

            return $this->success(true,"Password reset link has been sent to your email",[],200);
        } 
        catch (\Exception $e) {
            return $this->error(false,"Something went wrong. Please try again later.".$e->getMessage(),null,500);
        }
    }

    public function resetPassword(ResetPasswordDTO $dto)
    {
        try {
            DB::beginTransaction();

            $user = $this->authenticationRepository->findUserByEmail($dto->email);

            if (!$user) {
                return $this->error(false, "User not found", [], 404);
            }

            $reset_password = $this->authenticationRepository->findResetPasswordToken($user, $dto->token);

            if (
                !$reset_password ||
                Carbon::parse($reset_password->expires_at)->isPast() ||
                $reset_password->is_used
            ) {
                DB::rollBack();
                return $this->error(false, "Invalid or expired reset token", [], 401);
            }

            $this->authenticationRepository->resetPassword($user, $dto->new_password);
            $this->authenticationRepository->markResetPasswordAsUsed($reset_password);
            $this->authenticationRepository->revokeAllUserSessions($user);

            $user->increment("token_version");

            DB::commit();

            return $this->success(true, "Password reset successfully", [], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return $this->error(false,"Something went wrong. Please try again later.".$e->getMessage(),null,500);
        }
    }

    public function Login(LoginDTO $dto)
    {
        try {
            DB::beginTransaction();

            if (!$access_token = Auth::guard("api")->attempt(["email" => $dto->email,"password" => $dto->password])) 
            {
                DB::rollBack();
                return $this->error(false, "Invalid credentials", [], 401);
            }

            $user = Auth::guard("api")->user();

            if (!$user->email_verified_at) {
                DB::rollBack();
                return $this->error(false, "Email is not verified", [], 403);
            }

            $user_device = $this->authenticationRepository->findUserDeviceByDeviceId($user, $dto->device_id);

            $refresh_token = Str::random(36);

            if (!$user_device) {

                $new_user_device = $this->authenticationRepository->createUserDevice($user, $dto);

                $this->authenticationRepository->createRefreshToken($user, $new_user_device, $refresh_token);

                $this->authenticationRepository->createDeviceToken($user, $dto->fcm_token, $new_user_device);

                $fcm_tokens = $this->authenticationRepository->getActiveFcmTokens($user, $new_user_device);

                $notification = $this->notificationRepository->createNotification($user,"Security Alert - New Login","A new device has signed in to your account.","security",null);

                event(new SendFireBaseNotificationEvent($notification, $fcm_tokens));

                DB::commit();

                return $this->success(true, "Login successfully", [
                    "access_token" => $access_token,
                    "refresh_token" => $refresh_token,
                    "user" => $user
                ]);
            }

            $this->authenticationRepository->updateUserDevice($user_device, true);

            $refresh_token_model = $this->authenticationRepository->createRefreshToken($user, $user_device, $refresh_token);

            $this->authenticationRepository->createDeviceToken($user, $dto->fcm_token, $user_device);

            DB::commit();

            return $this->success(true, "Login successfully", [
                "access_token" => $access_token,
                "refresh_token" => $refresh_token_model->refresh_token,
                "user" => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(false,"Something went wrong. Please try again later.".$e->getMessage(),null,500);
        }
    }

    public function logout(LogoutDTO $dto)
    {
        try{
            $user = Auth::guard("api")->user();

            $access_token = request()->bearerToken() ?? request()->cookie("jwt");

            JWTAuth::setToken($access_token)->invalidate();

            $user_device = $this->authenticationRepository->findUserDeviceByDeviceId($user, $dto->device_id);

            if (!$user_device) {
                return $this->error(false, "Device not found", [], 404);
            }

            $this->authenticationRepository->updateUserDevice($user_device, false);

            $refresh_token = $this->authenticationRepository->findRefreshToken($user, $dto->refresh_token);

            if ($refresh_token) {
                $this->authenticationRepository->markRefreshTokenAsRevoked($refresh_token);
            }   

            $device_token=$this->authenticationRepository->findDeviceToken($user,$dto->fcm_token);

            if ($device_token) {
                $this->authenticationRepository->updateDeviceToken($device_token);
            }            

            $fcm_tokens = $this->authenticationRepository->getActiveFcmTokens($user, $user_device);

            $notification = $this->notificationRepository->createNotification($user,"Device Logged Out","One of your devices has been logged out. If you didn't perform this action, change your password immediately.","security",null);

            event(new SendFireBaseNotificationEvent($notification, $fcm_tokens));     

            return $this->success(true, "Logged out successfully", []);

        }
        catch(\Exception $e)
        {
            return $this->error(false,"Something went wrong. Please try again later.".$e->getMessage(),null,500);
        }
    }


    public function handleMobileTokenRotation(HandleMobileTokenRotationDTO $dto)
    {
        try {

            DB::beginTransaction();

            $user = Auth::guard("api")->user();

            if (!$user) {
                DB::rollBack();
                return $this->error(false, "Unauthorized", [], 401);
            }

            $refresh_token = $this->authenticationRepository->findRefreshToken($user, $dto->refresh_token);

            if (!$refresh_token || Carbon::parse($refresh_token->expires_at)->isPast() || $refresh_token->is_revoked) 
            {
                DB::rollBack();
                return $this->error(false,"Invalid or expired refresh token",[],401);
            }

            $this->authenticationRepository->markRefreshTokenAsRevoked($refresh_token);

            $old_access_token = request()->bearerToken();

            if ($old_access_token) {
                JWTAuth::setToken($old_access_token)->invalidate();
            }

            $new_access_token = JWTAuth::fromUser($user);

            $new_refresh_token = Str::random(36);

            $user_device = $this->authenticationRepository->findUserDeviceByDeviceId($user, $dto->device_id);

            $this->authenticationRepository->createRefreshToken($user, $user_device, $new_refresh_token);

            DB::commit();

            return $this->success(true, "Token rotated successfully", [
                "access_token" => $new_access_token,
                "refresh_token" => $new_refresh_token
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(false,"Something went wrong. Please try again later.".$e->getMessage(),null,500);
        }
    }
}