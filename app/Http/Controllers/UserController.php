<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        try {
            $users =  $this->userService->index();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }

    public function getAuthenticatedUser(): JsonResponse
    {
        try {
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }
}
