<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserRegistrationController;

Route::post('/registration', [UserRegistrationController::class, 'registration']);
Route::post('/activation', [UserRegistrationController::class, 'activation'])->name('activation');

Route::post('/admin/auth/login', [AdminAuthController::class, 'login']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/admin/auth/refresh-token', [AuthController::class, 'refreshToken']);

Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::delete('/product/bulk-destroy', [ProductController::class, 'bulkDestroy']);

Route::post('/product/upload-images/{itemId}', [ProductController::class, 'uploadImages'])->middleware('check.admin.jwt');;

Route::get('/order', [OrderController::class])->middleware('auth.optional');

Route::get('/user/get-authenticated-user', [UserController::class, 'getAuthenticatedUser']);

Route::apiResource('user', UserController::class);
Route::apiResource('order', OrderController::class);
Route::apiResource('category', CategoryController::class);

Route::prefix('/order/{order}')
    ->group(function () {
        Route::get('/order-item', [OrderItemController::class, 'index']);
        Route::get('/order-item/{id}', [OrderItemController::class, 'show']);
        Route::post('/order-item', [OrderItemController::class, 'store']);
        Route::put('/order-item/{id}', [OrderItemController::class, 'update']);
        Route::delete('/order-item/{id}', [OrderItemController::class, 'destroy']);
    });

Route::middleware('auth.api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::middleware('check.admin.jwt')->group(function () {
    Route::post('/product', [ProductController::class, 'store']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
});
