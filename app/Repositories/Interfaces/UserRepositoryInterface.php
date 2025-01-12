<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Models\User;

interface UserRepositoryInterface
{
    /** @return EloquentCollection<int, User> */
    public function index(): EloquentCollection;
}
