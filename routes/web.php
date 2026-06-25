<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductRatingController;
use App\Http\Controllers\OngkirController;

/*
|--------------------------------------------------------------------------
| PUBLIC (CUSTOMER - TANPA LOGIN)
|--------------------------------------------------------------------------
*/

// HALAMAN AWAL
Route::get('/', [CustomerController::class, 'beranda'])
    ->name('home');

// LOGIN ADMIN CUSTOM
Route::get('/login', function () {

    return view('welcome');

})->name('login');


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->group(function () {

    // BERANDA
    Route::get('/beranda/{id?}',
        [CustomerController::class, 'beranda'])
        ->name('customer.beranda');

    // KERANJANG
    Route::get('/pesanan',
        [CartController::class, 'index'])
        ->name('customer.pesanan');

    // PEMBAYARAN
    Route::get('/pembayaran',
        [CustomerController::class, 'pembayaran'])
        ->name('customer.pembayaran');

    // RIWAYAT
    Route::get('/pesanan-saya',
        [CustomerController::class, 'pesananSaya'])
        ->name('customer.pesananSaya');

    // DETAIL
    Route::get('/pesanan/{id}',
        [CustomerController::class, 'detailPesanan'])
        ->name('customer.detailPesanan');

    // TENTANG
    Route::get('/tentang-kami',
        [CustomerController::class, 'tentang'])
        ->name('customer.tentang');

    // ADD TO CART
    Route::post('/cart/add/{id}',
        [CustomerController::class, 'addToCart'])
        ->name('customer.cart.add');

    /*
    |--------------------------------------------------------------------------
    | PRODUCT RATING
    |--------------------------------------------------------------------------
    */

    Route::post('/rating',
        [ProductRatingController::class, 'store'])
        ->name('rating.store');
});


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

// ADD CART
Route::post('/cart/add',
    [CartController::class, 'add'])
    ->name('cart.add');

// UPDATE CART
Route::post('/cart/update',
    [CartController::class, 'update'])
    ->name('cart.update');

// REMOVE CART
Route::post('/cart/remove',
    [CartController::class, 'remove'])
    ->name('cart.remove');

// CLEAR CART
Route::post('/clear-cart', function () {

    session()->forget('cart');

    return response()->json([
        'success' => true
    ]);

})->name('cart.clear');


/*
|--------------------------------------------------------------------------
| ORDER
|--------------------------------------------------------------------------
*/

// CHECKOUT
Route::post('/checkout',
    [PaymentController::class, 'checkout'])
    ->name('checkout.store');

// BAYAR MANUAL
Route::post('/bayar/{id}',
    [OrderController::class, 'bayar'])
    ->name('order.bayar');

// RETRY PAYMENT
Route::post('/retry-payment/{id}',
    [OrderController::class, 'retryPayment'])
    ->name('retry.payment');


/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

// SUCCESS PAYMENT
Route::post('/payment-success',
    [PaymentController::class, 'paymentSuccess'])
    ->name('payment.success');

Route::post(
    '/payment/failed',
    [App\Http\Controllers\PaymentController::class, 'failed']
)->name('payment.failed');

// MIDTRANS CALLBACK
Route::post('/midtrans/callback',
    [PaymentController::class, 'callback'])
    ->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| ONGKIR
|--------------------------------------------------------------------------
*/

Route::get(
    '/get-ongkir/{jarak}',
    [OngkirController::class, 'getOngkir']
)->name('get.ongkir');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard',
        [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile',
        [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile',
        [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile',
        [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | CRUD ADMIN
    |--------------------------------------------------------------------------
    */

    // PRODUCT
    Route::resource('products',
        ProductController::class);

    // CATEGORY
    Route::resource('categories',
        CategoryController::class);

    // ORDER
    Route::resource('orders',
        OrderController::class);

    // PAYMENT
    Route::resource('payments',
        PaymentController::class);
    
    // ONGKIR
    Route::resource('ongkir', OngkirController::class);
});


require __DIR__.'/auth.php';