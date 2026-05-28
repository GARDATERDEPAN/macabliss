@extends('layouts.customer')

@section('content')

<div class="max-w-4xl mx-auto p-5">

    <!-- HEADER -->
    <div class="mb-5">

        <h1 class="text-2xl font-bold text-gray-800">
            Detail Pesanan
        </h1>

        <p class="text-sm text-gray-400 mt-1">
            Informasi lengkap pesanan customer
        </p>

    </div>

    <!-- CARD -->
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">

        <!-- INFORMASI -->
        <div class="p-5 border-b">

            <div class="grid md:grid-cols-2 gap-4">

                <!-- KIRI -->
                <div class="space-y-3">

                    <div>
                        <p class="text-md text-gray-400 mb-1">
                            Kode Pesanan
                        </p>

                        <div class=" bg-gray-50 border rounded-xl px-3 py-2 font-semibold">
                            {{ $order->kode }}
                        </div>
                    </div>

                    <div>
                        <p class="text-md text-gray-400 mb-1">
                            Nama Customer
                        </p>

                        <div class="bg-gray-50 border rounded-xl px-3 py-2">
                            {{ $order->nama_customer }}
                        </div>
                    </div>

                    <div>
                        <p class="text-md text-gray-400 mb-1">
                            Nomor Handphone
                        </p>

                        <div class="bg-gray-50 border rounded-xl px-3 py-2">
                            {{ $order->no_hp }}
                        </div>
                    </div>

                </div>

                <!-- KANAN -->
                <div class="space-y-3">

                    <div>
                        <p class="text-md text-gray-400 mb-1">
                            Tanggal Pesanan
                        </p>

                        <div class="bg-gray-50 border rounded-xl px-3 py-2">
                            {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div>
                        <p class=" text-md text-gray-400 mb-1">
                            Tanggal Pengiriman
                        </p>

                        <div class="bg-gray-50 border rounded-xl px-3 py-2">
                            {{ \Carbon\Carbon::parse($order->tanggal_kirim)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div>
                        <p class="text-md text-gray-400 mb-1">
                            Metode Pembayaran
                        </p>

                        <div class="bg-gray-50 border rounded-xl px-3 py-2">
                            {{ $order->metode_pembayaran }}
                        </div>
                    </div>

                </div>

            </div>

            <!-- ALAMAT -->
            <div class="mt-4">

                <p class="text-md text-gray-400 mb-1">
                    Alamat Pengiriman
                </p>

                <div class="bg-gray-50 border rounded-xl px-3 py-2">
                    {{ $order->alamat }}
                </div>

            </div>

            <!-- STATUS -->
            <div class="grid md:grid-cols-2 gap-4 mt-4">

                <!-- PAYMENT -->
                <div>

                    <p class="text-md text-gray-400 mb-2">
                        Status Pembayaran
                    </p>

                    @php
                        $paymentStatus = $order->payment->status ?? 'pending';
                    @endphp

                    <div class="bg-gray-50 border rounded-xl px-3 py-2">

                        @if($paymentStatus == 'paid')

                            <span class="bg-green-100 text-green-600 text-md px-3 py-1 rounded-full font-medium">
                                Pembayaran Berhasil
                            </span>

                        @elseif($paymentStatus == 'pending')

                            <span class="bg-yellow-100 text-yellow-600 text-md px-3 py-1 rounded-full font-medium">
                                Menunggu Pembayaran
                            </span>

                        @elseif($paymentStatus == 'expired')

                            <span class="bg-gray-100 text-gray-600 text-md px-3 py-1 rounded-full font-medium">
                                Pembayaran Kadaluarsa
                            </span>

                        @elseif($paymentStatus == 'failed')

                            <span class="bg-red-100 text-red-600 text-md px-3 py-1 rounded-full font-medium">
                                Pembayaran Gagal
                            </span>

                        @elseif($paymentStatus == 'cancelled')

                            <span class="bg-pink-100 text-pink-600 text-md px-3 py-1 rounded-full font-medium">
                                Pembayaran Dibatalkan
                            </span>

                        @endif

                    </div>

                </div>

                <!-- ORDER -->
                <div>

                    <p class="text-md text-gray-400 mb-2">
                        Status Pesanan
                    </p>

                    <div class="bg-gray-50 border rounded-xl px-3 py-2">

                        <span class="text-md px-3 py-1 rounded-full font-medium

                            @if($order->status=='diproses')
                                bg-yellow-100 text-yellow-600

                            @elseif($order->status=='dikemas')
                                bg-blue-100 text-blue-600

                            @elseif($order->status=='dikirim')
                                bg-purple-100 text-purple-600

                            @elseif($order->status=='dibatalkan')
                                bg-red-100 text-red-600

                            @else
                                bg-green-100 text-green-600
                            @endif

                        ">

                            {{ ucfirst($order->status) }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- PRODUK -->
        <div class="p-5 border-b">

            <div class="flex justify-between items-center mb-4">

                <h2 class="text-base font-semibold text-gray-800">
                    Daftar Produk
                </h2>

                <span class="text-md text-gray-400">
                    {{ $order->details->count() }} item
                </span>

            </div>

            <div class="overflow-x-auto rounded-xl border">

                <table class="w-full text-sm">

                    <thead class="bg-red-300 text-gray-900">

                        <tr>

                            <th class="px-3 py-3 text-center">
                                Produk
                            </th>

                            <th class="px-3 py-3 text-center">
                                Qty
                            </th>

                            <th class="px-3 py-3 text-center">
                                Harga
                            </th>

                            <th class="px-3 py-3 text-center">
                                Subtotal
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($order->details as $item)

                        <tr class="border-t">

                            <td class="px-3 py-3 text-center font-medium">
                                {{ $item->product->nama_produk ?? '-' }}
                            </td>

                            <td class="px-3 py-3 text-center">
                                {{ $item->qty }}
                            </td>

                            <td class="px-3 py-3 text-center">
                                Rp {{ number_format($item->harga,0,',','.') }}
                            </td>

                            <td class="px-3 py-3 text-center font-semibold">
                                Rp {{ number_format($item->harga * $item->qty,0,',','.') }}
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <!-- RINGKASAN -->
        <div class="p-5">

            <div class="bg-gray-50 border rounded-2xl p-5">

                <h2 class="text-base font-semibold text-gray-800 mb-4">
                    Ringkasan Pembayaran
                </h2>

                <div class="space-y-2 text-sm">

                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Total Produk
                        </span>

                        <span class="font-medium">
                            Rp {{ number_format($order->details->sum(fn($d)=>$d->harga*$d->qty),0,',','.') }}
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Ongkos Kirim
                        </span>

                        <span class="font-medium">
                            Rp {{ number_format($order->ongkir,0,',','.') }}
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Biaya Admin
                        </span>

                        <span class="font-medium">
                            Rp 1.000
                        </span>

                    </div>

                    <hr class="my-3">

                    <div class="bg-gray-50 flex justify-between items-center">

                        <span class="font-semibold text-gray-800">
                            Total Pembayaran
                        </span>

                        <span class="text-xl font-bold text-red-500">
                            Rp {{ number_format($order->total_harga,0,',','.') }}
                        </span>

                    </div>

                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-end mt-5">

                <a href="{{ route('customer.pesananSaya') }}"
                   class="px-5 py-2 rounded-xl bg-white border shadow-sm
                          text-gray-700 hover:bg-gray-50 transition text-sm">

                    Kembali

                </a>

            </div>

        </div>

    </div>

</div>

@endsection