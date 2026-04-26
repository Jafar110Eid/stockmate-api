<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $email, string $password, string $role): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['بيانات الدخول غير صحيحة.'],
            ]);
        }

        if ($user->role->value !== $role) {
            throw ValidationException::withMessages([
                'role' => ['الدور المختار لا يتطابق مع صلاحيات حسابك.'],
            ]);
        }

        $token = $user->createToken('stockmate-api')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
