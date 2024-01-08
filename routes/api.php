<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('user/register', [UserController::class, 'store'])->name('user.store');
Route::post('user/login', [UserController::class, 'login'])->name('user.login');
Route::post('user/forgot_password', [UserController::class, 'forgotPassword'])->name('user.forgot_password');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'me'])->name('user.me');
    Route::post('user/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::put('user/confirm_email', [UserController::class, 'confirmEmail'])->name('user.confirm_email');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
});
