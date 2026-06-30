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
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

// HALAMAN AWAL
Route::redirect('/', '/customer/login');

// LOGIN ADMIN & KASIR
Route::get('/login', function () {

    return view('welcome');

})->name('login');


/*
|--------------------------------------------------------------------------
| CUSTOMER AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('guest:customer')->group(function () {

    Route::get(
        '/customer/login',
        [CustomerAuthController::class, 'showLogin']
    )->name('customer.showLogin');

    Route::post(
        '/customer/login',
        [CustomerAuthController::class, 'login']
    )->name('customer.login');

});

Route::middleware('customer')->group(function () {

    Route::post(
        '/customer/logout',
        [CustomerAuthController::class, 'logout']
    )->name('customer.logout');

});


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::middleware(['customer'])
    ->prefix('customer')
    ->group(function () {

        // BERANDA
        Route::get(
            '/beranda/{id?}',
            [CustomerController::class, 'beranda']
        )->name('customer.beranda');

        // KERANJANG
        Route::get(
            '/pesanan',
            [CartController::class, 'index']
        )->name('customer.pesanan');

        // PEMBAYARAN
        Route::get(
            '/pembayaran',
            [CustomerController::class, 'pembayaran']
        )->name('customer.pembayaran');

        // RIWAYAT
        Route::get(
            '/pesanan-saya',
            [CustomerController::class, 'pesananSaya']
        )->name('customer.pesananSaya');

        // DETAIL PESANAN
        Route::get(
            '/pesanan/{id}',
            [CustomerController::class, 'detailPesanan']
        )->name('customer.detailPesanan');

        // ADD TO CART
        Route::post(
            '/cart/add/{id}',
            [CustomerController::class, 'addToCart']
        )->name('customer.cart.add');

        // RATING
        Route::post(
            '/rating',
            [ProductRatingController::class, 'store']
        )->name('rating.store');

        // CHAT
        Route::get(
            '/chat',
            [ChatController::class, 'index']
        )->name('customer.chat');

        Route::post(
            '/chat/send',
            [ChatController::class, 'send']
        )->name('customer.chat.send');

        Route::get(
            '/chat/fetch',
            [ChatController::class, 'fetch']
        )->name('customer.chat.fetch');

});


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::middleware('customer')->group(function () {

    Route::post(
        '/cart/add',
        [CartController::class, 'add']
    )->name('cart.add');

    Route::post(
        '/cart/update',
        [CartController::class, 'update']
    )->name('cart.update');

    Route::post(
        '/cart/remove',
        [CartController::class, 'remove']
    )->name('cart.remove');

    Route::post('/clear-cart', function () {

        session()->forget('cart');

        return response()->json([
            'success' => true
        ]);

    })->name('cart.clear');

});

/*
|--------------------------------------------------------------------------
| ORDER
|--------------------------------------------------------------------------
*/

Route::middleware('customer')->group(function () {

    Route::post(
        '/checkout',
        [PaymentController::class, 'checkout']
    )->name('checkout.store');

    Route::post(
        '/bayar/{id}',
        [OrderController::class, 'bayar']
    )->name('order.bayar');

    Route::post(
        '/retry-payment/{id}',
        [OrderController::class, 'retryPayment']
    )->name('retry.payment');

});

/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

Route::post(
    '/payment-success',
    [PaymentController::class, 'paymentSuccess']
)->name('payment.success');

Route::post(
    '/payment/failed',
    [PaymentController::class, 'failed']
)->name('payment.failed');

Route::post(
    '/midtrans/callback',
    [PaymentController::class, 'callback']
)->name('midtrans.callback');


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
| PROFILE (ADMIN & KASIR)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,kasir'])->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| CHAT ADMIN & KASIR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,kasir'])->group(function () {

    // LIST CUSTOMER CHAT
    Route::get(
        '/chat',
        [ChatController::class, 'adminIndex']
    )->name('admin.chat');

    // DETAIL CHAT CUSTOMER
    Route::get(
        '/chat/{customerId}',
        [ChatController::class, 'adminShow']
    )->name('admin.chat.show');

    // KIRIM PESAN ADMIN/KASIR
    Route::post(
        '/chat/{customerId}',
        [ChatController::class, 'adminSend']
    )->name('admin.chat.send');

    // AUTO REFRESH CHAT
    Route::get(
        '/chat/{customerId}/fetch',
        [ChatController::class, 'adminFetch']
    )->name('chat.fetch');

});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    Route::resource(
        'products',
        ProductController::class
    );

    Route::resource(
        'categories',
        CategoryController::class
    );

    Route::resource(
        'ongkir',
        OngkirController::class
    );

});


/*
|--------------------------------------------------------------------------
| ADMIN & KASIR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,kasir'])->group(function () {

    Route::resource(
        'orders',
        OrderController::class
    );

    Route::resource(
        'payments',
        PaymentController::class
    );

});


require __DIR__.'/auth.php';