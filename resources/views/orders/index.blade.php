@extends('layouts.app')

@section('content')
<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <!-- KIRI -->
        <div>
            <h1 class="text-2xl font-bold">
                Daftar Pesanan
            </h1>
        </div>

        <!-- FILTER -->
        <div class="flex items-center gap-3">

            <form id="filterForm"
                  method="GET"
                  action="{{ route('orders.index') }}"
                  class="flex gap-2 items-center">

                <!-- SEARCH -->
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode / nama..."

                       class="border px-3 py-2 rounded w-56 bg-white
                              appearance-none
                              focus:outline-none focus:ring-2 focus:ring-red-300"

                       onkeyup="submitWithDelay()">

                <!-- PAYMENT -->
                {{-- <select name="payment_status"

                    class="border px-3 py-2 pr-10 rounded bg-white
                           appearance-none
                           focus:outline-none focus:ring-2 focus:ring-red-300"

                    onchange="document.getElementById('filterForm').submit()">

                    <option value="">Semua Pembayaran</option>

                    <option value="paid"
                        {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                        Paid
                    </option>

                    <option value="pending"
                        {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="expired"
                        {{ request('payment_status') == 'expired' ? 'selected' : '' }}>
                        Expired
                    </option>

                    <option value="failed"
                        {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                        Failed
                    </option>

                </select> --}}

                <!-- ORDER -->
                <select name="status"

                    class="border px-3 py-2 pr-10 rounded bg-white
                           appearance-none
                           focus:outline-none focus:ring-2 focus:ring-red-300"

                    onchange="document.getElementById('filterForm').submit()">

                    <option value="">Semua Order</option>

                    <option value="diproses"
                        {{ request('status') == 'diproses' ? 'selected' : '' }}>
                        Diproses
                    </option>

                    <option value="dikemas"
                        {{ request('status') == 'dikemas' ? 'selected' : '' }}>
                        Dikemas
                    </option>

                    <option value="dikirim"
                        {{ request('status') == 'dikirim' ? 'selected' : '' }}>
                        Dikirim
                    </option>

                    <option value="dibatalkan"
                        {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>

                    <option value="selesai"
                        {{ request('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                </select>

                <!-- DATE FROM -->
                <input type="date"
                       name="from"
                       value="{{ request('from') }}"

                       class="border px-3 py-2 rounded bg-white
                              appearance-none
                              focus:outline-none focus:ring-2 focus:ring-red-300"

                       onchange="document.getElementById('filterForm').submit()">

                <!-- DATE TO -->
                <input type="date"
                       name="to"
                       value="{{ request('to') }}"

                       class="border px-3 py-2 rounded bg-white
                              appearance-none
                              focus:outline-none focus:ring-2 focus:ring-red-300"

                       onchange="document.getElementById('filterForm').submit()">

            </form>

        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-left border-collapse">

            <!-- HEAD -->
            <thead class="bg-red-300">
                <tr>

                    <th class="p-4 border text-center">
                        Kode Pesanan
                    </th>

                    <th class="p-4 border text-center">
                        Customer
                    </th>

                    <th class="p-4 border text-center">
                        Tanggal Pemesanan
                    </th>

                    <th class="p-4 border text-center">
                        Tanggal Selesai
                    </th>

                    <th class="p-4 border text-center">
                        Status Payment
                    </th>

                    <th class="p-4 border text-center">
                        Status Order
                    </th>

                    <th class="p-4 border text-center">
                        Aksi
                    </th>

                </tr>
            </thead>

            <!-- BODY -->
            <tbody>

                @forelse($orders as $order)

                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">

                    <!-- KODE -->
                    <td class="p-4 border text-center font-medium">
                        {{ $order->kode }}
                    </td>

                    <!-- NAMA -->
                    <td class="p-4 border text-center">
                        {{ $order->nama_customer }}
                    </td>

                    <!-- TANGGAL PESAN -->
                    <td class="p-4 border text-center">
                        {{ \Carbon\Carbon::parse($order->tanggal_pesan)
                            ->timezone('Asia/Makassar')
                            ->format('d-m-Y') }}
                    </td>

                    <!-- TANGGAL SELESAI -->
                    <td class="p-4 border text-center">

                        @if($order->tanggal_kirim)

                            {{ \Carbon\Carbon::parse($order->tanggal_kirim)
                                ->format('d-m-Y') }}

                        @else

                            -

                        @endif

                    </td>

                    <!-- STATUS PAYMENT -->
                    <td class="p-4 border text-center">

                        @php
                            $paymentStatus = $order->payment->status ?? 'pending';
                        @endphp

                        @if($paymentStatus == 'pending')

                            <span class="bg-yellow-100 text-yellow-700 px-4 py-1 rounded-full text-sm">
                                Pending
                            </span>

                        @elseif($paymentStatus == 'paid')

                            <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm">
                                Paid
                            </span>

                        @elseif($paymentStatus == 'expired')

                            <span class="bg-gray-100 text-gray-700 px-4 py-1 rounded-full text-sm">
                                Expired
                            </span>

                        @elseif($paymentStatus == 'failed')

                            <span class="bg-red-100 text-red-700 px-4 py-1 rounded-full text-sm">
                                Failed
                            </span>

                        @elseif($paymentStatus == 'cancelled')

                            <span class="bg-pink-100 text-pink-700 px-4 py-1 rounded-full text-sm">
                                Cancelled
                            </span>

                        @endif

                    </td>

                    <!-- STATUS ORDER -->
                    <td class="p-4 border text-center">

                        @if($order->status == 'diproses')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                Diproses
                            </span>

                        @elseif($order->status == 'dikemas')

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                Dikemas
                            </span>

                        @elseif($order->status == 'dikirim')

                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                Dikirim
                            </span>

                        @elseif($order->status == 'dibatalkan')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Dibatalkan
                            </span>

                        @else

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Selesai
                            </span>

                        @endif

                    </td>

                    <!-- AKSI -->
                    <td class="p-4 border text-center">

                        <a href="{{ route('orders.show', $order->id) }}"
                           class="px-4 py-2 bg-red-400 text-white rounded-lg text-sm hover:bg-red-600 transition">

                            Detail

                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center text-gray-500 p-4">
                        Tidak ada data pemesanan
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="mt-4 flex justify-end">
        {{ $orders->links('pagination::tailwind') }}
    </div>

</div>

<script>

let timeout = null;

function submitWithDelay() {

    clearTimeout(timeout);

    timeout = setTimeout(() => {

        document.getElementById('filterForm').submit();

    }, 500);

}

</script>

@endsection