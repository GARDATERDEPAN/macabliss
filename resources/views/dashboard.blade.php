@extends('layouts.app')

@section('content')

<div class="p-6 space-y-5">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Dashboard Macabliss
            </h1>

        </div>

        <!-- PENDAPATAN -->
        <div class="bg-gradient-to-r from-red-400 to-pink-400
                    rounded-2xl px-6 py-4 text-white shadow-sm min-w-[378px]">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs opacity-80">
                        Total Pendapatan
                    </p>

                    <h1 class="text-3xl font-bold mt-1">

                        Rp {{ number_format($totalPendapatan,0,',','.') }}

                    </h1>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-white/20
                            flex items-center justify-center text-2xl">

                    💰

                </div>

            </div>

        </div>

    </div>


    <!-- SUMMARY -->
    <div class="grid grid-cols-2 xl:grid-cols-3 gap-4">

        <!-- PRODUK -->
        <div class="bg-white border rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-gray-400">
                        Total Produk
                    </p>

                    <h1 class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $totalProduk }}
                    </h1>

                </div>

                <div class="w-12 h-12 rounded-xl bg-red-100
                            flex items-center justify-center text-xl">

                    📦

                </div>

            </div>

        </div>

        <!-- PESANAN -->
        <div class="bg-white border rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-gray-400">
                        Total Pesanan
                    </p>

                    <h1 class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $totalPesanan }}
                    </h1>

                </div>

                <div class="w-12 h-12 rounded-xl bg-yellow-100
                            flex items-center justify-center text-xl">

                    🛒

                </div>

            </div>

        </div>

        <!-- SELESAI -->
        <div class="bg-white border rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-gray-400">
                        Pesanan Selesai
                    </p>

                    <h1 class="text-2xl font-bold text-green-600 mt-1">
                        {{ $pesananSelesai }}
                    </h1>

                </div>

                <div class="w-12 h-12 rounded-xl bg-green-100
                            flex items-center justify-center text-xl">

                    ✅

                </div>

            </div>

        </div>

    </div>


    <!-- STATUS -->
    <div class="grid grid-cols-2 xl:grid-cols-3 gap-4">

        <!-- DIPROSES -->
        <div class="bg-yellow-50 border border-yellow-200
                    rounded-2xl px-5 py-4">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-yellow-600">
                        Diproses
                    </p>

                    <h1 class="text-2xl font-bold text-yellow-700 mt-1">

                        {{ $pesananDiproses }}

                    </h1>

                </div>

                <div class="text-2xl">
                    ⏳
                </div>

            </div>

        </div>

        <!-- LUNAS -->
        <div class="bg-blue-50 border border-blue-200
                    rounded-2xl px-5 py-4">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-blue-600">
                        Payment Lunas
                    </p>

                    <h1 class="text-2xl font-bold text-blue-700 mt-1">

                        {{ \App\Models\Payment::where('status','paid')->count() }}

                    </h1>

                </div>

                <div class="text-2xl">
                    💵
                </div>

            </div>

        </div>

        <!-- PENDING -->
        <div class="bg-red-50 border border-red-200
                    rounded-2xl px-5 py-4">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-red-600">
                        Payment Pending
                    </p>

                    <h1 class="text-2xl font-bold text-red-700 mt-1">

                        {{ \App\Models\Payment::where('status','pending')->count() }}

                    </h1>

                </div>

                <div class="text-2xl">
                    ⚠️
                </div>

            </div>

        </div>

    </div>


    <!-- TABLE SECTION -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        <!-- PESANAN TERBARU -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border">

            <div class="flex justify-between items-center mb-5">

                <h2 class="font-semibold text-gray-700 text-lg">
                    Pesanan Terbaru
                </h2>

                <a href="{{ route('orders.index') }}"
                   class="text-sm text-red-500 hover:underline">

                    Lihat Semua

                </a>

            </div>

            <div class="space-y-3">

                @forelse($latestOrders as $o)

                    <div class="flex justify-between items-center
                                border rounded-2xl p-4 hover:bg-gray-50 transition">

                        <div>

                            <p class="font-semibold text-gray-800">
                                {{ $o->kode }}
                            </p>

                            <p class="text-sm text-gray-400 mt-1">
                                {{ $o->nama_customer }}
                            </p>

                        </div>

                        <div class="text-right">

                            <p class="font-semibold text-gray-700">
                                Rp {{ number_format($o->total_harga,0,',','.') }}
                            </p>

                            <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full

                                {{ $o->status == 'diproses'
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : 'bg-green-100 text-green-700' }}">

                                {{ ucfirst($o->status) }}

                            </span>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-10 text-gray-400">

                        Belum ada pesanan

                    </div>

                @endforelse

            </div>

        </div>


        <!-- PAYMENT TERBARU -->
        <div class="bg-white p-5 rounded-3xl shadow-sm border">

            <div class="flex justify-between items-center mb-5">

                <h2 class="font-semibold text-gray-700 text-lg">
                    Pembayaran Terbaru
                </h2>

                <a href="{{ route('payments.index') }}"
                   class="text-sm text-red-500 hover:underline">

                    Lihat Semua

                </a>

            </div>

            <div class="space-y-3">

                @forelse($latestPayments as $p)

                    <div class="flex justify-between items-center
                                border rounded-2xl p-4 hover:bg-gray-50 transition">

                        <div>

                            <p class="font-semibold text-gray-800">
                                {{ $p->kode_pembayaran }}
                            </p>

                            <p class="text-sm text-gray-400 mt-1">
                                {{ $p->order->kode ?? '-' }}
                            </p>

                        </div>

                        <div class="text-right">

                            <p class="font-semibold text-gray-700">
                                Rp {{ number_format($p->jumlah,0,',','.') }}
                            </p>

                            <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full

                                @if($p->status == 'pending')
                                    bg-yellow-100 text-yellow-700
                                @elseif($p->status == 'paid')
                                    bg-green-100 text-green-700
                                @elseif($p->status == 'expired')
                                    bg-gray-100 text-gray-600
                                @else
                                    bg-red-100 text-red-700
                                @endif">

                                {{ ucfirst($p->status) }}

                            </span>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-10 text-gray-400">

                        Belum ada pembayaran

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection