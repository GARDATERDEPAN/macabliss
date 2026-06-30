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


    <form action="{{ route('categories.update', $category->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 items-center">

            <!-- NAMA KATEGORI -->
            <label>
                Nama Kategori
            </label>

            <input type="text"
                   name="nama_kategori"
                   value="{{ $category->nama_kategori }}"
                   class="border p-2 rounded">


            <!-- GAMBAR -->
            <label>
                Gambar Kategori
            </label>

            <div>

                @if($category->gambar)

                    <div
                        id="preview-gambar"
                        class="relative w-fit mb-3">

                        <img
                            src="{{ asset('storage/' . $category->gambar) }}"
                            class="w-20 h-20 object-cover rounded-full border">

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

                <input
                    type="file"
                    name="gambar"
                    class="border p-2 rounded">

            </div>

        </div>


        <!-- BUTTON -->
        <div class="flex justify-end gap-4 mt-8">

            <a href="{{ route('categories.index') }}"
               class="px-6 py-2 border rounded-lg">

                Kembali

            </a>

            <button class="bg-red-400 text-white px-6 py-2 rounded-lg hover:bg-red-600">

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