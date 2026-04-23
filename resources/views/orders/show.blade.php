@extends('layouts.app')

@section('content')
<div class="p-6 max-w-5xl mx-auto">

    <!-- HEADER -->
    <h1 class="text-2xl font-bold mb-6">
        Detail Pesanan
    </h1>

    <!-- CARD -->
    <div class="bg-white rounded-xl shadow p-6">

        <!-- INFO -->
        <div class="grid grid-cols-2 gap-4 mb-6">

            <div>
                <label class="text-sm text-gray-500">Kode Pesanan</label>
                <div class="border p-2 rounded bg-gray-50 font-semibold">
                    {{ $order->kode }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Nama</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ $order->nama_customer }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">No. Handphone</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ $order->no_hp }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Tanggal Pengiriman</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ $order->tanggal_kirim ?? '-' }}
                </div>
            </div>

            <div class="col-span-2">
                <label class="text-sm text-gray-500">Alamat</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ $order->alamat }}
                </div>
            </div>

        </div>

        <!-- TABLE PRODUK -->
        <div class="rounded-xl border overflow-hidden mb-6">

            <table class="w-full text-left border-collapse">

                <thead class="bg-red-400 text-white">
                    <tr>
                        <th class="p-3 text-center">Produk</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-center">Harga</th>
                        <th class="p-3 text-center">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @php 
                        $total = 0; // total produk
                        $ongkir = $order->ongkir ?? 0;
                        $admin = 1000;
                    @endphp

                    @forelse($order->details as $item)

                        @php
                            $subtotal = $item->qty * $item->harga;
                            $total += $subtotal;
                        @endphp

                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-3 text-center font-medium">
                                {{ $item->product->nama_produk ?? 'Produk dihapus' }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $item->qty }}
                            </td>

                            <td class="p-3 text-center">
                                Rp {{ number_format($item->harga,0,',','.') }}
                            </td>

                            <td class="p-3 text-center font-semibold">
                                Rp {{ number_format($subtotal,0,',','.') }}
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-6 text-gray-400">
                                Tidak ada produk dalam pesanan
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

            {{-- <div class="mt-4 border-t pt-4"> --}}

                <div class="bg-gray-50 rounded-lg p-4 space-y-3">

                    <h3 class="font-semibold text-gray-700 mb-2">
                        Ringkasan Pembayaran
                    </h3>

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Produk</span>
                        <span class="font-medium">
                            Rp {{ number_format($total,0,',','.') }}
                        </span>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Biaya Pengiriman</span>
                        <span class="font-medium">
                            Rp {{ number_format($ongkir,0,',','.') }}
                        </span>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Biaya Admin</span>
                        <span class="font-medium">
                            Rp {{ number_format($admin,0,',','.') }}
                        </span>
                    </div>

                    {{-- <div class="border-t pt-3 flex justify-between items-center">
                        <span class="font-semibold text-gray-700">Total Pembayaran</span>

                        @php
                            $grandTotal = $total + $ongkir + $admin;
                        @endphp

                        <span class="text-lg font-bold text-red-500">
                            Rp {{ number_format($grandTotal,0,',','.') }}
                        </span>
                    </div> --}}

                </div>

            {{-- </div> --}}

        </div>

        <!-- STATUS + TOTAL -->
        <form method="POST" action="{{ route('orders.update', $order->id) }}">
            @csrf
            @method('PUT')

            <div class="flex justify-between items-end mb-6">

                <!-- STATUS -->
                <div>
                    <label class="text-sm text-gray-500">Status Pesanan</label>

                    @if($order->status == 'selesai')
                        <div class="px-3 py-2 bg-green-100 text-green-700 rounded w-40 text-center font-semibold">
                            Selesai
                        </div>
                    @else
                        <select name="status"
                            class="border px-3 py-2 pr-10 rounded w-40 bg-white focus:outline-none focus:ring-2 focus:ring-red-300">
                            <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>
                                Diproses
                            </option>
                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>
                        </select>
                    @endif
                </div>

                <!-- TOTAL -->
                <div>
                    <label class="text-sm text-gray-500">Total Pembayaran</label>
                    <div class="bg-gray-100 px-4 py-2 rounded font-bold w-40 text-center text-lg">
                        @php
                            $grandTotal = $total + $ongkir + $admin;
                        @endphp

                        Rp {{ number_format($grandTotal,0,',','.') }}
                    </div>
                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('orders.index') }}"
                   class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                    Kembali
                </a>

                @if($order->status != 'selesai')
                <button class="bg-red-400 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    Simpan
                </button>
                @endif

            </div>

        </form>

    </div>

</div>
@endsection