<?php

namespace App\Services;

use App\Mail\ActivationEmail;
use App\Models\Category;
use App\Models\User;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\UserRegistrationInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserRegistrationService
{
    protected UserRegistrationInterface $userRegistration;

    public function __construct(UserRegistrationInterface $userRegistration)
    {
        $this->userRegistration = $userRegistration;
    }

    public function registration(array $data): User
    {
        try {
            $user = $this->userRegistration->registration($data);

            Mail::to($user->email)->send(new ActivationEmail($user));

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function activation(string $token): bool
    {
        $status = $this->userRegistration->activation($token);

        if (!$status) {
            throw new NotFoundHttpException('Invalid token');
        }

        return true;
    }
}
