<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class OptionalAuthenticateWithJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return Response|JsonResponse
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        if ($token) {
            try {
                $token = str_replace('Bearer ', '', $token);

                $user = JWTAuth::setToken($token)->authenticate();

                if ($user) {
                    auth()->login($user);
                }
            } catch (\Exception $e) {
            }
        }

        return $next($request);
    }
}
