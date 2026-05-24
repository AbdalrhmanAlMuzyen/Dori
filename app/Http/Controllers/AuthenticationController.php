<?php

namespace App\Http\Controllers;

use App\DTOs\Authentication\ForgetPasswordDTO;
use App\DTOs\Authentication\HandleMobileTokenRotationDTO;
use App\DTOs\Authentication\RegisterDTO;
use App\DTOs\Authentication\LoginDTO;
use App\DTOs\Authentication\LogoutDTO;
use App\DTOs\Authentication\ResendVerificationEmailDTO;
use App\DTOs\Authentication\ResetPasswordDTO;
use App\DTOs\Authentication\VerifyAccountDTO;
use App\Http\Requests\Authentication\ForgetPasswordRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\LogoutRequest;
use App\Http\Requests\Authentication\ResendVerificationEmailRequest;
use App\Http\Requests\Authentication\VerifyAccountRequest;
use App\Http\Requests\Authentication\HandleMobileTokenRotationRequest;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Services\AuthenticationService;

class AuthenticationController extends Controller
{
    protected $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService=$authenticationService;
    }

    public function register(RegisterRequest $request)
    {
        $result=$this->authenticationService->register(RegisterDTO::FromRequest($request));
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);
    }

    public function resendVerificationEmail(ResendVerificationEmailRequest $request)
    {
        $result=$this->authenticationService->resendVerificationEmail(ResendVerificationEmailDTO::FromRequest($request));
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);
    }

    public function verfiyAccount(VerifyAccountRequest $request)
    {
        $result=$this->authenticationService->verifyAccount(VerifyAccountDTO::FromRequest($request));
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $result=$this->authenticationService->forgetPassword(ForgetPasswordDTO::FromRequest($request));
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);        
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result=$this->authenticationService->resetPassword(ResetPasswordDTO::FromRequest($request));
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);  
    }

    public function Login(LoginRequest $request)
    {
        $result = $this->authenticationService->Login(LoginDTO::FromRequest($request));
        if($result["success"])
        {
            $cookie=cookie("refresh_token",$result["data"]["refresh_token"],60 * 24 * 30, "/",null,true,true,false,"Strict");
            return response(["success" => false,"message" => $result["message"],"data" => $result["data"]], $result["code"])->withCookie($cookie);
        }    
        return response(["success" => false,"message" => $result["message"],"data" => $result["data"]], $result["code"]);
    }  

    public function logout(LogoutRequest $request)
    {
        $result=$this->authenticationService->logout(LogoutDTO::FromRequest($request));
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);        
    }

    public function handleMobileTokenRotation(HandleMobileTokenRotationRequest $request)
    {
        $result=$this->authenticationService->handleMobileTokenRotation(HandleMobileTokenRotationDTO::FromRequest($request));
        return response(["success"=>$result["success"] , "message"=>$result["message"] , "data"=>$result["data"]] , $result["code"]);        
    }

    
}
