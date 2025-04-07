<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\PharmacyAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PharmacyPostController;
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


Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('login', [PharmacyAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [PharmacyAuthController::class, 'login']);
    Route::get('register', [PharmacyAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [PharmacyAuthController::class, 'register']);
    Route::middleware('auth:pharmacy')->group(function () {
        Route::get('home', [PharmacyController::class, 'index'])->name('home');
        Route::get('logout', [PharmacyAuthController::class, 'logout'])->name('logout');

        //posts
        Route::get('post', [PharmacyPostController::class, 'index'])->name('post.index');
        Route::get('post/create', [PharmacyPostController::class, 'create'])->name('post.create');
        Route::post('post', [PharmacyPostController::class, 'store'])->name('post.store');
        Route::get('post/{id}', [PharmacyPostController::class, 'show'])->name('post.show');
        Route::get('post/{id}/edit', [PharmacyPostController::class, 'edit'])->name('post.edit');
        Route::put('post/{id}', [PharmacyPostController::class, 'update'])->name('post.update');
        Route::delete('post/{id}', [PharmacyPostController::class, 'destroy'])->name('post.destroy');

        //chat routes
        Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
        Route::get('chats/{id}', [ChatController::class, 'show'])->name('chats.show');
        Route::post('chats/{id}/message', [ChatController::class, 'storeMessage'])->name('chats.storeMessage');
    });
});


Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login']);
    Route::get('register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::middleware('auth:customer')->group(function () {
        Route::get('home', [CustomerController::class, 'index'])->name('home');
        Route::get('logout', [CustomerAuthController::class, 'logout'])->name('logout');

        //posts
        Route::get('post', [CustomerPostController::class, 'index'])->name('post.index');
        Route::get('post/create', [CustomerPostController::class, 'create'])->name('post.create');
        Route::post('post', [CustomerPostController::class, 'store'])->name('post.store');
        Route::get('post/{id}', [CustomerPostController::class, 'show'])->name('post.show');
        Route::get('post/{id}/edit', [CustomerPostController::class, 'edit'])->name('post.edit');
        Route::put('post/{id}', [CustomerPostController::class, 'update'])->name('post.update');
        Route::delete('post/{id}', [CustomerPostController::class, 'destroy'])->name('post.destroy');

        Route::get('pharmacy', [CustomerController::class, 'pharmacy'])->name('pharmacy');

        // Chat routes
        Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
        Route::get('chats/{id}', [ChatController::class, 'show'])->name('chats.show');
        Route::post('chats/{id}/message', [ChatController::class, 'storeMessage'])->name('chats.storeMessage');
        Route::get('chats/start/{pharmacy}', [PharmacyController::class, 'startChat'])->name('startChat');
        Route::post('chats/start/{pharmacy}', [PharmacyController::class, 'startChat'])->name('startChat.store');

    });
});
