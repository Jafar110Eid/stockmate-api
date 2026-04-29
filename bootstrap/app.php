<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // تسجيل الاسم المستعار للـ Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // توحيد صيغة أخطاء التحقق (422) لتطابق عقد الـ API
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 'VALIDATION_ERROR',
                    'message' => 'فشل التحقق من البيانات المدخلة.',
                    'errors'  => $e->errors()
                ], 422);
            }
            // للطلبات غير الـ API نعيد السلوك الافتراضي
            return parent::render($request, $e);
        });
    })->create();
