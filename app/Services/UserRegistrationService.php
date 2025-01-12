<?php

namespace App\Services;

use App\Mail\ActivationEmail;
use App\Models\User;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\UserRegistrationInterface;
use Exception;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserRegistrationService
{
    protected UserRegistrationInterface $userRegistration;
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(UserRegistrationInterface $userRegistration, OrderRepositoryInterface $orderRepository)
    {
        $this->userRegistration = $userRegistration;
        $this->orderRepository = $orderRepository;
    }

    public function registration(array $data): User
    {
        try {
            $user = $this->userRegistration->registration($data);

            $this->orderRepository->assignUserToOrdersByEmail($user->id, $user->email);

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
