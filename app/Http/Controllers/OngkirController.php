<?php

namespace App\Http\Controllers;

use App\Models\Ongkir;
use Illuminate\Http\Request;

class OngkirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ongkir::query();

        if ($request->search) {

            $query->where('jarak_min', 'like', '%' . $request->search . '%')
                  ->orWhere('jarak_max', 'like', '%' . $request->search . '%')
                  ->orWhere('tarif', 'like', '%' . $request->search . '%');
        }

        $ongkirs = $query
            ->orderBy('jarak_min')
            ->paginate(8);

        return view('ongkir.index', compact('ongkirs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ongkir.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jarak_min' => 'required|numeric|min:0',
            'jarak_max' => 'required|numeric|gt:jarak_min',
            'tarif'     => 'required|numeric|min:0',
        ]);

        Ongkir::create([
            'jarak_min' => $request->jarak_min,
            'jarak_max' => $request->jarak_max,
            'tarif'     => $request->tarif,
        ]);

        return redirect()
            ->route('ongkir.index')
            ->with('success', 'Data ongkir berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ongkir = Ongkir::findOrFail($id);

        return view('ongkir.edit', compact('ongkir'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jarak_min' => 'required|numeric|min:0',
            'jarak_max' => 'required|numeric|gt:jarak_min',
            'tarif'     => 'required|numeric|min:0',
        ]);

        $ongkir = Ongkir::findOrFail($id);

        $ongkir->update([
            'jarak_min' => $request->jarak_min,
            'jarak_max' => $request->jarak_max,
            'tarif'     => $request->tarif,
        ]);

        return redirect()
            ->route('ongkir.index')
            ->with('success', 'Data ongkir berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ongkir = Ongkir::findOrFail($id);

        $ongkir->delete();

        return redirect()
            ->route('ongkir.index')
            ->with('success', 'Data ongkir berhasil dihapus');
    }

    /**
     * Get ongkir by jarak (AJAX)
     */
    public function getOngkir($jarak)
    {
        $ongkir = Ongkir::where('jarak_min', '<=', $jarak)
            ->where('jarak_max', '>=', $jarak)
            ->first();

        return response()->json([
            'tarif' => $ongkir ? $ongkir->tarif : 0
        ]);
    }
}