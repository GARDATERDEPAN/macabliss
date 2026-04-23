@extends('layouts.customer')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <h1 class="text-2xl font-semibold mb-6">
        Pesanan Anda
    </h1>

    <div class="bg-white rounded-xl shadow border">

        @php $total = 0; @endphp

        @forelse($cart as $id => $item)
        @php 
            $subtotal = $item['harga'] * $item['qty']; 
            $total += $subtotal;
        @endphp

        <div class="p-4 flex justify-between items-start border-b last:border-b-0">

            <!-- LEFT -->
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800">
                    {{ $item['nama_produk'] }}
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $item['deskripsi'] ?? '-' }}
                </p>

                <p class="mt-2 text-sm font-semibold text-gray-900">
                    Rp {{ number_format($item['harga'],0,',','.') }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    Estimasi : {{ $item['estimasi'] ?? '2 Hari' }}
                </p>
            </div>

            <!-- RIGHT -->
            <div class="flex flex-col items-center gap-2 w-[90px]">

                <!-- FOTO -->
                @if(!empty($item['gambar']) && file_exists(public_path('storage/'.$item['gambar'])))
                    <img src="{{ asset('storage/'.$item['gambar']) }}" 
                         class="w-16 h-16 object-cover rounded-lg shadow-sm">
                @else
                    <div class="w-16 h-16 bg-gray-200 flex items-center justify-center text-xs text-gray-400 rounded-lg">
                        No Image
                    </div>
                @endif

                <!-- QTY -->
                <form action="{{ route('cart.update') }}" method="POST" 
                      class="flex items-center gap-2 bg-red-400 px-2 py-1 rounded-full">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">

                    <button type="submit" name="qty" value="{{ $item['qty'] - 1 }}"
                        class="text-sm text-white px-1">-</button>

                    <span class="text-sm text-white font-medium min-w-[20px] text-center">
                        {{ $item['qty'] }}
                    </span>

                    <button type="submit" name="qty" value="{{ $item['qty'] + 1 }}"
                        class="text-sm text-white px-1">+</button>
                </form>

                <!-- HAPUS -->
                <form action="{{ route('cart.remove') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">
                    <button class="text-red-500 text-xs hover:underline">
                        Hapus
                    </button>
                </form>

            </div>

        </div>

        @empty
        <div class="p-6 text-center text-gray-400">
            Belum ada pesanan
        </div>
        @endforelse


        {{-- 🔥 ADD MORE (GOJEK STYLE) --}}
        @if(count($cart) > 0)
        <div class="p-4 border-t flex justify-between items-center">

            <div>
                <p class="text-sm font-semibold text-gray-700">
                    Mau tambah produk lagi?
                </p>
                <p class="text-xs text-gray-400">
                    Tambahkan item lainnya sebelum checkout
                </p>
            </div>

            <a href="{{ route('customer.beranda') }}" 
               class="border border-red-400 text-red-500 px-4 py-2 rounded-full text-sm hover:bg-red-50 transition">
                + Tambah
            </a>

        </div>
        @endif


        @if(count($cart) > 0)

<!-- TOTAL -->
<div class="p-4 border-t flex justify-between font-semibold">
    <span>Total</span>
    <span>Rp {{ number_format($total,0,',','.') }}</span>
</div>

<!-- CHECKOUT BUTTON -->
<div class="p-4">
    <a href="{{ route('customer.pembayaran') }}" 
       class="block w-full text-center bg-red-400 text-white py-3 rounded-lg 
              font-semibold hover:bg-red-500 transition">
        Checkout
    </a>
</div>

@endif

    </div>

</div>

@endsection