@extends('layouts.customer')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <h1 class="text-2xl font-semibold mb-6">
        Detail Pesanan
    </h1>

    <!-- INFO -->
    <div class="bg-white border rounded-2xl p-5 mb-4 shadow-sm">

        <div class="flex justify-between items-center">
            <div>
                <p class="font-semibold text-gray-800 text-lg">{{ $order->kode }}</p>
                <p class="text-sm text-gray-400">
                    {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d M Y') }}
                </p>
            </div>

            <span class="text-xs px-4 py-1 rounded-full font-medium
                @if($order->status=='diproses') bg-yellow-100 text-yellow-600
                @elseif($order->status=='dikemas') bg-blue-100 text-blue-600
                @elseif($order->status=='dikirim') bg-purple-100 text-purple-600
                @else bg-green-100 text-green-600
                @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

    </div>

    <!-- PRODUK -->
    <div class="bg-white border rounded-2xl p-5 mb-4 shadow-sm">

        <div class="flex items-center justify-between mb-4">
            <p class="font-semibold text-gray-800">Pesanan</p>
            <span class="text-xs text-gray-400">
                {{ $order->details->count() }} item
            </span>
        </div>

        @foreach($order->details as $item)
        <div class="flex justify-between items-center py-3 border-b last:border-0">

            <!-- KIRI -->
            <div>
                <p class="text-sm font-semibold text-gray-800">
                    {{ $item->product->nama_produk ?? '-' }}
                </p>

                <p class="text-xs text-gray-400">
                    x{{ $item->qty }}
                </p>
            </div>

            <!-- KANAN -->
            <div class="text-sm font-semibold text-gray-800">
                Rp {{ number_format($item->harga * $item->qty,0,',','.') }}
            </div>

        </div>
        @endforeach

    </div>

    <!-- RINCIAN -->
    <div class="bg-gray-50 border rounded-2xl p-5 mb-4 text-sm">

        <div class="flex justify-between mb-2">
            <span class="text-gray-500">Total Produk</span>
            <span class="font-medium">
                Rp {{ number_format($order->details->sum(fn($d)=>$d->harga*$d->qty),0,',','.') }}
            </span>
        </div>

        <div class="flex justify-between mb-2">
            <span class="text-gray-500">Biaya Pengiriman</span>
            <span class="font-medium">
                Rp {{ number_format($order->ongkir,0,',','.') }}
            </span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-500">Biaya Admin</span>
            <span class="font-medium">Rp 1.000</span>
        </div>

    </div>

    <!-- TOTAL -->
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex justify-between items-center mb-6">

        <span class="font-semibold text-gray-700">
            Total Pembayaran
        </span>

        <span class="text-md font-bold text-red-500">
            Rp {{ number_format($order->total_harga,0,',','.') }}
        </span>

    </div>

    <!-- BUTTON -->
    <div class="flex justify-between">

        <a href="{{ route('customer.pesananSaya') }}" 
           class="px-5 py-2.5 rounded-xl bg-white border shadow-sm text-gray-700 hover:bg-gray-50 transition text-sm">
            ← Kembali
        </a>

    </div>

</div>

@endsection