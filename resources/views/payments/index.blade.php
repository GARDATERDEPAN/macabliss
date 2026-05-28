@extends('layouts.app')

@section('content')
<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <!-- KIRI -->
        <div>

            <h1 class="text-2xl font-bold">
                Daftar Pembayaran
            </h1>

        </div>

        <!-- FILTER -->
        <div class="flex items-center gap-3">

            <form method="GET"
                  class="flex gap-2 items-center">

                <!-- SEARCH -->
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode pembayaran / order..."

                       class="border px-3 py-2 rounded w-64 bg-white
                              appearance-none
                              focus:outline-none focus:ring-2 focus:ring-red-300">

                <!-- STATUS -->
                <select name="status"

                    class="border px-3 py-2 pr-10 rounded bg-white
                           appearance-none
                           focus:outline-none focus:ring-2 focus:ring-red-300"

                    onchange="this.form.submit()">

                    <option value="">
                        Semua Status
                    </option>

                    <option value="pending"
                        {{ request('status') == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="paid"
                        {{ request('status') == 'paid' ? 'selected' : '' }}>
                        Paid
                    </option>

                    <option value="expired"
                        {{ request('status') == 'expired' ? 'selected' : '' }}>
                        Expired
                    </option>

                    <option value="failed"
                        {{ request('status') == 'failed' ? 'selected' : '' }}>
                        Failed
                    </option>

                    <option value="cancelled"
                        {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>

                </select>

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
                        ID Pembayaran
                    </th>

                    <th class="p-4 border text-center">
                        Kode Pesanan
                    </th>

                    <th class="p-4 border text-center">
                        Customer
                    </th>

                    <th class="p-4 border text-center">
                        Metode
                    </th>

                    <th class="p-4 border text-center">
                        Jumlah
                    </th>

                    <th class="p-4 border text-center">
                        Status
                    </th>

                    <th class="p-4 border text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <!-- BODY -->
            <tbody>

                @forelse($payments as $p)

                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">

                    <!-- ID -->
                    <td class="p-4 border text-center font-medium">
                        {{ $p->kode_pembayaran }}
                    </td>

                    <!-- ORDER -->
                    <td class="p-4 border text-center">
                        {{ $p->order->kode ?? '-' }}
                    </td>

                    <!-- CUSTOMER -->
                    <td class="p-4 border text-center">
                        {{ $p->order->nama_customer ?? '-' }}
                    </td>

                    <!-- METODE -->
                    <td class="p-4 border text-center">

                        @if($p->metode == 'QRIS')

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                QRIS
                            </span>

                        @elseif($p->metode == 'COD')

                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                COD
                            </span>

                        @else

                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $p->metode }}
                            </span>

                        @endif

                    </td>

                    <!-- JUMLAH -->
                    <td class="p-4 border text-center">

                        Rp {{ number_format($p->jumlah,0,',','.') }}

                    </td>

                    <!-- STATUS -->
                    <td class="p-4 border text-center">

                        @if($p->status == 'paid')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                Paid
                            </span>

                        @elseif($p->status == 'pending')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                                Pending
                            </span>

                        @elseif($p->status == 'expired')

                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                                Expired
                            </span>

                        @elseif($p->status == 'failed')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                Failed
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                Cancelled
                            </span>

                        @endif

                    </td>

                    <!-- AKSI -->
                    <td class="p-4 border text-center">

                        <a href="{{ route('payments.show', $p->id) }}"
                           class="px-4 py-2 bg-red-400 text-white rounded-lg text-sm hover:bg-red-600 transition">

                            Detail

                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7"
                        class="text-center p-5 text-gray-500">

                        Tidak ada data pembayaran

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="mt-4 flex justify-end">

        {{ $payments->links('pagination::tailwind') }}

    </div>

</div>
@endsection