<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\AddressController as ClientAddressController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\ShopController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SubcategoryController;
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
    Route::post('/client/orders/{order}/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::get('/payment/success', [OrderController::class, 'success'])->name('payment.success');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/home', function () {
            return redirect()->route('admin.dashboard');
        })->name('home');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::view('/orders', 'admin.orders')->name('orders');
        Route::view('/promotions', 'admin.promotions')->name('promotions');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('subcategories', SubcategoryController::class);
        Route::resource('shippings', ShippingController::class);
    });

    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/home', [ShopController::class, 'home'])->name('home');
        Route::get('/profile', [ClientAddressController::class, 'index'])->name('profile');
        Route::post('/addresses', [ClientAddressController::class, 'store'])->name('addresses.store');
        Route::patch('/addresses/{address}', [ClientAddressController::class, 'update'])->name('addresses.update');
        Route::get('/categories', [ShopController::class, 'categories'])->name('categories.index');
        Route::get('/categories/{category}', [ShopController::class, 'showCategory'])->name('categories.show');
        Route::get('/subcategories/{subcategory}', [ShopController::class, 'showSubcategory'])->name('subcategories.show');
        Route::get('/products/{product}', [ShopController::class, 'showProduct'])->name('products.show');
        Route::post('/products/{product}/add-to-cart', [ShopController::class, 'addToCart'])->name('products.add-to-cart');

        Route::get('/cart', fn () => redirect()->route('client.categories.index'))->name('cart.index');
        Route::post('/cart/items/{cartItem}/increment', [CartController::class, 'increment'])->name('cart.items.increment');
        Route::post('/cart/items/{cartItem}/decrement', [CartController::class, 'decrement'])->name('cart.items.decrement');
        Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.items.destroy');
        Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::post('/checkout/place-order', [CartController::class, 'placeOrder'])->name('checkout.place-order');
        Route::get('/orders', [CartController::class, 'orders'])->name('orders.index');
        Route::get('/orders/{order}', [CartController::class, 'showOrder'])->name('orders.show');
        Route::get('/orders/{order}/pay', [CartController::class, 'pay'])->name('orders.pay');
        Route::post('/orders/{order}/payment/confirm', [CartController::class, 'confirmPayment'])->name('orders.payment.confirm');
        Route::get('/orders/{order}/payment/success', [CartController::class, 'paymentSuccess'])->name('orders.payment.success');
        Route::get('/orders/{order}/payment/cancel', [CartController::class, 'paymentCancel'])->name('orders.payment.cancel');
    });
});
