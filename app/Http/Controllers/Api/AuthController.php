<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login(
            $request->email,
            $request->password,
            $request->role
        );

        return response()->json([
            'status'  => 'success',
            'code'    => 'LOGIN_SUCCESS',
            'message' => 'تم تسجيل الدخول بنجاح.',
            'data'    => [
                'user'  => new UserResource($data['user']),
                'token' => $data['token'],
            ],
        ], 200);
    }
}
