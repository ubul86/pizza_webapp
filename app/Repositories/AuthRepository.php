<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserAuthenticationInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthRepository implements UserAuthenticationInterface
{
    public function login(array $data, int $isAdmin = 0): array
    {

        if (! $token = JWTAuth::attempt($data)) {
            throw new NotFoundHttpException('Invalid credentials');
        }

        $user = auth()->user();

        if (!$user) {
            throw new NotFoundHttpException('Unauthorized: User not found');
        }

        if (!empty($isAdmin) && $user->is_admin !== $isAdmin) {
            throw new NotFoundHttpException('Unauthorized: User not found');
        }

        $tokenExpire = JWTAuth::factory()->getTTL();
        $refreshTokenExpire = $tokenExpire * 113000;

        JWTAuth::factory()->setTTL($refreshTokenExpire);
        $refreshToken = JWTAuth::claims(['is_refresh_token' => true])->fromUser(auth()->user());

        JWTAuth::factory()->setTTL($tokenExpire);

        return [
            'token' => $token,
            'token_expire' => $tokenExpire,
            'refresh_token' => $refreshToken
        ];
    }

    public function logout(string $token): bool
    {
        try {
            JWTAuth::setToken($token)->invalidate();
            return true;
        } catch (JWTException $e) {
            return false;
        }
    }
}
