<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {   
        $cart = session()->get('cart', []);
        return view('customer.pesanan', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $cart = session()->get('cart', []);

        if(isset($cart[$product->id])) {
            $cart[$product->id]['qty']++;
        } else {
            $cart[$product->id] = [
                "nama_produk" => $product->nama_produk,
                "harga" => $product->harga,
                "gambar" => $product->gambar,
                "deskripsi" => $product->deskripsi ?? '-',
                "estimasi" => "2 Hari",
                "qty" => 1
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$request->id])) {

            if($request->qty <= 0){
                unset($cart[$request->id]);
            } else {
                $cart[$request->id]['qty'] = $request->qty;
            }

            session()->put('cart', $cart);
        }

        return back();
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart');

        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return back();
    }
}