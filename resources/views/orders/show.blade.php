@extends('layouts.app')

@section('content')
<div class="p-6 max-w-6xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Detail Pesanan
            </h1>

        </div>

    </div>

    <!-- MAIN CARD -->
    <div class="bg-white rounded-2xl shadow border overflow-hidden">

        <!-- TOP -->
        <div class="p-6 border-b">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- LEFT -->
                <div class="space-y-4">

                    <div>
                        <label class="text-sm text-gray-500">
                            Kode Pesanan
                        </label>

                        <div class="mt-1 border rounded-xl px-4 py-3 bg-gray-50 font-semibold">
                            {{ $order->kode }}
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Nama Customer
                        </label>

                        <div class="mt-1 border rounded-xl px-4 py-3 bg-gray-50">
                            {{ $order->nama_customer }}
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Nomor Handphone
                        </label>

                        <div class="mt-1 border rounded-xl px-4 py-3 bg-gray-50">
                            {{ $order->no_hp }}
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Alamat
                        </label>

                        <div class="mt-1 border rounded-xl px-4 py-3 bg-gray-50">
                            {{ $order->alamat }}
                        </div>
                    </div>

                </div>

                <!-- RIGHT -->
                <div class="space-y-4">

                    <div>
                        <label class="text-sm text-gray-500">
                            Tanggal Pemesanan
                        </label>

                        <div class="mt-1 border rounded-xl px-4 py-3 bg-gray-50">

                            {{ \Carbon\Carbon::parse($order->tanggal_pesan)
                                ->timezone('Asia/Makassar')
                                ->format('d/m/Y') }}

                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Tanggal Pengiriman
                        </label>

                        <div class="mt-1 border rounded-xl px-4 py-3 bg-gray-50">
                            @if($order->tanggal_kirim)

                                {{ \Carbon\Carbon::parse($order->tanggal_kirim)
                                    ->format('d/m/Y') }}

                            @else

                                -

                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Metode Pembayaran
                        </label>

                        <div class="mt-1 border rounded-xl px-4 py-3 bg-gray-50 font-medium">
                            {{ $order->metode_pembayaran }}
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Status Pembayaran
                        </label>

                        <div class="mt-1 border rounded-xl px-4 py-3 bg-gray-50">

                            @php
                                $paymentStatus = $order->payment->status ?? 'pending';
                            @endphp

                            @if($paymentStatus == 'paid')

                                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium">
                                    Pembayaran Berhasil
                                </span>

                            @elseif($paymentStatus == 'pending')

                                <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-medium">
                                    Menunggu Pembayaran
                                </span>

                            @elseif($paymentStatus == 'expired')

                                <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium">
                                    Pembayaran Kadaluarsa
                                </span>

                            @elseif($paymentStatus == 'failed')

                                <span class="bg-red-200 text-red-800 px-4 py-2 rounded-full text-sm font-medium">
                                    Pembayaran Gagal
                                </span>

                            @else

                                <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium">
                                    Pembayaran Gagal
                                </span>

                            @endif

                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- TABLE -->
        <div class="p-6">

            <div class="rounded-2xl border overflow-hidden mb-6">

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead class="bg-red-300 text-gray-800">

                            <tr>

                                <th class="p-4 border text-center">
                                    Produk
                                </th>

                                <th class="p-4 border text-center">
                                    Qty
                                </th>

                                <th class="p-4 border text-center">
                                    Harga
                                </th>

                                <th class="p-4 border text-center">
                                    Subtotal
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @php

                                $total = 0;

                                $ongkir = $order->ongkir ?? 0;

                                $admin = 1000;

                            @endphp

                            @forelse($order->details as $item)

                                @php

                                    $subtotal = $item->qty * $item->harga;

                                    $total += $subtotal;

                                @endphp

                                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition">

                                    <td class="p-4 border text-center font-medium">

                                        {{ $item->product->nama_produk ?? 'Produk dihapus' }}

                                    </td>

                                    <td class="p-4 border text-center">
                                        {{ $item->qty }}
                                    </td>

                                    <td class="p-4 border text-center">

                                        Rp {{ number_format($item->harga,0,',','.') }}

                                    </td>

                                    <td class="p-4 border text-center font-semibold">

                                        Rp {{ number_format($subtotal,0,',','.') }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4"
                                        class="text-center p-10 text-gray-400">

                                        Tidak ada produk dalam pesanan

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- SUMMARY -->
            <div class="bg-gray-50 rounded-2xl p-6 mb-6 border">

                <h3 class="font-semibold text-gray-700 mb-4">
                    Ringkasan Pembayaran
                </h3>

                <div class="space-y-3">

                    <div class="flex justify-between text-sm">

                        <span class="text-gray-500">
                            Total Produk
                        </span>

                        <span class="font-medium">

                            Rp {{ number_format($total,0,',','.') }}

                        </span>

                    </div>

                    <div class="flex justify-between text-sm">

                        <span class="text-gray-500">
                            Ongkos Kirim
                        </span>

                        <span class="font-medium">

                            Rp {{ number_format($ongkir,0,',','.') }}

                        </span>

                    </div>

                    <div class="flex justify-between text-sm">

                        <span class="text-gray-500">
                            Biaya Admin
                        </span>

                        <span class="font-medium">

                            Rp {{ number_format($admin,0,',','.') }}

                        </span>

                    </div>

                    <div class="border-t pt-4 flex justify-between items-center">

                        <span class="font-semibold text-gray-700">
                            Total Pembayaran
                        </span>

                        @php

                            $grandTotal = $total + $ongkir + $admin;

                        @endphp

                        <span class="text-2xl font-bold text-red-500">

                            Rp {{ number_format($grandTotal,0,',','.') }}

                        </span>

                    </div>

                </div>

            </div>

            <!-- UPDATE STATUS -->
            <form method="POST"
                  action="{{ route('orders.update', $order->id) }}">

                @csrf
                @method('PUT')

                <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">

                    <!-- STATUS -->
                    <div>

                        <label class="text-sm text-gray-500">
                            Status Pesanan
                        </label>

                        <div class="mt-2">

                            @if($order->status == 'selesai')

                                <div class="bg-green-100 text-green-700
                                            px-4 py-3 rounded-xl w-52 text-center font-semibold">

                                    Pesanan Selesai

                                </div>

                            @elseif($order->status == 'dibatalkan')

                                <div class="bg-red-100 text-red-700
                                            px-4 py-3 rounded-xl w-52 text-center font-semibold">

                                    Pesanan Dibatalkan

                                </div>

                            @else

                                <select name="status"

                                    class="border px-4 py-3 rounded-xl w-56 bg-white
                                        focus:outline-none focus:ring-2 focus:ring-red-300">
                                    
                                    @if($order->metode_pengambilan == 'pickup')

                                        <div class="hidden" id="pickup-order"></div>

                                    @elseif($order->metode_pengambilan == 'delivery')

                                        <div class="hidden" id="delivery-order"></div>

                                    @endif

                                    <option value="pending"
                                        {{ $order->status == 'pending' ? 'selected' : '' }}>

                                        Pending

                                    </option>

                                    <option value="diproses"
                                        {{ $order->status == 'diproses' ? 'selected' : '' }}>

                                        Diproses

                                    </option>

                                    <option value="dikemas"
                                        {{ $order->status == 'dikemas' ? 'selected' : '' }}>

                                        Dikemas

                                    </option>

                                    <option value="dikirim"
                                        {{ $order->status == 'dikirim' ? 'selected' : '' }}>

                                        Dikirim

                                    </option>

                                    <option value="ambil"
                                        {{ $order->status == 'ambil' ? 'selected' : '' }}>

                                        Ambil

                                    </option>

                                    <option value="selesai"
                                        {{ $order->status == 'selesai' ? 'selected' : '' }}>

                                        Selesai

                                    </option>

                                    <option value="dibatalkan"
                                        {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>

                                        Dibatalkan

                                    </option>

                                </select>

                            @endif

                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="flex gap-3">

                        <a href="{{ route('orders.index') }}"
                           class="px-5 py-3 border rounded-xl hover:bg-gray-100 transition">

                            Kembali

                        </a>

                        @if($order->status != 'selesai' && $order->status != 'dibatalkan')

                        <button
                            class="bg-red-400 hover:bg-red-500
                                   text-white px-5 py-3 rounded-xl transition">

                            Simpan Perubahan

                        </button>

                        @endif

                    </div>

                </div>

            <script>

            document.addEventListener('DOMContentLoaded', function () {

                const statusSelect =
                    document.querySelector('select[name="status"]');

                if(!statusSelect) return;

                const metodePengambilan =
                    @json($order->metode_pengambilan);

                const metodePembayaran =
                    @json($order->metode_pembayaran);

                /*
                |--------------------------------------------------------------------------
                | QRIS
                |--------------------------------------------------------------------------
                */

                if(metodePembayaran === 'QRIS')
                {
                    /*
                    QRIS boleh:
                    pending
                    diproses
                    dikemas
                    dikirim / ambil
                    selesai
                    dibatalkan
                    */
                }

                /*
                |--------------------------------------------------------------------------
                | PICKUP
                |--------------------------------------------------------------------------
                */

                if(metodePengambilan === 'pickup')
                {
                    const kirimOption =
                        statusSelect.querySelector(
                            'option[value="dikirim"]'
                        );

                    if(kirimOption)
                    {
                        kirimOption.remove();
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | DELIVERY
                |--------------------------------------------------------------------------
                */

                if(metodePengambilan === 'delivery')
                {
                    const ambilOption =
                        statusSelect.querySelector(
                            'option[value="ambil"]'
                        );

                    if(ambilOption)
                    {
                        ambilOption.remove();
                    }
                }

            });

            </script>

            </form>

        </div>

    </div>

</div>
@endsection