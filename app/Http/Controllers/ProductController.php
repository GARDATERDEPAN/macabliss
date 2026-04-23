<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // SEARCH NAMA
        if ($request->search) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // FILTER STATUS
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(5)->withQueryString();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'status' => 'required'
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
        } else {
            $path = null;
        }

        Product::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi'   => $request->deskripsi,
            'harga'       => $request->harga,
            'status'      => $request->status,
            'estimasi'    => $request->estimasi,
            'gambar'      => $path,
        ]);

        return redirect('/products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
        } else {
            $path = $product->gambar;
        }

        $product->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi'   => $request->deskripsi,
            'harga'       => $request->harga,
            'status'      => $request->status,
            'gambar'      => $path,
            'estimasi'    => $request->estimasi,
        ]);

        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect('/products');
    }
}
