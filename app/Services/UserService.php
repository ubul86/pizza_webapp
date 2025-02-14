<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /** @return EloquentCollection<int, User> */
    public function index(): EloquentCollection
    {
        try {
            return $this->userRepository->index();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
