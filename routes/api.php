<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::post('user/register', [UserController::class, 'store'])->name('user.store');
Route::post('user/login', [UserController::class, 'login'])->name('user.login');
Route::post('user/forgot_password', [UserController::class, 'forgotPassword'])->name('user.forgot_password');
Route::post('user/{id}/reset_password', [UserController::class, 'resetPassword'])->name('user.reset_password');

Route::get('/books', [BookController::class, 'index'])->name('book.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'me'])->name('user.me');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::post('user/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::put('user/confirm_email', [UserController::class, 'confirmEmail'])->name('user.confirm_email');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');

    Route::post('/books', [BookController::class, 'store'])->name('book.store');
});
