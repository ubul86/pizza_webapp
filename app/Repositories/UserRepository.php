<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**  @return EloquentCollection<int, User> */
    public function index(): EloquentCollection
    {
        return User::all();
    }
}
