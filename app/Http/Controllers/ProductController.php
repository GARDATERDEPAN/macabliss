<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // SEARCH NAMA
        if ($request->search) {

            $query->where('nama_produk', 'like', '%' . $request->search . '%');

        }

        // FILTER STATUS
        if ($request->status) {

            $query->where('status', $request->status);

        }

        $products = $query->latest()
                          ->paginate(5)
                          ->withQueryString();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'nama_produk' => 'required',
            'category_id' => 'required',
            'harga'       => 'required|numeric',
            'status'      => 'required'

        ]);

        // UPLOAD GAMBAR
        if ($request->hasFile('gambar')) {

            $path = $request->file('gambar')
                            ->store('products', 'public');

        } else {

            $path = null;

        }

        // CREATE PRODUCT
        Product::create([

            'category_id' => $request->category_id,
            'nama_produk' => $request->nama_produk,
            'deskripsi'   => $request->deskripsi,
            'harga'       => $request->harga,
            'status'      => $request->status,
            'estimasi'    => $request->estimasi,
            'gambar'      => $path,

        ]);

        return redirect('/products')
            ->with('success', 'Produk berhasil ditambahkan');
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
        $categories = Category::all();

        return view('products.edit', compact(
            'product',
            'categories'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([

            'nama_produk' => 'required',
            'category_id' => 'required',
            'harga'       => 'required|numeric',
            'status'      => 'required'

        ]);

        // CHECK GAMBAR
        if ($request->hasFile('gambar')) {

            $path = $request->file('gambar')
                            ->store('products', 'public');

        } else {

            $path = $product->gambar;

        }

        // UPDATE PRODUCT
        $product->update([

            'category_id' => $request->category_id,
            'nama_produk' => $request->nama_produk,
            'deskripsi'   => $request->deskripsi,
            'harga'       => $request->harga,
            'status'      => $request->status,
            'gambar'      => $path,
            'estimasi'    => $request->estimasi,

        ]);

        return redirect('/products')
            ->with('success', 'Produk berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect('/products')
            ->with('success', 'Produk berhasil dihapus');
    }
}