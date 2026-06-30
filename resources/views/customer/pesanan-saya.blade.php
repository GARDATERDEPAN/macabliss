@extends('layouts.customer')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <h1 class="text-2xl font-semibold mb-6">
        Riwayat Pesanan
    </h1>

    <div class="space-y-4">

        @forelse($orders as $order)

        @php
            $paymentStatus = $order->payment->status ?? 'pending';
        @endphp

        <div class="bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">

            <!-- HEADER -->
            <div class="flex justify-between items-start mb-3">

                <div>

                    <p class="font-semibold text-gray-800">
                        {{ $order->kode }}
                    </p>

                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($order->tanggal_pesan)
                            ->timezone('Asia/Makassar')
                            ->format('d M Y') }}
                    </p>

                </div>

                <!-- STATUS ORDER -->
                <span class="text-xs px-3 py-1 rounded-full font-medium

                    @if($order->status=='pending')
                        bg-gray-100 text-gray-600

                    @elseif($order->status=='diproses')
                        bg-yellow-100 text-yellow-600

                    @elseif($order->status=='dikemas')
                        bg-blue-100 text-blue-600

                    @elseif($order->status=='dikirim')
                        bg-purple-100 text-purple-600

                    @elseif($order->status=='ambil')
                        bg-indigo-100 text-purple-600

                    @elseif($order->status=='selesai')
                        bg-green-100 text-green-600

                    @elseif($order->status=='dibatalkan')
                        bg-red-100 text-red-600

                    @endif

                ">

                    {{ ucfirst($order->status) }}

                </span>

            </div>

            <!-- DIVIDER -->
            <div class="border-t my-3"></div>

            <!-- TOTAL -->
            <div class="flex justify-between items-center text-sm mb-3">

                <div class="text-gray-500">
                    Total Pembayaran
                </div>

                <div class="font-semibold text-gray-800">
                    Rp {{ number_format($order->total_harga,0,',','.') }}
                </div>

            </div>

            <!-- METODE PEMBAYARAN -->
            <div class="flex justify-between items-center text-sm mb-3">

                <div class="text-gray-500">
                    Metode Pembayaran
                </div>

                <div class="font-medium text-gray-700">
                    {{ $order->metode_pembayaran }}
                </div>

            </div>

            <!-- STATUS PAYMENT -->
            <div class="flex justify-between items-center text-sm mb-4">

                <div class="text-gray-500">
                    Status Pembayaran
                </div>

                <div>

                    @if($paymentStatus == 'paid')

                        <span class="bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full font-medium">
                            Pembayaran Berhasil
                        </span>

                    @elseif($paymentStatus == 'pending')

                        <span class="bg-yellow-100 text-yellow-600 text-xs px-3 py-1 rounded-full font-medium">
                            Menunggu Pembayaran
                        </span>

                    @elseif($paymentStatus == 'expired')

                        <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-medium">
                            Pembayaran Kadaluarsa
                        </span>

                    @elseif($paymentStatus == 'failed')

                        <span class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full font-medium">
                            Pembayaran Gagal
                        </span>

                    @elseif($paymentStatus == 'cancelled')

                        <span class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full font-medium">
                            Pembayaran Dibatalkan
                        </span>

                    @endif

                </div>

            </div>

            @php

                $belumRating = false;

                if($order->status == 'selesai'){

                    foreach($order->details as $detail){

                        $sudahRating = \App\Models\ProductRating::where(
                            'user_id',
                            Auth::guard('customer')->id()
                        )
                        ->where('order_id', $order->id)
                        ->where('product_id', $detail->product_id)
                        ->exists();

                        if(!$sudahRating){

                            $belumRating = true;

                            break;

                        }

                    }

                }

            @endphp


            @if($belumRating)

            <div class="mb-4">

                <div class="bg-yellow-50 border border-yellow-300 rounded-2xl p-4">

                    <div class="flex items-start gap-3">

                        <div class="text-2xl">
                            ⭐
                        </div>

                        <div>

                            <h4 class="font-semibold text-yellow-700">

                                Berikan Rating & Ulasan

                            </h4>

                            <p class="text-sm text-yellow-600 mt-1">

                                Pesanan telah selesai.
                                Yuk berikan penilaian dan ulasan untuk produk
                                yang telah Anda beli.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

            @endif

            <!-- ACTION BUTTON -->
            <div class="flex flex-wrap gap-3">

                <!-- DETAIL -->
                <a href="{{ route('customer.detailPesanan', $order->id) }}"
                   class="bg-red-400 text-white rounded xl hover:bg-red-800
                          text-gray-700 px-4 py-2 rounded-xl text-sm transition">

                    Detail Pesanan
                </a>

                <!-- LANJUTKAN PEMBAYARAN -->
                @if(
                    $paymentStatus == 'pending'
                    && $order->metode_pembayaran == 'QRIS'
                    && $order->snap_token
                )

                    <button
                        onclick="continuePayment('{{ $order->snap_token }}')"
                        class="bg-red-400 hover:bg-red-500
                               text-white px-4 py-2 rounded-xl text-sm transition">

                        Lanjutkan Pembayaran

                    </button>

                @endif

                <!-- BAYAR LAGI -->
                @if(
                    ($paymentStatus == 'expired'
                    || $paymentStatus == 'failed')
                    && $order->metode_pembayaran == 'QRIS'
                )

                @endif

            </div>

        </div>

        @empty

        <!-- EMPTY -->
        <div class="text-center py-16">

            <i data-lucide="package-x"
               class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>

            <p class="text-gray-500 mb-2">
                Belum ada pesanan
            </p>

            <p class="text-xs text-gray-400 mb-4">
                Yuk mulai belanja dulu!
            </p>

            <a href="{{ route('customer.beranda') }}"
               class="inline-block bg-red-500 text-white
                      px-4 py-2 rounded-lg text-sm hover:bg-red-600">

                Belanja Sekarang

            </a>

        </div>

        @endforelse

    </div>

</div>

<!-- MIDTRANS -->
<script
src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>

<script>

function continuePayment(token){

    window.snap.pay(token);

}

function retryPayment(orderId){

    fetch('/retry-payment/' + orderId, {

        method: 'POST',

        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }

    })

    .then(async response => {

        const data = await response.json();

        console.log(data);

        if(data.snap_token){

            window.snap.pay(data.snap_token);

        } else {

            alert(data.message || 'Gagal membuat pembayaran ulang');

        }

    })

    .catch(error => {

        console.log(error);

        alert('Terjadi kesalahan');

    });

}

</script>

@endsection