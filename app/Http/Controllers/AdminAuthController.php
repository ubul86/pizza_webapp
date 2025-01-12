<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Repositories\Interfaces\AdminUserAuthenticationInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RuntimeException;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthController extends Controller
{
    protected AdminUserAuthenticationInterface $authRepository;

    public function __construct(AdminUserAuthenticationInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(AdminLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            $token = $this->authRepository->login($credentials);

            return response()->json([
                'token' => $token,
            ]);
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }
}
