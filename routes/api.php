<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('user/register', [UserController::class, 'store'])->name('user.store');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'me'])->name('user.me');
});
