<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Client\ClientController;
use App\Http\Controllers\Api\V1\Project\ProjectController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', 'logout');
        Route::get('/auth/profile', 'getProfile');
    });

    Route::middleware('guest')->group(function () {
        Route::post('/auth/forgot-password', 'forgotPassword')->name('password.email');
        Route::post('/auth/reset-password', 'resetPassword')->name('password.update');
    });

    Route::post('/auth/login', 'login');
});

Route::controller(UserController::class)->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users/{user}', 'show');
        Route::put('/users/{user}/update', 'update');
        Route::delete('/users/{user}/delete', 'destroy');
    });

    Route::post('/users/create', 'store');
});

Route::controller(ClientController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/clients/create', 'store');
    Route::get('/clients', 'index');
    Route::get('/clients/{client}', 'show');
    Route::put('/clients/{client}/update', 'update');
    Route::delete('/clients/{client}/delete', 'destroy');
});

Route::controller(ProjectController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/projects/create', 'store');
    Route::get('/projects', 'index');
    Route::get('/projects/{project}', 'show');
    Route::put('/projects/{project}/update', 'update');
    Route::delete('/projects/{project}/delete', 'destroy');
});
