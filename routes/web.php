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

/*
|--------------------------------------------------------------------------
| PUBLIC (CUSTOMER - TANPA LOGIN)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return "OK HIDUP";
});

// CUSTOMER
Route::prefix('customer')->group(function () {
    Route::get('/beranda', [CustomerController::class, 'beranda'])->name('customer.beranda');
    Route::get('/pesanan', [CartController::class, 'index'])->name('customer.pesanan');
    Route::get('/pembayaran', function () {
        return view('customer.pembayaran');
    })->name('customer.pembayaran');

    Route::get('/pesanan-saya', [CustomerController::class, 'pesananSaya'])->name('customer.pesananSaya');
    Route::get('/pesanan/{id}', [CustomerController::class, 'detailPesanan'])->name('customer.detailPesanan');
});

// CART & CHECKOUT (PUBLIC)
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/customer/cart/add/{id}', [CustomerController::class, 'addToCart'])->name('customer.cart.add');

Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');


/*
|--------------------------------------------------------------------------
| ADMIN (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD ADMIN
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('payments', PaymentController::class);
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');


require __DIR__.'/auth.php';