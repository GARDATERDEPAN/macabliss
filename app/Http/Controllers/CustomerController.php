<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class CustomerController extends Controller
{
    public function beranda($id = null)
    {
        // KATEGORI + PRODUK
        $categories = Category::with([

            'products' => function ($query) {

                $query->with('ratings')
                    ->where('status', 'active')
                    ->latest();

            }

        ])->get();


        // GALERI PRODUK
        $galeries = Product::with('ratings')
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();


        if ($id) {

            $products = Product::with('ratings')
                ->where('status', 'active')
                ->where('category_id', $id)
                ->latest()
                ->get();

        } else {

            $products = Product::with('ratings')
                ->where('status', 'active')
                ->latest()
                ->get();

        }

        $testimonials = \App\Models\ProductRating::with([
            'user',
            'product'
        ])
        ->whereNotNull('komentar')
        ->latest()
        ->take(6)
        ->get();

        return view('customer.beranda', compact(
            'products',
            'categories',
            'galeries',
            'testimonials'
        ));
    }

    public function addToCart($id)
    {
        $product = Product::where('id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $cart[$id]['qty']++;

        } else {

            $cart[$id] = [
                'nama' => $product->nama_produk,
                'harga' => $product->harga,
                'qty'   => 1
            ];

        }

        session()->put('cart', $cart);

        return redirect()
            ->back()
            ->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function index()
    {
        $cart = session()->get('cart', []);

        return view('customer.pesanan', compact('cart'));
    }

    public function pesananSaya()
    {
        $orders = Order::with([
                'payment',
                'details'
            ])
            ->where(
                'user_id',
                Auth::guard('customer')->id()
            )
            ->latest()
            ->get();

        return view(
            'customer.pesanan-saya',
            compact('orders')
        );
    }

    public function detailPesanan($id)
    {
        $order = Order::with([
                'details.product',
                'payment'
            ])
            ->where(
                'id',
                $id
            )
            ->where(
                'user_id',
                Auth::guard('customer')->id()
            )
            ->firstOrFail();

        return view(
            'customer.detail-pesanan',
            compact('order')
        );
    }

    public function pembayaran()
    {
        $order = Order::where(
                'user_id',
               Auth::guard('customer')->id()
            )
            ->latest()
            ->first();

        return view(
            'customer.pembayaran',
            compact('order')
        );
    }
}