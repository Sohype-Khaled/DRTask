<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    });

Route::prefix('v1/{shop:slug}/')
    ->group(function () {
        Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
        Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
        Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    });

