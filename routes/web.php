<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSupportController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AdminForgotPasswordController;
use App\Http\Controllers\Auth\AdminResetPasswordController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerForgotPasswordController;
use App\Http\Controllers\Auth\CustomerResetPasswordController;
use App\Http\Controllers\Auth\PharmacyAuthController;
use App\Http\Controllers\Auth\PharmacyForgotPasswordController;
use App\Http\Controllers\Auth\PharmacyResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
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

    Route::get('/password/reset', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AdminResetPasswordController::class, 'reset'])->name('password.update');

    Route::middleware('auth:admin')->group(function () {
        Route::get('home', [AdminController::class, 'index'])->name('home');
        Route::get('/statistics/details/{month}', [AdminController::class, 'statisticsDetails'])->name('statistics.details');
        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');

        //posts
        Route::get('/posts', [AdminController::class, 'postsindex'])->name('posts.index');
        Route::delete('/posts/{post}/{type}', [AdminController::class, 'destroy'])->name('posts.destroy');

        // pharmacy status
        Route::get('/pharmacies', [AdminController::class, 'pharmacystatus'])->name('pharmacies.index');
        Route::post('/pharmacies/{pharmacy}/toggle-status', [AdminController::class, 'toggleStatus'])->name('pharmacies.toggle-status');

        Route::get('/customers', [AdminController::class, 'customerstatus'])->name('customers.show');

        // User Management (Customers & Pharmacies)
        Route::get('/users', [AdminController::class, 'customerindex'])->name('users.index');
        Route::post('/users/{user}/toggle-block', [AdminController::class, 'toggleBlock'])->name('users.toggle-block');

        //chats
        Route::get('/chats', [AdminController::class, 'chatindex'])->name('chats.index');
        Route::get('/chats/{chat}', [AdminController::class, 'chatshow'])->name('chats.show');
        // Route::delete('/chats/{chat}', [AdminController::class, 'chatdestroy'])->name('chats.destroy');

        //suport
        Route::get('/admin/support-tickets', [AdminSupportController::class, 'index'])->name('support.index');
        Route::get('/admin/support-tickets/{ticket}', [AdminSupportController::class, 'show'])->name('support.show');
        Route::put('/admin/support-tickets/{ticket}', [AdminSupportController::class, 'update'])->name('support.update');

        //payments
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
        Route::get('/payments/{payment}', [AdminController::class, 'show'])->name('payments.show');

        //profile
        Route::get('/profile', [AdminAuthController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminAuthController::class, 'update'])->name('profile.update');
    });
});


Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('login', [PharmacyAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [PharmacyAuthController::class, 'login']);
    Route::get('register', [PharmacyAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [PharmacyAuthController::class, 'register']);

    Route::get('/password/reset', [PharmacyForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [PharmacyForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [PharmacyResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PharmacyResetPasswordController::class, 'reset'])->name('password.update');

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

        Route::get('/posts/{id}', [PharmacyController::class, 'showModal'])->name('posts.show.modal');
        Route::put('/notifications/{notificationId}/read', [PharmacyController::class, 'markAsRead']);
        Route::post('/chats/{chat}/send-offer', [ChatController::class, 'sendOffer'])->name('chats.send-offer');

        //orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

        //support
        Route::get('/contact-admin', [ContactController::class, 'create'])->name('contact.create');
        Route::post('/contact-admin', [ContactController::class, 'store'])->name('contact.store');

        //profile
        Route::get('/profile', [PharmacyAuthController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [PharmacyAuthController::class, 'update'])->name('profile.update');
    });
});


Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login']);
    Route::get('register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register']);

    Route::get('/password/reset', [CustomerForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [CustomerForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [CustomerResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [CustomerResetPasswordController::class, 'reset'])->name('password.update');

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
        // Route::post('order', [OrderController::class, 'store'])->name('customer.order.store');
        Route::post('send-medicine-details', [PharmacyController::class, 'sendMedicineDetails'])->name('sendMedicineDetails');

        Route::get('/chats/{chatId}/message/{messageId}/pay', [PaymentController::class, 'showPaymentPage'])->name('pay');
        Route::post('/chats/{chatId}/message/{messageId}/pay', [PaymentController::class, 'processPayment'])->name('processPayment');

        //orders
            Route::get('/orders', [OrderController::class, 'customerindex'])->name('orders.index');
            Route::get('/orders/{order}', [OrderController::class, 'customershow'])->name('orders.show');

        //rating
        Route::post('/orders/{order}/ratings', [RatingController::class, 'store'])->name('ratings.store');

        //support
        Route::get('/contact-admin', [ContactController::class, 'create'])->name('contact.create');
        Route::post('/contact-admin', [ContactController::class, 'store'])->name('contact.store');

        //profile
        Route::get('/profile', [CustomerAuthController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [CustomerAuthController::class, 'update'])->name('profile.update');
    });
});
