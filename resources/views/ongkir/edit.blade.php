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

    <form action="{{ route('ongkir.update', $ongkir->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 items-center">

            <!-- JARAK MINIMUM -->
            <label>
                Jarak Minimum (KM)
                <span class="text-red-500">*</span>
            </label>

            <input type="number"
                   step="0.01"
                   name="jarak_min"
                   value="{{ old('jarak_min', $ongkir->jarak_min) }}"
                   class="border p-2 rounded"
                   required>

            <!-- JARAK MAKSIMUM -->
            <label>
                Jarak Maksimum (KM)
                <span class="text-red-500">*</span>
            </label>

            <input type="number"
                   step="0.01"
                   name="jarak_max"
                   value="{{ old('jarak_max', $ongkir->jarak_max) }}"
                   class="border p-2 rounded"
                   required>

            <!-- TARIF -->
            <label>
                Tarif Ongkir
                <span class="text-red-500">*</span>
            </label>

            <input type="number"
                   name="tarif"
                   value="{{ old('tarif', $ongkir->tarif) }}"
                   class="border p-2 rounded"
                   required>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-4 mt-8">

            <a href="{{ route('ongkir.index') }}"
               class="px-6 py-2 border rounded-lg">

                Kembali

            </a>

            <button
                class="bg-red-400 text-white px-6 py-2 rounded-lg hover:bg-red-600">

                Update

            </button>

        </div>

    </form>

</div>

@endsection