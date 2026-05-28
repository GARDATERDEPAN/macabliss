<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Notification;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\OrderDetail;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('order');

        // SEARCH
        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('kode_pembayaran', 'like', '%' . $request->search . '%')

                ->orWhereHas('order', function ($q2) use ($request) {

                    $q2->where('kode', 'like', '%' . $request->search . '%');
                });
            });
        }

        // FILTER STATUS
        if ($request->status) {

            $query->where('status', $request->status);
        }

        $payments = $query->latest()
            ->paginate(8)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        return view('payments.show', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // STATUS FINAL
        if (
            $payment->status == 'paid' ||
            $payment->status == 'expired' ||
            $payment->status == 'failed' ||
            $payment->status == 'cancelled'
        ) {

            return redirect()
                ->route('payments.index')
                ->with('error', 'Status pembayaran sudah final');
        }

        $request->validate([
            'status' => 'required|in:pending,paid,expired,failed,cancelled'
        ]);

        // UPDATE PAYMENT
        $payment->update([
            'status' => $request->status
        ]);

        /*
        |--------------------------------------------------------------------------
        | SINKRON STATUS ORDER
        |--------------------------------------------------------------------------
        */

        if ($payment->order) {

            /*
            |--------------------------------------------------------------------------
            | PAYMENT BERHASIL
            |--------------------------------------------------------------------------
            */

            if ($request->status == 'paid') {

                if (
                    $payment->order->status != 'selesai'
                ) {

                    $payment->order->update([
                        'status' => 'diproses'
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | PAYMENT GAGAL
            |--------------------------------------------------------------------------
            */

            if (
                $request->status == 'expired' ||
                $request->status == 'failed' ||
                $request->status == 'cancelled'
            ) {

                if (
                    $payment->order->status != 'selesai'
                ) {

                    $payment->order->update([
                        'status' => 'diproses'
                    ]);
                }
            }
        }

        return redirect()
            ->route('payments.index')
            ->with('success', 'Status pembayaran berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT MIDTRANS
    |--------------------------------------------------------------------------
    */

    public function checkout(Request $request)
    {
        try {

            Config::$serverKey =
                config('midtrans.server_key');

            Config::$isProduction = false;

            Config::$isSanitized = true;

            Config::$is3ds = true;

            $cart = session('cart', []);

            if (count($cart) <= 0) {

                return response()->json([
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | GENERATE KODE ORDER
            |--------------------------------------------------------------------------
            */

            $lastOrder = Order::where('kode', 'like', 'M-%')
                ->orderBy('id', 'desc')
                ->first();

            $number = 1;

            if ($lastOrder) {

                $lastNumber =
                    (int) str_replace('M-', '', $lastOrder->kode);

                $number = $lastNumber + 1;
            }

            $kodeOrder =
                'M-' .
                str_pad(
                    $number,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            /*
            |--------------------------------------------------------------------------
            | HITUNG TOTAL
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            foreach ($cart as $item) {

                $subtotal +=
                    $item['harga'] *
                    $item['qty'];
            }

            $total =
                $subtotal +
                $request->ongkir +
                1000;

            /*
            |--------------------------------------------------------------------------
            | SIMPAN ORDER
            |--------------------------------------------------------------------------
            */

            $order = Order::create([

                'session_id' => session()->getId(),

                'kode' => $kodeOrder,

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
                    $request->payment,

                'total_harga' =>
                    $total,

                'ongkir' =>
                    $request->ongkir,

                'status' =>
                    'diproses',

                'metode_pengambilan' =>
                    $request->delivery_type

            ]);

            /*
            |--------------------------------------------------------------------------
            | DETAIL ORDER
            |--------------------------------------------------------------------------
            */

            foreach (session('cart', []) as $item) {

                $product = \App\Models\Product::where(
                    'nama_produk',
                    $item['nama_produk']
                )->first();

                if (!$product) {
                    continue;
                }

                OrderDetail::create([

                    'order_id' => $order->id,

                    'product_id' => $product->id,

                    'qty' => $item['qty'],

                    'harga' => $item['harga']

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | KODE PAYMENT
            |--------------------------------------------------------------------------
            */

            $lastPayment = Payment::where(
                    'kode_pembayaran',
                    'like',
                    'PAY-%'
                )
                ->orderBy('id', 'desc')
                ->first();

            $paymentNumber = 1;

            if ($lastPayment) {

                $lastNumber =
                    (int) str_replace(
                        'PAY-',
                        '',
                        $lastPayment->kode_pembayaran
                    );

                $paymentNumber = $lastNumber + 1;
            }

            $kodePayment =
                'PAY-' .
                str_pad(
                    $paymentNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            $statusPembayaran =
                $request->payment == 'COD'
                    ? 'pending'
                    : 'paid';

            $payment = Payment::create([

                'order_id'          => $order->id,

                'kode_pembayaran'   => $kodePayment,

                'metode'            => $request->payment,

                'tanggal_bayar'     => now(),

                'jumlah'            => $total,

                'status'            => $statusPembayaran,

                'payment_ref'       => $kodeOrder

            ]);

            /*
            |--------------------------------------------------------------------------
            | COD
            |--------------------------------------------------------------------------
            */

            if ($request->payment == 'COD') {

                session()->forget('cart');

                return response()->json([

                    'cod' => true,

                    'redirect' =>
                        '/customer/pesanan-saya'

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | MIDTRANS PARAM
            |--------------------------------------------------------------------------
            */

            $params = [

                'transaction_details' => [

                    'order_id' => 'MCB-' . time() . rand(100,999),

                    'gross_amount' => (int)$total

                ],

                'customer_details' => [

                    'first_name' => $request->nama,

                    'phone' => $request->no_hp

                ]

            ];

            $snapToken = Snap::getSnapToken($params);

            session()->forget('cart');

            return response()->json([

                'snap_token' =>
                    $snapToken

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'message' =>
                    $e->getMessage()

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO SUCCESS MIDTRANS
    |--------------------------------------------------------------------------
    */

    public function paymentSuccess(Request $request)
    {
        try {

            $midtransOrderId = $request->order_id;

            $order = Order::where('kode', $midtransOrderId)->first();

            if ($order) {

                $order->update([
                    'status' => 'diproses'
                ]);

                if ($order->payment) {

                    $order->payment->update([
                        'status' => 'paid',
                        'payment_ref' => $midtransOrderId,
                        'jumlah' => $order->total_harga
                    ]);
                }

                return response()->json([
                    'success' => true
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MIDTRANS CALLBACK
    |--------------------------------------------------------------------------
    */

    public function callback(Request $request)
    {
        try {

            Config::$serverKey =
                config('midtrans.server_key');

            Config::$isProduction = false;

            $notif = new Notification();

            $transaction =
                $notif->transaction_status;

            $orderId =
                $notif->order_id;

            /*
            |--------------------------------------------------------------------------
            | CARI ORDER
            |--------------------------------------------------------------------------
            */

            $order = Order::where('kode', $orderId)
                ->orWhereHas('payment', function ($q) use ($orderId) {

                    $q->where('payment_ref', $orderId);

                })
                ->first();

            if (!$order) {

                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan'
                ]);
            }

            $payment = Payment::where(
                'order_id',
                $order->id
            )->first();

            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            if (
                $transaction == 'settlement'
                ||
                $transaction == 'capture'
            ) {

                if ($order->status != 'selesai') {

                    $order->status =
                        'diproses';
                }

                if ($payment) {

                    $payment->status =
                        'paid';

                    $payment->jumlah =
                        $order->total_harga;

                    $payment->payment_ref =
                        $orderId;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | PENDING
            |--------------------------------------------------------------------------
            */

            elseif ($transaction == 'pending') {

                if ($order->status != 'selesai') {

                    $order->status =
                        'diproses';
                }

                if ($payment) {

                    $payment->status =
                        'pending';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | EXPIRE
            |--------------------------------------------------------------------------
            */

            elseif ($transaction == 'expire') {

                if ($order->status != 'selesai') {

                    $order->status =
                        'diproses';
                }

                if ($payment) {

                    $payment->status =
                        'expired';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | FAILED
            |--------------------------------------------------------------------------
            */

            elseif (
                $transaction == 'cancel' ||
                $transaction == 'deny'
            ) {

                if ($order->status != 'selesai') {

                    $order->status =
                        'diproses';
                }

                if ($payment) {

                    $payment->status =
                        'failed';
                }
            }

            // SAVE ORDER
            $order->save();

            // SAVE PAYMENT
            if ($payment) {

                $payment->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Callback berhasil diproses'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function failed(Request $request)
    {
        $payment = Payment::latest()->first();

        if ($payment) {

            $payment->update([

                'status' => 'failed'
            ]);

        }

        return response()->json([

            'success' => true
        ]);
    }
}