<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    // ✅ أي مستخدم مصادق عليه يمكنه تسجيل الخروج
    public function authorize(): bool
    {
        return true;
    }

    // ✅ لا حاجة لقواعد تحقق، المصادقة (auth:sanctum) تكفي
    public function rules(): array
    {
        return [];
    }
}
