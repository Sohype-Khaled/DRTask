<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/{shop:slug}/')
    ->group(function () {
        Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
        Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);

        Route::group(['middleware' => ['auth:sanctum']], function () {

            Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
        });
    });


