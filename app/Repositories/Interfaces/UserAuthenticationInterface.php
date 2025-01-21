<?php

namespace App\Repositories\Interfaces;

interface UserAuthenticationInterface
{
    public function login(array $data, int $isAdmin = 0): mixed;
    public function logout(string $token): bool;
}
