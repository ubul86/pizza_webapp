<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AdminUserAuthenticationInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminAuthRepository implements AdminUserAuthenticationInterface
{
    public function login(array $credentials): mixed
    {

        if (! $token = JWTAuth::attempt($credentials)) {
            throw new NotFoundHttpException('Invalid credentials');
        }

        $user = auth()->user();

        if (!$user || !$user->is_admin) {
            throw new NotFoundHttpException('Unauthorized: Admin privileges required');
        }

        return $token;
    }
}
