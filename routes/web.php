<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/home', function () {
            return redirect()->route('admin.dashboard');
        })->name('home');

        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/analytics', 'admin.analytics')->name('analytics');
        Route::view('/orders', 'admin.orders')->name('orders');
        Route::view('/products', 'admin.products')->name('products');
        Route::view('/promotions', 'admin.promotions')->name('promotions');
        Route::resource('categories', CategoryController::class);
    });

    Route::get('/client/home', function () {
        return 'Client home';
    })->name('client.home');
});
