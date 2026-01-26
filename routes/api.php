<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // index
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to POS API',
        ]);
    });

    // Auth
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {

        // Profile
        Route::controller(ProfileController::class)->prefix('profile')->group(function () {
            Route::get('/show', 'show');
            Route::patch('/change-name', 'changeName');
            Route::patch('/change-password', 'changePassword');
            Route::patch('/change-photo', 'changePhoto');
            Route::post('/logout', 'logout');
        });
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('menus', MenuController::class);
    });

});