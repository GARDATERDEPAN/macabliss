<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class CustomerController extends Controller
{
    public function beranda()
    {
        $products = Product::where('status', 'active')
                  ->latest()
                  ->get();

        return view('customer.beranda', compact('products'));
    }

    public function addToCart($id)
    {
        $product = Product::where('id', $id)
                  ->where('status', 'active')
                  ->firstOrFail();

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'nama' => $product->nama,
                'harga' => $product->harga,
                'qty' => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('customer.pesanan', compact('cart'));
    }

    public function pesananSaya()
    {
        $orders = \App\Models\Order::latest()->get();
        return view('customer.pesanan-saya', compact('orders'));
    }

    public function detailPesanan($id)
    {
        $order = \App\Models\Order::with('details.product')->findOrFail($id);
        return view('customer.detail-pesanan', compact('order'));
    }

    public function pembayaran()
    {
        $order = Order::latest()->first(); // ambil order terakhir

        return view('customer.pembayaran', compact('order'));
    }
}
