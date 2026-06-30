<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Notification;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Ongkir;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

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
                        'status' => 'dibatalkan'
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

            $request->validate([

                'payment' => 'required',

                'delivery_type' => 'required|in:pickup,delivery',

                'tanggal_kirim' => 'required|date',

                'alamat' => 'nullable|string|max:255'

            ]);

            Config::$serverKey =
                config('midtrans.server_key');

            Config::$isProduction = false;

            Config::$isSanitized = true;

            Config::$is3ds = true;

            $cart = session('cart', []);

            $existingOrder = Order::with('payment')
                ->where('session_id', session()->getId())
                ->where('status', 'pending')
                ->latest()
                ->first();

            if (
                $existingOrder &&
                $existingOrder->payment &&
                !empty($existingOrder->snap_token)
            ) {

                return response()->json([
                    'snap_token' => $existingOrder->snap_token,
                    'payment_id' => $existingOrder->payment->id,
                    'existing' => true
                ]);
            }

            if (count($cart) <= 0) {

                return response()->json([
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | CEK ORDER PENDING
            |--------------------------------------------------------------------------
            */

            if ($existingOrder && $existingOrder->payment) {

                return response()->json([
                    'snap_token' => $existingOrder->snap_token,
                    'payment_id' => $existingOrder->payment->id,
                    'existing' => true
                ]);
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

            $ongkir = 0;

            if ($request->delivery_type == 'delivery') {

                $jarak = $request->jarak ?? 0;

                $ongkirData = Ongkir::where(
                        'jarak_min',
                        '<=',
                        $jarak
                    )
                    ->where(
                        'jarak_max',
                        '>=',
                        $jarak
                    )
                    ->first();

                $ongkir = $ongkirData
                    ? $ongkirData->tarif
                    : 0;
            }

            $total =
                $subtotal +
                $ongkir +
                1000;
            
            /*
            |--------------------------------------------------------------------------
            | BATAS MAKSIMAL COD
            |--------------------------------------------------------------------------
            */

            $maxCOD = 100000;

            if (
                $request->payment == 'COD'
                &&
                $total > $maxCOD
            ) {

                return response()->json([

                    'message' =>
                        'COD hanya berlaku untuk transaksi maksimal Rp100.000. Silakan gunakan QRIS.'

                ], 400);

            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN ORDER
            |--------------------------------------------------------------------------
            */

            $statusOrder = 'pending';

            if ($request->payment == 'COD') {

                $statusOrder = 'diproses';

            }

            $order = Order::create([

                'user_id' => Auth::guard('customer')->id(),

                'session_id' => session()->getId(),

                'kode' => $kodeOrder,

                'nama_customer' => Auth::guard('customer')->user()->name,

                'no_hp' => Auth::guard('customer')->user()->phone,

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
                    $ongkir,

                'status' => $statusOrder,

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

            $midtransOrderId =
                $kodeOrder . '-' . time();

            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            $statusPembayaran = 'pending';

            $payment = Payment::create([

                'order_id'          => $order->id,

                'kode_pembayaran'   => $kodePayment,

                'metode'            => $request->payment,

                'tanggal_bayar'     => now(),

                'jumlah'            => $total,

                'status'            => $statusPembayaran,

                'payment_ref'       => $midtransOrderId

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

                'order_id' => $midtransOrderId,

                'gross_amount' => (int)$total

            ],

                'customer_details' => [

                    'first_name' => Auth::guard('customer')->user()->name,

                    'phone' => Auth::guard('customer')->user()->phone,

                ]

            ];

            $snapToken = Snap::getSnapToken($params);

            $order->update([
                'snap_token' => $snapToken,
                'expired_at' => now()->addMinutes(15),
                'payment_status' => 'pending'
            ]);

            return response()->json([

                'snap_token' => $snapToken,

                'payment_id' => $payment->id

            ]);

        } catch (\Exception $e) {

            \Log::error($e);

            return response()->json([

                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()

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

            $payment = Payment::where(
                'payment_ref',
                $midtransOrderId
            )->first();

            if (!$payment) {

                return response()->json([
                    'success' => false,
                    'message' => 'Payment tidak ditemukan'
                ], 404);
            }

            $payment->update([
                'status' => 'paid'
            ]);

            if ($payment->order) {

                $payment->order->update([
                    'status' => 'diproses'
                ]);
            }

            session()->forget('cart');

            return response()->json([
                'success' => true
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
        \Log::info('===== MIDTRANS CALLBACK MASUK =====');

        try {

            Config::$serverKey =
                config('midtrans.server_key');

            Config::$isProduction = false;

            $notif = new Notification();

            $transaction =
                $notif->transaction_status;

                \Log::info('MIDTRANS STATUS : ' . $transaction);

            $orderId =
                $notif->order_id;

                \Log::info('MIDTRANS ORDER ID : ' . $orderId);

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

                \Log::info('ORDER FOUND : ' . ($order ? $order->id : 'NULL'));

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
                ||
                $transaction == 'challenge'

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

                if ($order->status == 'pending') {

                    $order->status = 'pending';
                }

                if ($payment && $payment->status != 'paid') {

                    $payment->status = 'pending';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | EXPIRE
            |--------------------------------------------------------------------------
            */

            elseif ($transaction == 'expire') {

                if ($order->status == 'pending') {

                    $order->status = 'dibatalkan';
                }

                if ($payment) {

                    $payment->status = 'expired';
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

                if ($order->status == 'pending') {

                    $order->status = 'dibatalkan';
                }

                if ($payment) {

                    $payment->status = 'failed';
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
        $payment = Payment::find(
            $request->payment_id
        );

        if (!$payment) {

            return response()->json([
                'success' => false,
                'message' => 'Payment tidak ditemukan'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | JANGAN LANGSUNG BATALKAN ORDER
        |--------------------------------------------------------------------------
        |
        | Customer bisa saja menutup popup
        | tetapi QRIS masih aktif di Midtrans.
        |
        */

        return response()->json([
            'success' => true
        ]);
    }
}