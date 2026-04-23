@extends('layouts.app')

@section('content')
<div class="p-6 space-y-4">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-500">Ringkasan sistem Macabliss</p>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-4 gap-6">

        <div class="bg-white p-3 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-gray-400 text-sm">Total Produk</p>
            <h1 class="text-xl font-bold text-gray-800 mt-2">{{ $totalProduk }}</h1>
        </div>

        <div class="bg-white p-3 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-gray-400 text-sm">Total Pesanan</p>
            <h1 class="text-xl font-bold text-gray-800 mt-2">{{ $totalOrder }}</h1>
        </div>

        <div class="bg-white p-3 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-gray-400 text-sm">Pembayaran</p>
            <h1 class="text-xl font-bold text-gray-800 mt-2">{{ $totalPayment }}</h1>
        </div>

        <div class="bg-gradient-to-r from-red-400 to-pink-400 p-3 rounded-2xl text-white shadow hover:shadow-lg transition">
            <p class="text-sm opacity-80">Pendapatan</p>
            <h1 class="text-xl font-bold mt-2">
                Rp {{ number_format($totalPendapatan) }}
            </h1>
        </div>

    </div>

    <!-- STATUS -->
    <div class="grid grid-cols-2 gap-6">

        <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-xl">
            <p class="text-yellow-600 text-sm">Pesanan Diproses</p>
            <h1 class="text-2xl font-bold text-yellow-700 mt-2">{{ $orderDiproses }}</h1>
        </div>

        <div class="bg-green-50 border border-green-200 p-3 rounded-xl">
            <p class="text-green-600 text-sm">Pesanan Selesai</p>
            <h1 class="text-2xl font-bold text-green-700 mt-2">{{ $orderSelesai }}</h1>
        </div>

    </div>

    <!-- TABLE SECTION -->
    <div class="grid grid-cols-2 gap-6">

        <!-- ORDER TERBARU -->
        <div class="bg-white p-3 rounded-xl shadow">
            <h2 class="font-semibold text-gray-700 mb-4">Pesanan Terbaru</h2>

            @forelse($latestOrders as $o)
                <div class="flex justify-between items-center py-2 border-b last:border-none">
                    <div>
                        <p class="font-medium text-gray-800">{{ $o->kode }}</p>
                        <p class="text-sm text-gray-400">{{ $o->nama_customer }}</p>
                    </div>

                    <span class="px-2 py-1 text-xs rounded-full
                        {{ $o->status == 'diproses' 
                            ? 'bg-yellow-100 text-yellow-700' 
                            : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($o->status) }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada pesanan</p>
            @endforelse
        </div>

        <!-- PAYMENT TERBARU -->
        <div class="bg-white p-5 rounded-2xl shadow">
            <h2 class="font-semibold text-gray-700 mb-4">Pembayaran Terbaru</h2>

            @forelse($latestPayments as $p)
                <div class="flex justify-between items-center py-2 border-b last:border-none">
                    <div>
                        <p class="font-medium text-gray-800">{{ $p->kode_pembayaran }}</p>
                        <p class="text-sm text-gray-400">
                            {{ $p->order->kode ?? '-' }}
                        </p>
                    </div>

                    <span class="px-2 py-1 text-xs rounded-full
                        {{ $p->status == 'pending' 
                            ? 'bg-yellow-100 text-yellow-700' 
                            : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($p->status) }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada pembayaran</p>
            @endforelse
        </div>

    </div>

</div>
@endsection