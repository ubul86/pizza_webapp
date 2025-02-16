<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use HandleJsonResponse;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        try {
            $users =  $this->userService->index();
            return $this->successResponse($users);
        } catch (\Exception $e) {
            return $this->errorResponse($e, 404);
        }
    }

    public function getAuthenticatedUser(): JsonResponse
    {
        try {
            return $this->successResponse(auth()->user());
        } catch (\Exception $e) {
            return $this->errorResponse($e, 404);
        }
    }
}
