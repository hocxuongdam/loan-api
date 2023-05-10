<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\AbstractService;
use Illuminate\Support\Facades\Auth;

class AuthService extends AbstractService implements AuthServiceInterface
{

    public function login(string $email, string $password)
    {
        if (Auth::attempt([
            'email' => $email,
            'password' => $password,
        ])) {

            $user = User::query()->find(Auth::id());
            $token = $user->createToken(User::APP_TOKEN_NAME);

            return $token->plainTextToken;
        }

        return null;
    }
}
