@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-4">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Dashboard Macabliss
            </h1>

            
        </div>

        <div class="bg-white px-4 py-2 rounded-2xl border shadow-sm">

            <p class="text-[11px] text-gray-400">
                Hari Ini
            </p>

            <h3 class="text-base font-semibold text-slate-700">
                {{ now()->translatedFormat('d F Y') }}
            </h3>

        </div>

    </div>


    {{-- SUMMARY --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- TOTAL PENDAPATAN --}}
        <div
            class="relative overflow-hidden
                   rounded-2xl
                   p-5
                   text-white
                   shadow-lg
                   bg-gradient-to-br
                   from-red-500
                   via-rose-500
                   to-orange-400"
        >

            <div
                class="absolute
                       -right-4
                       -top-4
                       w-24
                       h-24
                       rounded-full
                       bg-white/10"
            ></div>

            <div
                class="absolute
                       right-4
                       bottom-2
                       text-4xl
                       opacity-10"
            >
                💰
            </div>

            <p class="text-xs text-red-100">
                Total Pendapatan
            </p>

            <h2 class="text-2xl font-bold mt-2">
                Rp {{ number_format($totalPendapatan,0,',','.') }}
            </h2>

            

        </div>


        {{-- CUSTOMER --}}
        <div
            class="bg-white
                   rounded-2xl
                   p-5
                   border
                   shadow-sm"
        >

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-gray-400">
                        Customer Aktif
                    </p>

                    <h2 class="text-2xl font-bold mt-2 text-slate-800">
                        {{ $totalCustomer }}
                    </h2>

                </div>

                <div
                    class="w-12
                           h-12
                           rounded-xl
                           bg-blue-100
                           flex
                           items-center
                           justify-center
                           text-lg"
                >
                    👥
                </div>

            </div>

        </div>


        {{-- PRODUK --}}
        <div
            class="bg-white
                   rounded-2xl
                   p-5
                   border
                   shadow-sm"
        >

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-gray-400">
                        Total Produk
                    </p>

                    <h2 class="text-2xl font-bold mt-2 text-slate-800">
                        {{ $totalProduk }}
                    </h2>

                </div>

                <div
                    class="w-12
                           h-12
                           rounded-xl
                           bg-green-100
                           flex
                           items-center
                           justify-center
                           text-lg"
                >
                    📦
                </div>

            </div>

        </div>


        {{-- PESANAN --}}
        <div
            class="bg-white
                   rounded-2xl
                   p-5
                   border
                   shadow-sm"
        >

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-xs text-gray-400">
                        Total Pesanan
                    </p>

                    <h2 class="text-2xl font-bold mt-2 text-slate-800">
                        {{ $totalPesanan }}
                    </h2>

                </div>

                <div
                    class="w-12
                           h-12
                           rounded-xl
                           bg-yellow-100
                           flex
                           items-center
                           justify-center
                           text-lg"
                >
                    🛒
                </div>

            </div>

        </div>

    </div>


    {{-- CHART + BEST SELLER --}}
    <div class="grid lg:grid-cols-3 gap-4">

        {{-- CHART --}}
        <div
            class="lg:col-span-2
                   bg-white
                   rounded-2xl
                   border
                   shadow-sm
                   p-5"
        >

            <div class="flex justify-between items-center mb-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Penjualan per Bulan
                    </h2>

                    {{-- <p class="text-xs text-gray-400 mt-1">
                        Statistik pendapatan {{ now()->year }}
                    </p> --}}

                </div>

                <span
                    class="bg-red-100
                           text-red-600
                           px-3
                           py-1
                           rounded-xl
                           text-xs
                           font-semibold"
                >
                    {{ now()->year }}
                </span>

            </div>

            <div class="h-[270px]">
                <canvas id="salesChart"></canvas>
            </div>

        </div>

                {{-- BEST SELLER --}}
        <div
            class="bg-white
                   rounded-2xl
                   border
                   shadow-sm
                   p-5"
        >

            <div class="flex justify-between items-center mb-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Best Seller
                    </h2>

                </div>

                <div
                    class="w-10
                           h-10
                           rounded-xl
                           bg-red-100
                           flex
                           items-center
                           justify-center
                           text-sm"
                >
                    🔥
                </div>

            </div>

            <div class="space-y-3">

                @forelse($bestProducts->take(3) as $index => $product)

                    <div
                        class="flex
                               items-center
                               justify-between
                               bg-slate-50
                               rounded-xl
                               p-3"
                    >

                        <div class="flex items-center gap-3">

                            <div
                                class="w-10
                                       h-10
                                       rounded-xl
                                       bg-gradient-to-br
                                       from-red-500
                                       to-orange-400
                                       text-white
                                       flex
                                       items-center
                                       justify-center
                                       text-sm
                                       font-bold"
                            >
                                {{ $index + 1 }}
                            </div>

                            <div>

                                <h3 class="text-sm font-semibold text-slate-800">
                                    {{ $product->nama_produk }}
                                </h3>

                            </div>

                        </div>

                        <span
                            class="px-3
                                   py-1
                                   rounded-lg
                                   bg-red-100
                                   text-red-600
                                   text-xs
                                   font-semibold"
                        >
                            {{ $product->total_terjual }}
                        </span>

                    </div>

                @empty

                    <div class="text-center py-6 text-sm text-gray-400">
                        Belum ada data
                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- PESANAN + PEMBAYARAN --}}
    <div class="grid lg:grid-cols-2 gap-4">


        {{-- PESANAN TERBARU --}}
        <div
            class="bg-white
                   rounded-2xl
                   border
                   shadow-sm
                   p-5"
        >

            <div class="flex justify-between items-center mb-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Pesanan Terbaru
                    </h2>

                    

                </div>

                <a
                    href="{{ url('/orders') }}"
                    class="text-red-500
                           text-xs
                           font-semibold"
                >
                    Lihat Semua
                </a>

            </div>


            <div class="space-y-3">

                @forelse($latestOrders->take(3) as $order)

                    @php

                        $orderStatusColor = match($order->status) {

                            'pending' => 'bg-gray-100 text-gray-600',

                            'diproses' => 'bg-yellow-100 text-yellow-600',

                            'dikemas' => 'bg-blue-100 text-blue-600',

                            'dikirim' => 'bg-purple-100 text-purple-600',

                            'selesai' => 'bg-green-100 text-green-600',

                            'dibatalkan' => 'bg-red-100 text-red-600',

                            default => 'bg-gray-100 text-gray-600'

                        };

                    @endphp

                    <div
                        class="border
                               rounded-xl
                               p-3"
                    >

                        <div class="flex items-center gap-2 mb-2">

                            <h3
                                class="text-sm
                                       font-bold
                                       text-slate-800"
                            >
                                {{ $order->kode ?? 'ORD-'.$order->id }}
                            </h3>

                            <span
                                class="
                                    px-2
                                    py-1
                                    rounded-lg
                                    text-[10px]
                                    font-semibold
                                    {{ $orderStatusColor }}
                                "
                            >
                                {{
                                    match($order->status) {

                                        'pending' => 'Pending',

                                        'diproses' => 'Diproses',

                                        'dikemas' => 'Dikemas',

                                        'dikirim' => 'Dikirim',

                                        'selesai' => 'Selesai',

                                        'dibatalkan' => 'Dibatalkan',

                                        default => '-'

                                    }
                                }}
                            </span>

                        </div>


                        <p class="text-xs text-gray-500 mb-2">
                            👤
                            {{ $order->user->name ?? $order->nama_customer }}
                        </p>


                        <div class="space-y-1">

                            @foreach($order->details as $detail)

                                <div
                                    class="text-xs
                                           text-slate-700"
                                >

                                    •
                                    {{ $detail->product->nama_produk ?? '-' }}
                                    x{{ $detail->qty }}

                                </div>

                            @endforeach

                        </div>

                    </div>

                @empty

                    <div class="text-center py-6 text-sm text-gray-400">
                        Belum ada pesanan
                    </div>

                @endforelse

            </div>

        </div>

                {{-- PEMBAYARAN TERBARU --}}
        <div
            class="bg-white
                   rounded-2xl
                   border
                   shadow-sm
                   p-5"
        >

            <div class="flex justify-between items-center mb-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Pembayaran Terbaru
                    </h2>

                    

                </div>

                <a
                    href="{{ url('/payments') }}"
                    class="text-red-500
                           text-xs
                           font-semibold"
                >
                    Lihat Semua
                </a>

            </div>


            <div class="space-y-3">

                @forelse($latestPayments->take(3) as $payment)

                    @php

                        $statusColor = match($payment->status) {

                            'paid' => 'bg-green-100 text-green-600',

                            'pending' => 'bg-yellow-100 text-yellow-600',

                            'expired' => 'bg-gray-100 text-gray-600',

                            'failed' => 'bg-red-100 text-red-600',

                            'cancelled' => 'bg-red-100 text-red-600',

                            default => 'bg-gray-100 text-gray-600'

                        };

                    @endphp

                    <div
                        class="border
                               rounded-xl
                               p-3"
                    >

                        <div
                            class="flex
                                   justify-between
                                   items-start"
                        >

                            <div>

                                <h3
                                    class="text-sm
                                           font-bold
                                           text-slate-800"
                                >
                                    {{ $payment->kode_pembayaran }}
                                </h3>

                                <p
                                    class="text-xs
                                           text-gray-500
                                           mt-2"
                                >
                                    📦
                                    {{ $payment->order->kode ?? 'ORD-'.$payment->order_id }}
                                </p>

                                <p
                                    class="text-xs
                                           text-gray-500
                                           mt-1"
                                >
                                    👤
                                    {{ $payment->order->user->name ?? $payment->order->nama_customer }}
                                </p>

                                <p
                                    class="text-xs
                                           text-gray-500
                                           mt-1"
                                >
                                    💳
                                    {{ $payment->metode ?? 'QRIS' }}
                                </p>

                            </div>


                            <div class="text-right">

                                <h3
                                    class="text-base
                                           font-bold
                                           text-green-600"
                                >
                                    Rp {{ number_format($payment->jumlah,0,',','.') }}
                                </h3>

                                <span
                                    class="inline-block
                                           mt-2
                                           px-2
                                           py-1
                                           rounded-lg
                                           text-[10px]
                                           font-semibold
                                           {{ $statusColor }}"
                                >
                                    {{
                                        match($payment->status) {

                                            'paid' => 'Lunas',

                                            'pending' => 'Pending',

                                            'expired' => 'Expired',

                                            'failed' => 'Gagal',

                                            'cancelled' => 'Dibatalkan',

                                            default => '-'

                                        }
                                    }}
                                </span>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-6 text-sm text-gray-400">
                        Belum ada pembayaran
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const ctx = document.getElementById('salesChart');

    if (!ctx) return;

    new Chart(ctx, {

        type: 'line',

        data: {

            labels: @json($months),

            datasets: [

                {

                    data: @json($monthlySales),

                    borderColor: '#ef4444',

                    backgroundColor: 'rgba(239,68,68,0.10)',

                    fill: true,

                    tension: 0.4,

                    borderWidth: 3,

                    pointRadius: 3,

                    pointHoverRadius: 5,

                    pointBackgroundColor: '#ef4444'

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    display: false

                },

                tooltip: {

                    callbacks: {

                        label: function(context) {

                            return 'Rp ' +
                                new Intl.NumberFormat(
                                    'id-ID'
                                ).format(
                                    context.raw
                                );

                        }

                    }

                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    grid: {

                        color: '#f3f4f6',

                        drawBorder: false

                    },

                    ticks: {

                        font: {

                            size: 11

                        },

                        callback: function(value) {

                            return 'Rp ' +
                                new Intl.NumberFormat(
                                    'id-ID'
                                ).format(
                                    value
                                );

                        }

                    }

                },

                x: {

                    grid: {

                        display: false

                    },

                    ticks: {

                        font: {

                            size: 11

                        }

                    }

                }

            }

        }

    });

});

</script>

@endsection