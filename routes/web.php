<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::get('register', [AdminAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::middleware('auth:admin')->group(function () {
        Route::get('home', [AdminController::class, 'index'])->name('home');
        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');

    });
});
