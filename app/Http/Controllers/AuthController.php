<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\Interfaces\UserAuthenticationInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RuntimeException;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected UserAuthenticationInterface $authRepository;

    public function __construct(UserAuthenticationInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            $arrayOfTokens = $this->authRepository->login($credentials);

            return response()->json($arrayOfTokens);
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $token = $request->header('Authorization', '');

            if (strpos($token, 'Bearer ') === 0) {
                $token = substr($token, 7);
            }

            if ($this->authRepository->logout($token)) {
                return response()->json([
                    'message' => 'Successfully logged out'
                ]);
            }

            throw new RuntimeException('Could not invalidate token', 500);
        } catch (JWTException $e) {
            throw $e;
        }
    }

    public function refreshToken(): JsonResponse
    {
        try {
            $user = auth()->user();
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'token' => $token,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Could not refresh token'], 402);
        }
    }
}
