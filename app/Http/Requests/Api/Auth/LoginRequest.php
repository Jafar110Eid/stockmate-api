<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', Rule::in(array_column(UserRole::cases(), 'value'))],
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني.',
            'role.in'      => 'الدور المحدد غير مدعوم في النظام.',
        ];
    }
}
