<?php

namespace App\Http\Middleware;

use App\Models\RefreshToken;
use Illuminate\Support\Str;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class JWTAuthenticationMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
       
        if(request()->bearerToken())
        {
            $response = $this->handleMobile();

            if ($response) {
                return $response;
            }        
        }
        elseif(request()->cookie("access_token"))
        {
            $response = $this->handleWeb();

            if ($response) {
                return $response;
            } 
        }        
        else{
            return response([]);
        }
        return $next($request);
    }

    public function handleMobile()
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    "success" => false,
                    "message" => "User not found"
                ], 404);
            }

            return null;
        } 
        catch (TokenExpiredException $e) {

            return response()->json([
                "success" => false,
                "message" => "Token expired"
            ], 401);

        } 
        catch (TokenInvalidException $e) {

            return response()->json([
                "success" => false,
                "message" => "Token invalid"
            ], 401);

        } 
        catch (TokenBlacklistedException $e) {

            return response()->json([
                "success" => false,
                "message" => "Token blacklisted"
            ], 401);

        } 
        catch (JWTException $e) {

            return response()->json([
                "success" => false,
                "message" => "Token absent"
            ], 401);

        }
    }

    public function handleWeb()
    {
        try {

            $user = JWTAuth::setToken(request()->cookie("access_token"))->authenticate();
            Auth::guard("api")->setUser($user);

            if (!$user) {
                return response()->json([
                    "success" => false,
                    "message" => "User not found"
                ], 404);
            }
            return null;
        } 
        catch (TokenExpiredException $e) {

            $old_refresh_token = request()->cookie("refresh_token");

            $refresh_token = RefreshToken::where("refresh_token", $old_refresh_token)->where("is_revoked", false)->where("expires_at", ">", now())->first();

            if (!$refresh_token) {
                return response()->json(["success" => false,"message" => "Invalid or expired refresh token"], 401);
            }

            $user = $refresh_token->user;

            if (!$user) {
                return response()->json(["success" => false,"message" => "User not found"], 404);
            }

            Auth::guard("api")->setUser($user);
            
            $refresh_token->update([
                "is_revoked" => true
            ]);

            $new_refresh_token = Str::random(64);

            RefreshToken::create([
                "user_id" => $user->id,
                "user_device_id" => $refresh_token->user_device_id,
                "refresh_token" => $new_refresh_token,
                "expires_at" => now()->addDays(7),
                "is_revoked" => false
            ]);

            $new_access_token = JWTAuth::fromUser($user);

            cookie()->queue(cookie("access_token",$new_access_token,60 * 24,"/",null,true,true));

            cookie()->queue(cookie("refresh_token",$new_refresh_token,60 * 24 * 7,"/",null,true,true));
            
            return null;

        }
        catch (TokenInvalidException $e) {

            return response()->json([
                "success" => false,
                "message" => "Token invalid"
            ], 401);

        } 
        catch (TokenBlacklistedException $e) {

            return response()->json([
                "success" => false,
                "message" => "Token blacklisted"
            ], 401);

        } 
        catch (JWTException $e) {

            return response()->json([
                "success" => false,
                "message" => "Token absent"
            ], 401);

        }
    }
}