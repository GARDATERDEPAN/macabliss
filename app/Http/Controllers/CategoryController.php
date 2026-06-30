<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\File;

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
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {

            $destination = '/home/u872760679/domains/macabliss.com/public_html/storage/categories';

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();

            $request->file('gambar')->move($destination, $filename);

            $gambar = 'categories/' . $filename;
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
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $gambar = $category->gambar;

            // HAPUS GAMBAR
            if ($request->hapus_gambar == 1) {

                if ($category->gambar) {

                    $oldFile = '/home/u872760679/domains/macabliss.com/public_html/storage/' . $category->gambar;

                    if (File::exists($oldFile)) {

                        File::delete($oldFile);

                    }

                }

                $gambar = null;

            }

            // UPLOAD GAMBAR BARU
            elseif ($request->hasFile('gambar')) {

                $destination = '/home/u872760679/domains/macabliss.com/public_html/storage/categories';

                if (!File::exists($destination)) {
                    File::makeDirectory($destination, 0755, true);
                }

                // Hapus gambar lama
                if ($category->gambar) {

                    $oldFile = '/home/u872760679/domains/macabliss.com/public_html/storage/' . $category->gambar;

                    if (File::exists($oldFile)) {

                        File::delete($oldFile);

                    }

                }

                $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();

                $request->file('gambar')->move($destination, $filename);

                $gambar = 'categories/' . $filename;

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

        if ($category->gambar) {

            $file = '/home/u872760679/domains/macabliss.com/public_html/storage/' . $category->gambar;

            if (File::exists($file)) {
                File::delete($file);
            }
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}