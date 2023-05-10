<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthServiceInterface;

class AuthController extends ApiController
{
    public function __construct(private AuthServiceInterface $service)
    {
    }

    /**
     * Login
     *
     * @group Auth
     *
     * @response 200 {"data": {"token": "bearer_token"}, "success": {"message": "Success"}}
     * @response 401 {"errors": "Unauthenticated"}
     *
     * @unauthenticated
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if ($token = $this->service->login($request->input('email'), $request->input('password'))) {
            return $this->respondSuccess([
                'token' => $token,
            ]);
        }

        return $this->respondUnauthorized();
    }
}
