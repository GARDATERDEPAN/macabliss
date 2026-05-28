@extends('layouts.app')

@section('content')
<div class="p-6 max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Detail Pembayaran
            </h1>

        </div>

    </div>

    <!-- MAIN CARD -->
    <div class="bg-white rounded-2xl shadow border overflow-hidden">

        <!-- TOP -->
        <div class="p-6 border-b">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- ID PAYMENT -->
                <div>

                    <label class="text-sm text-gray-500">
                        ID Pembayaran
                    </label>

                    <div class="mt-2 border rounded-2xl px-5 py-4 bg-gray-50 font-semibold">

                        {{ $payment->kode_pembayaran }}

                    </div>

                </div>

                <!-- KODE ORDER -->
                <div>

                    <label class="text-sm text-gray-500">
                        Kode Pesanan
                    </label>

                    <div class="mt-2 border rounded-2xl px-5 py-4 bg-gray-50">

                        {{ $payment->order->kode ?? '-' }}

                    </div>

                </div>

                <!-- CUSTOMER -->
                <div>

                    <label class="text-sm text-gray-500">
                        Nama Customer
                    </label>

                    <div class="mt-2 border rounded-2xl px-5 py-4 bg-gray-50">

                        {{ $payment->order->nama_customer ?? '-' }}

                    </div>

                </div>

                <!-- METODE -->
                <div>

                    <label class="text-sm text-gray-500">
                        Metode Pembayaran
                    </label>

                    <div class="mt-2 border rounded-2xl px-5 py-4 bg-gray-50">

                        @if($payment->metode == 'QRIS')

                            <span class="bg-blue-100 text-blue-700
                                         px-4 py-2 rounded-full text-sm font-medium">

                                QRIS

                            </span>

                        @elseif($payment->metode == 'COD')

                            <span class="bg-gray-100 text-gray-700
                                         px-4 py-2 rounded-full text-sm font-medium">

                                COD

                            </span>

                        @else

                            <span class="bg-gray-100 text-gray-700
                                         px-4 py-2 rounded-full text-sm font-medium">

                                {{ $payment->metode }}

                            </span>

                        @endif

                    </div>

                </div>

                <!-- TANGGAL -->
                <div>

                    <label class="text-sm text-gray-500">
                        Tanggal Pemesanan
                    </label>

                    <div class="mt-2 border rounded-2xl px-5 py-4 bg-gray-50">

                        {{ \Carbon\Carbon::parse($payment->tanggal_bayar)
                            ->timezone('Asia/Makassar')
                            ->format('d/m/Y') }}

                    </div>

                </div>

                <!-- PAYMENT REF -->
                <div>

                    <label class="text-sm text-gray-500">
                        Payment Reference
                    </label>

                    <div class="mt-2 border rounded-2xl px-5 py-4 bg-gray-50 break-all text-sm">

                        {{ $payment->payment_ref ?? '-' }}

                    </div>

                </div>

                <!-- JUMLAH -->
                <div>

                    <label class="text-sm text-gray-500">
                        Jumlah Pembayaran
                    </label>

                    <div class="mt-2 border rounded-2xl px-5 py-4 bg-red-50 text-md font-bold text-red-500">

                        Rp {{ number_format($payment->jumlah,0,',','.') }}

                    </div>

                </div>

                <!-- STATUS -->
                <div>

                    <label class="text-sm text-gray-500">
                        Status Pembayaran
                    </label>

                    <div class="mt-2 border rounded-2xl px-5 py-4 bg-gray-50">

                        @if($payment->status == 'paid')

                            <span class="bg-green-100 text-green-700
                                         px-4 py-2 rounded-full text-sm font-medium">

                                Pembayaran Berhasil

                            </span>

                        @elseif($payment->status == 'pending')

                            <span class="bg-yellow-100 text-yellow-700
                                         px-4 py-2 rounded-full text-sm font-medium">

                                Menunggu Pembayaran

                            </span>

                        @elseif($payment->status == 'expired')

                            <span class="bg-gray-100 text-gray-700
                                         px-4 py-2 rounded-full text-sm font-medium">

                                Pembayaran Expired

                            </span>

                        @elseif($payment->status == 'failed')

                            <span class="bg-red-100 text-red-700
                                         px-4 py-2 rounded-full text-sm font-medium">

                                Pembayaran Gagal

                            </span>

                        @else

                            <span class="bg-red-100 text-red-700
                                         px-4 py-2 rounded-full text-sm font-medium">

                                Pembayaran Dibatalkan

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        <!-- UPDATE FORM -->
        <div class="p-6">

            <form method="POST"
                  action="{{ route('payments.update', $payment->id) }}">

                @csrf
                @method('PUT')

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

                    <!-- STATUS UPDATE -->
                    <div>

                        <label class="text-sm text-gray-500">
                            Update Status Pembayaran
                        </label>

                        <div class="mt-2">

                            @if(in_array($payment->status, ['paid', 'expired', 'failed', 'cancelled']))

                                @if($payment->status == 'paid')

                                    <div class="bg-green-100 text-green-700
                                                px-4 py-3 rounded-xl w-56 text-center font-semibold">

                                        Pembayaran Berhasil

                                    </div>

                                @elseif($payment->status == 'expired')

                                    <div class="bg-gray-100 text-gray-700
                                                px-4 py-3 rounded-xl w-56 text-center font-semibold">

                                        Pembayaran Expired

                                    </div>

                                @elseif($payment->status == 'failed')

                                    <div class="bg-red-100 text-red-700
                                                px-4 py-3 rounded-xl w-56 text-center font-semibold">

                                        Pembayaran Gagal

                                    </div>

                                @else

                                    <div class="bg-red-100 text-red-700
                                                px-4 py-3 rounded-xl w-56 text-center font-semibold">

                                        Pembayaran Dibatalkan

                                    </div>

                                @endif

                            @else

                                <select name="status"

                                    class="border px-4 py-3 rounded-xl w-56 bg-white
                                           focus:outline-none focus:ring-2 focus:ring-red-300">

                                    <option value="pending"
                                        {{ $payment->status == 'pending' ? 'selected' : '' }}>

                                        Pending

                                    </option>

                                    <option value="paid">

                                        Paid

                                    </option>

                                    <option value="expired">

                                        Expired

                                    </option>

                                    <option value="failed">

                                        Failed

                                    </option>

                                    <option value="cancelled">

                                        Cancelled

                                    </option>

                                </select>

                            @endif

                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="flex gap-3">

                        <a href="{{ route('payments.index') }}"
                           class="px-5 py-3 border rounded-xl hover:bg-gray-100 transition">

                            Kembali

                        </a>

                        @if(!in_array($payment->status, ['paid', 'expired', 'failed', 'cancelled']))

                        <button
                            class="bg-red-400 hover:bg-red-500
                                   text-white px-5 py-3 rounded-xl transition">

                            Simpan Perubahan

                        </button>

                        @endif

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection