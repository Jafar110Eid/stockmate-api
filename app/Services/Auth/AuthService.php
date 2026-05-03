<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * تسجيل دخول المستخدم وإنشاء توكن المصادقة
     *
     * @param string $email
     * @param string $password
     * @param string $role
     * @return array {user: User, token: string}
     * @throws ValidationException
     */
    public function login(string $email, string $password, string $role): array
    {
        // 1. البحث عن المستخدم بالبريد الإلكتروني
        $user = User::where('email', $email)->first();

        // 2. التحقق من وجود المستخدم وصحة كلمة المرور
        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['بيانات الدخول غير صحيحة.'],
            ]);
        }

        // 3. التحقق من تطابق الدور (Role) مع معالجة آمنة لقيمة NULL
        // نستخدم ؟->value لاستخراج قيمة الـ Enum، أو نأخذ القيمة مباشرة إذا كانت نصاً
        $userRoleValue = $user->role?->value ?? $user->role;

        if ($userRoleValue !== $role) {
            throw ValidationException::withMessages([
                'role' => ['الدور المختار لا يتطابق مع صلاحيات حسابك.'],
            ]);
        }

        // 4. إنشاء توكن جديد باستخدام Sanctum
        $token = $user->createToken('stockmate-api')->plainTextToken;

        // 5. إرجاع البيانات
        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /*
     * @param \App\Models\User $user
     * @return bool
     */
    public function logout(\App\Models\User $user): bool
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return true;
    }







}
