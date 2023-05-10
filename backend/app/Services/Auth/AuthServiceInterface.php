<?php

namespace App\Services\Auth;

interface AuthServiceInterface
{
    public function login(string $email, string $password);
}
