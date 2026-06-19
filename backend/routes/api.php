<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Cashier\TransactionController;
use App\Http\Controllers\Api\Cashier\TransactionItemController;
use App\Http\Controllers\Api\Cashier\ReceiptController;
use App\Http\Controllers\Api\Admin\DashboardController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth khusus user yang sedang login
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Grup Admin (User Management)
    Route::prefix('admin')->group(function () {
        // User Routes
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus']);

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index']);

        // Category Routes
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{id}', [CategoryController::class, 'show']);

        // Product Routes
        Route::get('products', [ProductController::class, 'index']);
        Route::post('products', [ProductController::class, 'store']);
        Route::get('products/{id}', [ProductController::class, 'show']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::patch('products/{id}/deactivate', [ProductController::class, 'deactivate']);
    });

    Route::prefix('cashier')
        ->middleware(['auth:sanctum'])
        ->group(function () {

            Route::apiResource(
                'transactions',
                TransactionController::class
            )->only(['index', 'show', 'store']);

            Route::post(
                'transactions/{id}/pay',
                [TransactionController::class, 'pay']
            );

            Route::post(
                'transactions/{id}/cancel',
                [TransactionController::class, 'cancel']
            );

            Route::post(
                'transaction-items',
                [TransactionItemController::class, 'store']
            );

            Route::put(
                'transaction-items/{id}',
                [TransactionItemController::class, 'update']
            );

            Route::delete(
                'transaction-items/{id}',
                [TransactionItemController::class, 'destroy']
            );

            Route::get(
                'receipts/{id}',
                [ReceiptController::class, 'show']
            );
        });
});
