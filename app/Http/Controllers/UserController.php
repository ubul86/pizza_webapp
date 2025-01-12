<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): JsonResponse
    {
        $users =  $this->userRepository->index();
        return response()->json($users);
    }
}
