<?php

namespace App\Repositories\Interfaces;

interface AdminUserAuthenticationInterface
{
    public function login(array $data): mixed;
}
