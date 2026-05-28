<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class CustomerController extends Controller
{
    public function beranda($id = null)
    {
        // KATEGORI + PRODUK
        $categories = Category::with(['products' => function ($query) {
            $query->where('status', 'active')
                  ->latest();
        }])->get();

        if ($id) {

            $products = Product::where('status', 'active')
                        ->where('category_id', $id)
                        ->latest()
                        ->get();

        } else {

            $products = Product::where('status', 'active')
                        ->latest()
                        ->get();
        }

        return view('customer.beranda', compact(
            'products',
            'categories'
        ));
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
                'nama' => $product->nama_produk,
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
        $orders = Order::where(
            'session_id',
            session()->getId()
        )
        ->latest()
        ->get();

        return view('customer.pesanan-saya', compact('orders'));
    }

    public function detailPesanan($id)
    {
        $order = Order::with('details.product')->findOrFail($id);

        return view('customer.detail-pesanan', compact('order'));
    }

    public function pembayaran()
    {
        $order = Order::latest()->first();

        return view('customer.pembayaran', compact('order'));
    }

    public function tentang()
    {
        return view('customer.tentang');
    }
}