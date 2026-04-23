@extends('layouts.app')

@section('content')
<div class="p-6">

    <!-- HEADER + FILTER -->
    <div class="flex justify-between items-center mb-6">

        <!-- JUDUL -->
        <h1 class="text-2xl font-bold">Daftar Pesanan</h1>

        <!-- FILTER -->
        <form id="filterForm" method="GET" action="{{ route('orders.index') }}" class="flex gap-2 items-center">

            <!-- SEARCH -->
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kode / nama..."
                class="border px-3 py-2 rounded w-56 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-red-300"
                onkeyup="submitWithDelay()">

            <!-- STATUS -->
            <div class="relative">
                <select name="status"
                    class="border px-3 py-2 pr-10 rounded bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-red-300"
                    onchange="document.getElementById('filterForm').submit()">

                    <option value="">Semua Status</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

            </div>

            <!-- TANGGAL -->
            <input type="date" name="from" value="{{ request('from') }}"
                class="border px-3 py-2 rounded bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-red-300"
                onchange="document.getElementById('filterForm').submit()">

            <input type="date" name="to" value="{{ request('to') }}"
                class="border px-3 py-2 rounded bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-red-300"
                onchange="document.getElementById('filterForm').submit()">

        </form>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-left border-collapse">

            <thead class="bg-red-300">
                <tr>
                    <th class="p-4 border text-center">Kode</th>
                    <th class="p-4 border text-center">Nama</th>
                    <th class="p-4 border text-center">Tanggal Pemesanan</th>
                    <th class="p-4 border text-center">Tanggal Selesai</th>
                    <th class="p-4 border text-center">Status</th>
                    <th class="p-4 border text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">

                    <td class="p-4 border text-center">{{ $order->kode }}</td>

                    <td class="p-4 border text-center">
                        {{ $order->nama_customer }}
                    </td>

                    <td class="p-4 border text-center">
                        {{ $order->tanggal_pesan }}
                    </td>

                    <td class="p-4 border text-center">
                        {{ $order->tanggal_kirim ?? '-' }}
                    </td>

                    <td class="p-4 border text-center">
                        @if($order->status == 'diproses')
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                                Diproses
                            </span>
                        @else
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                                Selesai
                            </span>
                        @endif
                    </td>

                    <td class="p-4 border text-center">
                        <a href="{{ route('orders.show', $order->id) }}"
                        class="px-3 py-1 bg-red-400 text-white rounded-lg text-sm hover:bg-red-600 transition">
                            Detail
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-4">Tidak ada data</td>
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