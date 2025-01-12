<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class OptionalAuthenticateWithJwt
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        if ($token) {
            try {
                $token = str_replace('Bearer ', '', $token);

                $user = JWTAuth::setToken($token)->authenticate();

                if ($user) {
                    Auth::login($user);
                }
            } catch (\Exception $e) {
            }
        }

        return $next($request);
    }
}
