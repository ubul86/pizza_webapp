<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Repositories\Interfaces\UserAuthenticationInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminAuthController extends Controller
{
    protected UserAuthenticationInterface $authRepository;

    public function __construct(UserAuthenticationInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(AdminLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            $arrayOfTokens = $this->authRepository->login($credentials, 1);

            return response()->json($arrayOfTokens);
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }
}
