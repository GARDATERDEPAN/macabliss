<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
            'status'      => 'required',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'deskripsi'   => 'nullable|string|max:1000',
            'estimasi'    => 'nullable|string|max:100',

        ]);

        // UPLOAD GAMBAR
        if ($request->hasFile('gambar')) {

            $destination = '/home/u872760679/domains/macabliss.com/public_html/storage/products';

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $filename = time().'_'.uniqid().'.'.$request->file('gambar')->extension();

            $request->file('gambar')->move($destination, $filename);

            $path = 'products/'.$filename;

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
            'status'      => 'required',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'deskripsi'   => 'nullable|string|max:1000',
            'estimasi'    => 'nullable|string|max:100'

        ]);

        // HAPUS GAMBAR
        if ($request->hapus_gambar == 1) {

            if ($product->gambar) {

                $file = '/home/u872760679/domains/macabliss.com/public_html/storage/' . $product->gambar;

                if (File::exists($file)) {

                    File::delete($file);

                }

            }

            $path = null;

        }

        // UPLOAD GAMBAR BARU
        elseif ($request->hasFile('gambar')) {

            $destination = '/home/u872760679/domains/macabliss.com/public_html/storage/products';

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            if ($product->gambar) {

                $oldFile = '/home/u872760679/domains/macabliss.com/public_html/storage/' . $product->gambar;

                if (File::exists($oldFile)) {

                    File::delete($oldFile);

                }

            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('gambar')->extension();

            $request->file('gambar')->move($destination, $filename);

            $path = 'products/' . $filename;

        }

        // TIDAK ADA PERUBAHAN GAMBAR
        else {

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
        if ($product->gambar) {

            $file = '/home/u872760679/domains/macabliss.com/public_html/storage/' . $product->gambar;

            if (File::exists($file)) {
                File::delete($file);
            }

        }

        $product->delete();

        return redirect('/products')
            ->with('success', 'Produk berhasil dihapus');
    }
}