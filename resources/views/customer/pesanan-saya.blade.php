@extends('layouts.customer')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <h1 class="text-2xl font-semibold mb-6">
        Riwayat Pesanan
    </h1>

    <div class="space-y-4">

        @forelse($orders as $order)

        <a href="{{ route('customer.detailPesanan', $order->id) }}" 
           class="block bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">

            <!-- HEADER -->
            <div class="flex justify-between items-start mb-3">

                <div>
                    <p class="font-semibold text-gray-800">{{ $order->kode }}</p>
                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d M Y') }}
                    </p>
                </div>

                <!-- STATUS -->
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    @if($order->status=='diproses') bg-yellow-100 text-yellow-600
                    @elseif($order->status=='dikemas') bg-blue-100 text-blue-600
                    @elseif($order->status=='dikirim') bg-purple-100 text-purple-600
                    @else bg-green-100 text-green-600
                    @endif
                ">
                    {{ ucfirst($order->status) }}
                </span>

            </div>

            <!-- DIVIDER -->
            <div class="border-t my-2"></div>

            <!-- FOOTER -->
            <div class="flex justify-between items-center text-sm">

                <div class="text-gray-500">
                    Total Pembayaran
                </div>

                <div class="font-semibold text-gray-800">
                    Rp {{ number_format($order->total_harga,0,',','.') }}
                </div>

            </div>

        </a>

        @empty

        <!-- EMPTY STATE -->
        <div class="text-center py-16">

            <i data-lucide="package-x" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>

            <p class="text-gray-500 mb-2">Belum ada pesanan</p>

            <p class="text-xs text-gray-400 mb-4">
                Yuk mulai belanja dulu!
            </p>

            <a href="{{ route('customer.beranda') }}" 
               class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600">
                Belanja Sekarang
            </a>

        </div>

        @endforelse

    </div>

</div>

@endsection