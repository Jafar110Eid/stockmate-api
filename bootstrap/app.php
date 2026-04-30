<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // تسجيل الاسم المستعار لـ CheckRole Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // ─────────────────────────────────────────────
        // 1️⃣ معالجة أخطاء التحقق (422) - موحدة
        // ─────────────────────────────────────────────
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 'VALIDATION_ERROR',
                    'message' => 'فشل التحقق من البيانات المدخلة.',
                    'errors'  => $e->errors()
                ], 422);
            }
            // للطلبات غير الـ API نترك السلوك الافتراضي
            return null;
        });

        // ─────────────────────────────────────────────
        // 2️⃣ معالجة أخطاء المصادقة (401) - موحدة
        // ─────────────────────────────────────────────
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 'UNAUTHORIZED',
                    'message' => 'يجب تسجيل الدخول أولاً.'
                ], 401);
            }
            // للطلبات غير الـ API نترك السلوك الافتراضي
            return null;
        });

    })->create();
