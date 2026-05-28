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

                    <img src="{{ asset('images/' . $category->gambar) }}"
                         class="w-20 h-20 object-cover rounded-full mb-2 border">

                @endif

                <input type="file"
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

@endsection