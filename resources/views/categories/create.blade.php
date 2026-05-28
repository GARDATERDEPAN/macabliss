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


    <form action="{{ route('categories.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="grid grid-cols-2 gap-6 items-center">

            <!-- NAMA KATEGORI -->
            <label>
                Nama Kategori
                <span class="text-red-500">*</span>
            </label>

            <input type="text"
                   name="nama_kategori"
                   value="{{ old('nama_kategori') }}"
                   class="border p-2 rounded"
                   placeholder="Contoh: Cookies"
                   required>


            <!-- GAMBAR -->
            <label>
                Gambar Kategori
            </label>

            <input type="file"
                   name="gambar"
                   class="border p-2 rounded">

        </div>


        <!-- BUTTON -->
        <div class="flex justify-end gap-4 mt-8">

            <a href="{{ route('categories.index') }}"
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