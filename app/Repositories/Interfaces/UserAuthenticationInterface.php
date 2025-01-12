<?php

namespace App\Repositories\Interfaces;

interface UserAuthenticationInterface
{
    public function login(array $data): mixed;
    public function logout(string $token): bool;
}
