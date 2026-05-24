<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = Auth::guard("api")->user();

        if (!$user || $user->role->name != $role) {
            return response()->json([
                "success" => false,
                "message" => "Forbidden"
            ], 403);
        }

        return $next($request);
    }
}