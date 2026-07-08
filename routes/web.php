<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use Illuminate\Support\Facades\Auth;

// Root Route: Check auth and redirect appropriately
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard-analytics');
    }
    return redirect()->route('login');
});

// Authentication Routes (Accessible without login)
Route::middleware('guest')->group(function () {
    Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
    Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('login');
    Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
    Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
    Route::post('/auth/register', [RegisterBasic::class, 'store'])->name('auth-register-basic-store');
    Route::post('/auth/login', [LoginBasic::class, 'store'])->name('auth-login-basic-store');
});

// Protected Routes (Requires login)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/form/layouts-vertical', [ProductController::class, 'create'])->name('form-layouts-vertical');
    Route::resource('shops', ShopController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::middleware('admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('subcategories', SubCategoryController::class)
            ->parameters(['subcategories' => 'subCategory']);
        Route::resource('payment-methods', PaymentMethodController::class);
    });

    // Logout
    Route::post('/auth/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('auth-logout');
});
