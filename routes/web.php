<?php

use Illuminate\Support\Facades\Route;
use App\Presentation\Controllers\ProductController;
use App\Presentation\Controllers\CartController;
use App\Presentation\Controllers\OrderController;
use App\Presentation\Controllers\WebhookController;
use App\Presentation\Controllers\AdminController;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::post('/{product}/add-to-cart', [ProductController::class, 'addToCart'])->name('products.add-to-cart');
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::delete('/remove-item', [CartController::class, 'removeItem'])->name('cart.remove-item');
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::delete('/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

Route::prefix('orders')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/get-address-by-cep', [OrderController::class, 'getAddressByCep'])->name('orders.get-address-by-cep');
});

Route::prefix('webhook')->group(function () {
    Route::post('/update-order-status', [WebhookController::class, 'updateOrderStatus'])->name('webhook.update-order-status')->withoutMiddleware(['web']);
});

Route::prefix('admin')->group(function () {
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
});
