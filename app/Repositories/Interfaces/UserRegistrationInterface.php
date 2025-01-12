<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRegistrationInterface
{
    public function registration(array $data): User;
    public function activation(string $token): bool;
}
