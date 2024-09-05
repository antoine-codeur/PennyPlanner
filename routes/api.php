<?php
// routes/api.php

use App\Http\Controllers\Api\V1\AdminUserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Routes API Version 1
Route::prefix('/v1')->group(function () {
    // Routes pour l'authentification
    Route::post('/login', [AuthController::class, 'login']); // Connexion
    Route::post('/register', [AuthController::class, 'register']); // Inscription

    // Routes protégées par authentification
    Route::middleware('auth:sanctum')->group(function () {
        // Routes for public users
        Route::get('/users/profile', [UserController::class, 'profile']);
        Route::put('/users/profile', [UserController::class, 'updateProfile']);
    
        // Routes for transactions
        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::put('/transactions/{id}', [TransactionController::class, 'update']);
        Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
        Route::get('/transactions', [TransactionController::class, 'show']);
    
        // Routes for admins
        Route::middleware('check.admin')->prefix('/admin')->group(function () {
            Route::get('/users', [AdminUserController::class, 'index']);
            Route::get('/users/{id}', [AdminUserController::class, 'show']);
            Route::put('/users/{id}', [AdminUserController::class, 'update']);
            Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);
        });
    });
    
});
