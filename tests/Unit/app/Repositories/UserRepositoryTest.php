<?php

namespace Tests\Unit;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
