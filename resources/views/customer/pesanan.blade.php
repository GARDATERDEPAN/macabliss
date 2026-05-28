@extends('layouts.customer')

@section('content')

<div class="p-6 max-w-5xl mx-auto">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">
        Pesanan Anda
    </h1>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">

        @php $total = 0; @endphp

        @forelse($cart as $id => $item)

        @php 
            $subtotal = $item['harga'] * $item['qty']; 
            $total += $subtotal;
        @endphp

        <!-- ITEM -->
        <div class="p-6 border-b border-gray-100 last:border-b-0">

            <div class="flex justify-between items-center gap-5">

                <!-- LEFT -->
                <div class="flex-1">

                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $item['nama_produk'] }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                        {{ $item['deskripsi'] ?? 'Mantulity' }}
                    </p>

                    <p class="mt-3 text-xl font-bold text-gray-900">
                        Rp {{ number_format($item['harga'],0,',','.') }}
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Estimasi : {{ $item['estimasi'] ?? '2 Hari' }}
                    </p>

                </div>

                <!-- RIGHT -->
                <div class="w-[120px] flex flex-col items-center">

                    <!-- FOTO -->
                    @if(!empty($item['gambar']) && file_exists(public_path('storage/'.$item['gambar'])))

                        <img src="{{ asset('storage/'.$item['gambar']) }}"
                             class="w-24 h-24 object-cover rounded-2xl shadow-sm">

                    @else

                        <div class="w-24 h-24 bg-gray-200 rounded-2xl
                                    flex items-center justify-center
                                    text-xs text-gray-500">

                            No Image

                        </div>

                    @endif

                    <!-- COUNTER -->
                    <form action="{{ route('cart.update') }}" 
                          method="POST"
                          class="flex items-center justify-between
                                 bg-red-400 text-white
                                 rounded-full px-4 py-2
                                 w-full mt-3">
                        @csrf

                        <input type="hidden" name="id" value="{{ $id }}">

                        <button type="submit"
                                name="qty"
                                value="{{ $item['qty'] - 1 }}"
                                class="text-lg font-bold">

                            -

                        </button>

                        <span class="text-sm font-bold">
                            {{ $item['qty'] }}
                        </span>

                        <button type="submit"
                                name="qty"
                                value="{{ $item['qty'] + 1 }}"
                                class="text-lg font-bold">

                            +

                        </button>

                    </form>

                    <!-- HAPUS -->
                    <form action="{{ route('cart.remove') }}" 
                          method="POST"
                          class="mt-3">
                        @csrf

                        <input type="hidden" name="id" value="{{ $id }}">

                        <button class="text-red-500 text-sm hover:underline">
                            Hapus
                        </button>

                    </form>

                </div>

            </div>

        </div>

        @empty

        <div class="p-10 text-center text-gray-400 text-lg">
            Belum ada pesanan
        </div>

        @endforelse


        {{-- TAMBAH PRODUK --}}
        @if(count($cart) > 0)

        <div class="p-6 border-t flex justify-between items-center">

            <div>

                <p class="text-lg font-semibold text-gray-800">
                    Mau tambah produk lagi?
                </p>

                <p class="text-sm text-gray-400">
                    Tambahkan item lainnya sebelum checkout
                </p>

            </div>

            <a href="{{ route('customer.beranda') }}"
               class="border border-red-400 text-red-500
                      px-6 py-3 rounded-full
                      hover:bg-red-50 transition">

                Tambah

            </a>

        </div>

        <!-- TOTAL -->
        <div class="p-6 border-t flex justify-between items-center">

            <span class="text-xl font-bold text-gray-800">
                Total
            </span>

            <span class="text-xl font-bold text-gray-900">
                Rp {{ number_format($total,0,',','.') }}
            </span>

        </div>

        <!-- CHECKOUT -->
        <div class="p-6 pt-0">

            <a href="{{ route('customer.pembayaran') }}"
               class="block w-full text-center
                      bg-red-400 text-white
                      py-4 rounded-2xl
                      font-semibold text-lg
                      hover:bg-red-500 transition">

                Checkout

            </a>

        </div>

        @endif

    </div>

</div>

@endsection