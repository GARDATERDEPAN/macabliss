<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_customer', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

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
        $order = Order::with('details.product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status == 'selesai') {
            return back();
        }

        $request->validate([
            'status' => 'required|in:diproses,selesai'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back();
    }

    public function store(Request $request)
    {
        // 🔥 VALIDASI
        $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'tanggal_kirim' => 'required|date|after_or_equal:' . now()->addDays(2)->toDateString(),
            'payment' => 'required'
        ]);

        // 🔥 AMBIL CART
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        // 🔥 HITUNG TOTAL PRODUK
        $totalProduk = 0;
        foreach ($cart as $item) {
            $totalProduk += $item['harga'] * $item['qty'];
        }

        // 🔥 ONGKIR & ADMIN
        $ongkir = $request->ongkir ?? 0;
        $admin = 1000;

        // 🔥 TOTAL AKHIR
        $total = $totalProduk + $ongkir + $admin;

        // 🔥 SIMPAN ORDER
        $order = Order::create([
            'kode' => null,
            'nama_customer' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_pesan' => now(),
            'tanggal_kirim' => $request->tanggal_kirim,
            'metode_pembayaran' => $request->payment,
            'ongkir' => $ongkir,
            'total_harga' => $total,
            'status' => 'diproses'
        ]);

        // 🔥 SIMPAN PAYMENT (HANYA SEKALI DI SINI)
        Payment::create([
            'order_id' => $order->id,
            'kode_pembayaran' => 'PAY-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
            'metode' => $order->metode_pembayaran,
            'payment_ref' => null,
            'tanggal_bayar' => now(),
            'jumlah' => $order->total_harga,
            'status' => 'pending',
        ]);

        // 🔥 SIMPAN DETAIL PRODUK
        foreach ($cart as $id => $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'qty' => $item['qty'],
                'harga' => $item['harga'],
            ]);
        }

        // 🔥 GENERATE KODE ORDER
        $order->update([
            'kode' => 'M-' . str_pad($order->id, 4, '0', STR_PAD_LEFT)
        ]);

        // 🔥 CLEAR SESSION
        session()->forget('cart');

        return redirect()->route('customer.pesanan')
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    // 🔥 OPTIONAL (boleh dipakai kalau mau manual bayar)
    public function bayar($id)
    {
        $order = Order::findOrFail($id);

        // 🔥 CEK SUDAH ADA PAYMENT
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

        return redirect()->route('customer.pesanan')
            ->with('success', 'Pembayaran berhasil dibuat!');
    }
}