<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('payment');

        // SEARCH
        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('kode', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_customer', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER STATUS ORDER
        if ($request->status) {

            $query->where('status', $request->status);
        }

        // FILTER TANGGAL
        if ($request->from) {

            $query->whereDate('tanggal_pesan', '>=', $request->from);
        }

        if ($request->to) {

            $query->whereDate('tanggal_pesan', '<=', $request->to);
        }

        $orders = $query->orderBy('tanggal_pesan', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(8)
            ->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'details.product',
            'payment'
        ])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // JIKA SUDAH SELESAI ATAU DIBATALKAN
        if (
            $order->status == 'selesai' ||
            $order->status == 'dibatalkan'
        ) {

            return redirect()
                ->route('orders.index')
                ->with('error', 'Status pesanan sudah final');
        }

        $request->validate([
            'status' => 'required|in:diproses,dikemas,dikirim,selesai,dibatalkan'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        // JIKA SELESAI
        if ($request->status == 'selesai') {

            $order->update([
                'tanggal_kirim' => now()
            ]);
        }

        return redirect()
            ->route('orders.index')
            ->with('success', 'Status pesanan berhasil diupdate');
    }

    public function store(Request $request)
    {
        try {

            // VALIDASI
            $request->validate([

                'nama' => 'required',
                'no_hp' => 'required',
                'alamat' => 'required',

                'tanggal_kirim' =>
                    'required|date|after_or_equal:' .
                    now()->addDays(2)->toDateString(),

                'payment' => 'required'

            ]);

            // AMBIL CART
            $cart = session('cart', []);

            if (empty($cart)) {

                return response()->json([
                    'error' => 'Keranjang kosong!'
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN CHECKOUT SEMENTARA
            |--------------------------------------------------------------------------
            */

            session([

                'checkout_data' => [

                    'nama' =>
                        $request->nama,

                    'no_hp' =>
                        $request->no_hp,

                    'alamat' =>
                        $request->alamat,

                    'tanggal_kirim' =>
                        $request->tanggal_kirim,

                    'payment' =>
                        $request->payment,

                    'ongkir' =>
                        $request->ongkir ?? 0

                ]

            ]);

            /*
            |--------------------------------------------------------------------------
            | HITUNG TOTAL
            |--------------------------------------------------------------------------
            */

            $totalProduk = 0;

            foreach ($cart as $item) {

                $totalProduk +=
                    $item['harga']
                    * $item['qty'];
            }

            $ongkir =
                $request->ongkir ?? 0;

            $admin = 1000;

            $total =
                $totalProduk +
                $ongkir +
                $admin;

            /*
            |--------------------------------------------------------------------------
            | COD
            |--------------------------------------------------------------------------
            */

            if ($request->payment == 'COD') {

                // SIMPAN ORDER
                $order = Order::create([

                    'session_id' => session()->getId(),

                    'kode' => null,

                    'nama_customer' =>
                        $request->nama,

                    'no_hp' =>
                        $request->no_hp,

                    'alamat' =>
                        $request->alamat,

                    'tanggal_pesan' =>
                        now(),

                    'tanggal_kirim' =>
                        $request->tanggal_kirim,

                    'metode_pembayaran' =>
                        'COD',

                    'ongkir' =>
                        $ongkir,

                    'total_harga' =>
                        $total,

                    'status' =>
                        'diproses'

                ]);

                // GENERATE KODE
                $orderCode =
                    'M-' .
                    str_pad(
                        $order->id,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

                $order->update([

                    'kode' => $orderCode

                ]);

                // DETAIL ORDER
                foreach ($cart as $id => $item) {

                    OrderDetail::create([

                        'order_id' =>
                            $order->id,

                        'product_id' =>
                            $id,

                        'qty' =>
                            $item['qty'],

                        'harga' =>
                            $item['harga'],

                    ]);
                }

                // PAYMENT
                Payment::create([

                    'order_id' =>
                        $order->id,

                    'kode_pembayaran' =>
                        'PAY-' .
                        str_pad(
                            $order->id,
                            4,
                            '0',
                            STR_PAD_LEFT
                        ),

                    'metode' =>
                        'COD',

                    'payment_ref' =>
                        null,

                    'tanggal_bayar' =>
                        now(),

                    'jumlah' =>
                        $total,

                    'status' =>
                        'pending',

                ]);

                // CLEAR CART
                session()->forget('cart');

                return response()->json([

                    'cod' => true,

                    'redirect' =>
                        route('customer.pesananSaya')

                ]);
            }

            /*
|--------------------------------------------------------------------------
| MIDTRANS
|--------------------------------------------------------------------------
*/

Config::$serverKey =
    config('midtrans.server_key');

Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;

// ORDER ID UNIK
$uniqueOrderId =
    'ORDER-' .
    now()->timestamp .
    '-' .
    rand(1000, 9999);

$params = [

    'transaction_details' => [

        'order_id' =>
            $uniqueOrderId,

        'gross_amount' =>
            (int) $total,
    ],

    'customer_details' => [

        'first_name' =>
            $request->nama,

        'phone' =>
            $request->no_hp,
    ],
];

// SNAP TOKEN
$snapToken =
    Snap::getSnapToken($params);

return response()->json([

    'snap_token' =>
        $snapToken

]);

        } catch (\Throwable $e) {

            return response()->json([

                'message' =>
                    $e->getMessage(),

                'file' =>
                    $e->getFile(),

                'line' =>
                    $e->getLine(),

            ], 500);
        }
    }

    public function storeAfterPayment(Request $request)
    {
        try {

            // AMBIL CART
            $cart = session('cart', []);

            // AMBIL DATA CHECKOUT
            $checkout =
                session('checkout_data');

            // VALIDASI
            if (
                empty($cart) ||
                !$checkout
            ) {

                return response()->json([

                    'success' => false,
                    'message' => 'Checkout gagal'

                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | HITUNG TOTAL
            |--------------------------------------------------------------------------
            */

            $totalProduk = 0;

            foreach ($cart as $item) {

                $totalProduk +=
                    $item['harga']
                    * $item['qty'];
            }

            $ongkir =
                $checkout['ongkir'] ?? 0;

            $admin = 1000;

            $total =
                $totalProduk +
                $ongkir +
                $admin;

            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER
            |--------------------------------------------------------------------------
            */

            $order = Order::create([

                'kode' => null,

                'nama_customer' =>
                    $checkout['nama'],

                'no_hp' =>
                    $checkout['no_hp'],

                'alamat' =>
                    $checkout['alamat'],

                'tanggal_pesan' =>
                    now(),

                'tanggal_kirim' =>
                    $checkout['tanggal_kirim'],

                'metode_pembayaran' =>
                    'QRIS',

                'ongkir' =>
                    $ongkir,

                'total_harga' =>
                    $total,

                'status' =>
                    'diproses'

            ]);

            /*
            |--------------------------------------------------------------------------
            | GENERATE KODE
            |--------------------------------------------------------------------------
            */

            $orderCode =
                'M-' .
                str_pad(
                    $order->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            $order->update([

                'kode' => $orderCode

            ]);

            /*
            |--------------------------------------------------------------------------
            | DETAIL ORDER
            |--------------------------------------------------------------------------
            */

            foreach ($cart as $id => $item) {

                OrderDetail::create([

                    'order_id' =>
                        $order->id,

                    'product_id' =>
                        $id,

                    'qty' =>
                        $item['qty'],

                    'harga' =>
                        $item['harga']

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            Payment::create([

                'order_id' =>
                    $order->id,

                'kode_pembayaran' =>
                    'PAY-' .
                    str_pad(
                        $order->id,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ),

                'metode' =>
                    'QRIS',

                'payment_ref' =>
                    $request->transaction_id,

                'tanggal_bayar' =>
                    now(),

                'jumlah' =>
                    $total,

                'status' =>
                    'paid',

            ]);

            /*
            |--------------------------------------------------------------------------
            | CLEAR SESSION
            |--------------------------------------------------------------------------
            */

            session()->forget('cart');

            session()->forget('checkout_data');

            return response()->json([

                'success' => true

            ]);

        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    $e->getMessage(),

                'file' =>
                    $e->getFile(),

                'line' =>
                    $e->getLine()

            ], 500);
        }
    }

    // OPTIONAL MANUAL BAYAR
    public function bayar($id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment) {

            return back()->with('error', 'Sudah ada pembayaran!');
        }

        Payment::create([
            'order_id' => $order->id,
            'kode_pembayaran' => 'PAY-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
            'metode' => $order->metode_pembayaran,
            'payment_ref' => null,
            'tanggal_bayar' => now(),
            'jumlah' => $order->total_harga,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('customer.pesananSaya')
            ->with('success', 'Pembayaran berhasil dibuat!');
    }

    public function retryPayment($id)
{
    $order = Order::with('payment')->findOrFail($id);

    // MIDTRANS
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // ORDER ID BARU
    $uniqueOrderId =
        'RETRY-' .
        now()->timestamp .
        '-' .
        rand(1000, 9999);

    // GENERATE TOKEN BARU
    $params = [

        'transaction_details' => [

            'order_id' =>
                $uniqueOrderId,

            'gross_amount' =>
                (int) $order->total_harga,
        ],

        'customer_details' => [

            'first_name' =>
                $order->nama_customer,

            'phone' =>
                $order->no_hp,
        ],
    ];

    $snapToken =
        \Midtrans\Snap::getSnapToken($params);

    // UPDATE ORDER
    $order->update([

        'snap_token' =>
            $snapToken,

        'expired_at' =>
            now()->addMinutes(15),

        'status' =>
            'diproses'
    ]);

    // UPDATE PAYMENT
    if ($order->payment) {

        $order->payment->update([

            'status' =>
                'pending',

            'payment_ref' =>
                $snapToken
        ]);
    }

    return response()->json([

        'snap_token' =>
            $snapToken
    ]);
}
}