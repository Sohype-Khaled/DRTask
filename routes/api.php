<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/{shop:slug}/')
    ->group(function () {
        Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
        Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);

        Route::group(['middleware' => ['auth:sanctum']], function () {

            Route::group(['prefix' => 'profile'], function () {
                Route::group(['middleware' => [
                    'password.time_expiration',
                    'password.count_expiration',
                ]], function () {
                    Route::get('/', [App\Http\Controllers\API\ProfileController::class, 'profile']);
                });

                Route::post('/update-password', [App\Http\Controllers\API\ProfileController::class, 'updatePassword']);
            });
            Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
        });
    });


