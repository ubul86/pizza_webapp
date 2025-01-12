<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivationRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\UserRegistrationService;
use Illuminate\Http\JsonResponse;

class UserRegistrationController extends Controller
{
    protected UserRegistrationService $userRegistrationService;

    public function __construct(UserRegistrationService $userRegistrationService)
    {
        $this->userRegistrationService = $userRegistrationService;
    }

    public function registration(RegistrationRequest $request): JsonResponse
    {
        try {
            $this->userRegistrationService->registration($request->toArray());

            return response()->json(['message' => 'Registration is success! Please check your mail to activate your user.']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function activation(ActivationRequest $request): JsonResponse
    {
        try {
            $this->userRegistrationService->activation($request->token);

            return response()->json(['message' => 'User activated successfully!']);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
