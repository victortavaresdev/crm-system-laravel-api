<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Clients\ClientController;
use App\Http\Controllers\Projects\ProjectController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/login', 'login');
    Route::post('/auth/logout', 'logout')->middleware('auth:sanctum');
    Route::get('/auth/profile', 'getProfile')->middleware('auth:sanctum');

    Route::post('/auth/forgot-password', 'forgotPassword')->middleware('guest')->name('password.email');
    Route::post('/auth/reset-password', 'resetPassword')->middleware('guest')->name('password.update');
});

Route::controller(UserController::class)->group(function () {
    Route::post('/users/create', 'store');
    Route::get('/users/{id}', 'show')->middleware('auth:sanctum');
    Route::patch('/users/{id}/update', 'update')->middleware('auth:sanctum');
    Route::delete('/users/{id}/delete', 'destroy')->middleware('auth:sanctum');
});

Route::controller(ClientController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/clients/create', 'store');
    Route::get('/clients', 'index');
    Route::get('/clients/{id}', 'show');
    Route::patch('/clients/{id}/update', 'update');
    Route::delete('/clients/{id}/delete', 'destroy');
});

Route::controller(ProjectController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/projects/create', 'store');
    Route::get('/projects', 'index');
    Route::get('/projects/{id}', 'show');
    Route::patch('/projects/{id}/update', 'update');
    Route::delete('/projects/{id}/delete', 'destroy');
});
