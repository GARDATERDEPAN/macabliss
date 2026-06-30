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


    <form action="{{ route('products.update', $product->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 items-center">

            <!-- NAMA PRODUK -->
            <label>
                Nama Produk
            </label>

            <input type="text"
                   name="nama_produk"
                   value="{{ $product->nama_produk }}"
                   class="border p-2 rounded">


            <!-- KATEGORI -->
            <label>
                Kategori
            </label>

            <select name="category_id"
                    class="border p-2 rounded">

                @foreach($categories as $category)

                    <option value="{{ $category->id }}"
                        {{ $product->category_id == $category->id ? 'selected' : '' }}>

                        {{ $category->nama_kategori }}

                    </option>

                @endforeach

            </select>


            <!-- HARGA -->
            <label>
                Harga
            </label>

            <input type="number"
                   name="harga"
                   value="{{ $product->harga }}"
                   class="border p-2 rounded">


            <!-- ESTIMASI -->
            <label>
                Estimasi Produksi
            </label>

            <input type="text"
                   name="estimasi"
                   value="{{ $product->estimasi }}"
                   class="border p-2 rounded">


            <!-- STATUS -->
            <label>
                Status Produk
            </label>

            <select name="status"
                    class="border rounded px-2 py-1">

                <option value="active"
                    {{ $product->status == 'active' ? 'selected' : '' }}>

                    Active

                </option>

                <option value="inactive"
                    {{ $product->status == 'inactive' ? 'selected' : '' }}>

                    Inactive

                </option>

            </select>


            <!-- DESKRIPSI -->
            <label>
                Deskripsi
            </label>

            <textarea name="deskripsi"
                      class="border p-2 rounded">{{ $product->deskripsi }}</textarea>


            <!-- GAMBAR -->
            <label>
                Gambar
            </label>

            <div>

                @if($product->gambar)

                    <div
                        id="preview-gambar"
                        class="relative w-fit mb-3">

                        <img
                            src="{{ asset('storage/' . $product->gambar) }}"
                            class="w-20 h-20 object-cover rounded border">

                        <button
                            type="button"
                            onclick="hapusGambar()"
                            class="absolute -top-2 -right-2
                                w-6 h-6
                                bg-red-500
                                text-white
                                rounded-full
                                text-xs
                                hover:bg-red-600">

                            ✕

                        </button>

                    </div>

                    <input
                        type="hidden"
                        id="hapus_gambar"
                        name="hapus_gambar"
                        value="0">

                    @endif

                <input type="file"
                    name="gambar"
                    class="border p-2 rounded">

            </div>

        </div>

        <!-- BUTTON -->
        <div class="w-full flex justify-end gap-4 mt-8">

            <a href="{{ route('products.index') }}"
            class="px-6 py-2 border rounded-lg">

                Kembali

            </a>

            <button
                type="submit"
                class="bg-red-400 text-white px-6 py-2 rounded-lg hover:bg-red-600">

                Update

            </button>

        </div>

    </form>

</div>

<script>

function hapusGambar() {

    document
        .getElementById('hapus_gambar')
        .value = 1;

    document
        .getElementById('preview-gambar')
        .style.display = 'none';

}

</script>

@endsection