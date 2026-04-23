@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 items-center">

            <label>Nama Produk</label>
            <input type="text" name="nama_produk" value="{{ $product->nama_produk }}" class="border p-2 rounded">

            <label>Harga</label>
            <input type="number" name="harga" value="{{ $product->harga }}" class="border p-2 rounded">

            <label>Estimasi Produksi</label>
            <input type="text" name="estimasi" value="{{ $product->estimasi }}" class="border p-2 rounded">

            <label>Status Produk</label>
            <select name="status" class="border rounded px-2 py-1">
                <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            <label>Deskripsi</label>
            <textarea name="deskripsi" class="border p-2 rounded">{{ $product->deskripsi }}</textarea>

            <label>Gambar</label>
            <div>
                @if($product->gambar)
                    <img src="{{ asset('storage/'.$product->gambar) }}" class="w-20 mb-2">
                @endif
                <input type="file" name="gambar" class="border p-2 rounded">
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('products.index') }}" class="px-6 py-2 border rounded-lg">
                Kembali
            </a>

            <button class="bg-red-400 text-white px-6 py-2 rounded-lg">
                Update
            </button>
        </div>

    </form>

</div>
@endsection