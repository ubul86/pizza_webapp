<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class VerifyRefreshTokenMiddleware
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();

            if (!$payload->get('is_refresh_token')) {
                return response()->json(['message' => 'Invalid token type.'], 402);
            }

            $user = User::find($payload['sub']);
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 402);
        }

        return $next($request);
    }
}
