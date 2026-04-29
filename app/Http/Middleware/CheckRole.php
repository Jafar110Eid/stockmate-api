<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // إذا لم يكن هناك مستخدم مصادق عليه
        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'code'    => 'UNAUTHORIZED',
                'message' => 'يجب تسجيل الدخول أولاً.'
            ], 401);
        }

        // التحقق من تطابق الدور
        if (! in_array($user->role->value, $roles)) {
            return response()->json([
                'status'  => 'error',
                'code'    => 'FORBIDDEN',
                'message' => 'لا تملك الصلاحية للوصول إلى هذا المورد.'
            ], 403);
        }

        return $next($request);
    }
}
