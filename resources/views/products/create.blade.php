@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-2 gap-6 items-center">

            <!-- NAMA PRODUK -->
            <label>Nama Produk <span class="text-red-500">*</span></label>
            <input type="text" name="nama_produk" value="{{ old('nama_produk') }}"
                class="border p-2 rounded" required>

            <!-- HARGA -->
            <label>Harga <span class="text-red-500">*</span></label>
            <input type="number" name="harga" value="{{ old('harga') }}"
                class="border p-2 rounded" required>

            <!-- ESTIMASI -->
            <label>Estimasi Produksi <span class="text-red-500">*</span></label>
            <input type="text" name="estimasi" value="{{ old('estimasi') }}"
                class="border p-2 rounded" placeholder="Contoh: 2 Hari" required>

            <!-- STATUS -->
            <label>Status Produk</label>
            <select name="status" class="border rounded px-2 py-1">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <!-- DESKRIPSI -->
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="border p-2 rounded">{{ old('deskripsi') }}</textarea>

            <!-- GAMBAR -->
            <label>Gambar</label>
            <input type="file" name="gambar" class="border p-2 rounded">

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('products.index') }}"
               class="px-6 py-2 border rounded-lg">
                Kembali
            </a>

            <button class="bg-red-400 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                Simpan
            </button>
        </div>

    </form>

</div>
@endsection