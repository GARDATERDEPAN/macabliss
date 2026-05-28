<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('products')

            ->when($request->search, function ($query) use ($request) {

                $query->where('nama_kategori', 'like', '%' . $request->search . '%');

            })

            ->latest()

            ->paginate(5);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'gambar' => 'nullable|image'
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            $gambar = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('images'), $gambar);
        }

        Category::create([
            'nama_kategori' => $request->nama_kategori,
            'gambar' => $gambar
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required',
            'gambar' => 'nullable|image'
        ]);

        $gambar = $category->gambar;

        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            $gambar = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('images'), $gambar);
        }

        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'gambar' => $gambar
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}