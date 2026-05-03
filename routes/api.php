<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});








// مسار محمي (للتجربة)
Route::middleware(['auth:sanctum', 'role:warehouse_manager'])->get('/warehouse/test', function () {
    return response()->json([
        'status'  => 'success',
        'code'    => 'ACCESS_GRANTED',
        'message' => 'مرحباً بك في لوحة المستودع.'
    ]);
});
