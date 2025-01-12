<?php

namespace App\Repositories;

use App\Mail\ActivationEmail;
use App\Models\User;
use App\Repositories\Interfaces\UserRegistrationInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserRegistrationRepository implements UserRegistrationInterface
{
    public function registration(array $data): User
    {
        $collectedData = collect($data);

        $user = User::create([
            'name' => $collectedData->get('name'),
            'email' => $collectedData->get('email'),
            'password' => Hash::make($collectedData->get('password')),
            'activation_token' => Str::random(60),
        ]);

        return $user;
    }

    public function activation(string $token): bool
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return false;
        }

        $user->is_activated = true;
        $user->activation_token = null;
        $user->save();

        return true;
    }
}
