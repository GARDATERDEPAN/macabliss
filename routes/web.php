<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/test', function () {
    return "OK HIDUP";
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/products', [ProductController::class, 'index']);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('payments', PaymentController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/customer/beranda', [CustomerController::class, 'beranda'])->name('customer.beranda');
    Route::get('/customer/pesanan', function () {
    return 'pesanan';})->name('customer.pesanan');
    Route::get('/customer/pembayaran', function () {
    return 'pembayaran';})->name('customer.pembayaran');
    Route::post('/customer/cart/add/{id}', [CustomerController::class, 'addToCart'])->name('customer.cart.add');
    Route::get('/customer/pesanan', [OrderController::class, 'index'])->name('customer.pesanan');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/customer/pesanan', [CartController::class, 'index'])->name('customer.pesanan');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/customer/pembayaran', function () {
    return view('customer.pembayaran');})->name('customer.pembayaran');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/customer/pesanan-saya', [CustomerController::class, 'pesananSaya'])->name('customer.pesananSaya');
    Route::get('/customer/pesanan/{id}', [App\Http\Controllers\CustomerController::class, 'detailPesanan'])->name('customer.detailPesanan');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

require __DIR__.'/auth.php';
