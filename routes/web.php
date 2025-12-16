<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// AUTH
Route::get('/register', [AuthController::class, 'showRegister'])->name('showRegister');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =====================================
// ADMIN AREA
// =====================================
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    Route::get('/dashboard', fn() => view('admin.dashboard'))
        ->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [UserController::class, 'destroyrole'])->name('users.destroyrole');

    // ✅ ADMIN ORDER LIST
    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])
        ->name('admin.orders.index');

});

// =====================================
// USER STORE
// =====================================

Route::get('/search-products', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// CART
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])
    ->name('cart.update');


// QTY + -
Route::post('/cart/update', [CartController::class, 'updateQuantity'])
    ->name('cart.update');

// =====================================
// CHECKOUT FLOW
// =====================================
Route::middleware('auth')->group(function () {

    Route::get('/checkout', [CartController::class, 'showCheckout'])
        ->name('checkout.form');

    Route::post('/checkout/payment', [CartController::class, 'processPayment'])
        ->name('checkout.payment');

    // ✅ USER ORDER HISTORY
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    // =====================
// ADMIN AREA
// =====================
    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::get('/admin/orders', [OrderController::class, 'adminIndex'])
            ->name('admin.orders.index');

        Route::patch(
            '/admin/orders/{order}/status',
            [OrderController::class, 'updateStatus']
        )
            ->name('admin.orders.updateStatus');
    });


    // =====================
// USER AREA
// =====================
    Route::middleware('auth')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');
        Route::patch(
            '/admin/orders/{order}/status',
            [OrderController::class, 'updateStatus']
        )->name('admin.orders.updateStatus');

    });

    // USER REVIEW
    Route::middleware('auth')->group(function () {
        Route::get('/reviews/{orderItem}/create', [ReviewController::class, 'create'])
            ->name('reviews.create');

        Route::post('/reviews', [ReviewController::class, 'store'])
            ->name('reviews.store');

        Route::get(
            '/reviews/{order}/{product}',
            [ReviewController::class, 'show']
        )->name('reviews.show');
    });

    // ADMIN REVIEW
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin/reviews', [ReviewController::class, 'index'])
            ->name('admin.reviews.index');

        Route::patch('/admin/reviews/{review}/reply', [ReviewController::class, 'reply'])
            ->name('admin.reviews.reply');
    });
});

// order item test / dev
Route::get('/orderitems', [OrderItemsController::class, 'index'])
    ->name('orderitems.index');
