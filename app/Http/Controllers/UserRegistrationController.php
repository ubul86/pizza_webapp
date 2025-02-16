<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivationRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\UserRegistrationService;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\JsonResponse;

class UserRegistrationController extends Controller
{
    use HandleJsonResponse;

    protected UserRegistrationService $userRegistrationService;

    public function __construct(UserRegistrationService $userRegistrationService)
    {
        $this->userRegistrationService = $userRegistrationService;
    }

    public function registration(RegistrationRequest $request): JsonResponse
    {
        try {
            $this->userRegistrationService->registration($request->toArray());
            return $this->successResponse(['message' => 'Registration is success! Please check your mail to activate your user.']);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function activation(ActivationRequest $request): JsonResponse
    {
        try {
            $this->userRegistrationService->activation($request->token);
            return $this->successResponse(['message' => 'User activated successfully!']);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
}
