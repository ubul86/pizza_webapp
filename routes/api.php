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
use App\Http\Controllers\AdminProductController;

Route::post('/registration', [UserRegistrationController::class, 'registration']);
Route::post('/activation', [UserRegistrationController::class, 'activation'])->name('activation');

Route::post('/admin/auth/login', [AdminAuthController::class, 'login']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);

Route::get('/order', [OrderController::class])->middleware('auth.optional');

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

Route::middleware(['verify.refresh.token'])->get('/auth/refresh-token', [AuthController::class, 'refreshToken']);

/** Csak adminnak elérhető route-ok */
Route::middleware('check.admin.jwt')->group(function () {
    Route::get('/user/get-authenticated-user', [UserController::class, 'getAuthenticatedUser']);

    Route::get('/admin/product', [AdminProductController::class, 'index']);
    Route::get('/admin/product/{id}', [AdminProductController::class, 'show']);
    Route::post('/admin/product', [AdminProductController::class, 'store']);
    Route::put('/admin/product/{id}', [AdminProductController::class, 'update']);
    Route::delete('/admin/product/{id}', [AdminProductController::class, 'destroy']);
    Route::delete('/admin/product/bulk-destroy', [AdminProductController::class, 'bulkDestroy']);
    Route::post('/admin/product/upload-images/{itemId}', [AdminProductController::class, 'uploadImages']);
    Route::post('/admin/product/set-image-to-first', [AdminProductController::class, 'setImageToFirst']);
    Route::post('/admin/product/delete-image', [AdminProductController::class, 'deleteImage']);



});

Route::apiResource('user', UserController::class);
