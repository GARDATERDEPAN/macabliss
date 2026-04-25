@extends('layouts.app')

@section('content')
<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Pembayaran</h1>

        <!-- FILTER -->
        <form method="GET" class="flex gap-2">

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kode..."
                class="border px-3 py-2 rounded w-56 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-red-300">

            <select name="status"
                class="border px-3 py-2 pr-10 rounded bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-red-300"
                onchange="this.form.submit()">

                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="lunas">Lunas</option>
            </select>

        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full border-collapse">

            <thead class="bg-red-300">
                <tr>
                    <th class="p-4 border text-center">ID Bayar</th>
                    <th class="p-4 border text-center">Kode</th>
                    <th class="p-4 border text-center">Metode Pembayaran</th>
                    <th class="p-4 border text-center">Jumlah</th>
                    <th class="p-4 border text-center">Status</th>
                    <th class="p-4 border text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $p)
                <tr class="odd:bg-white even:bg-gray-50">

                    <td class="p-4 border text-center">{{ $p->kode_pembayaran }}</td>
                    <td class="p-4 border text-center">{{ $p->order->kode }}</td>
                    <td class="p-4 border text-center">{{ $p->metode }}</td>

                    <td class="p-4 border text-center">
                        Rp {{ number_format($p->jumlah) }}
                    </td>

                    <td class="p-4 border text-center">
                        @if($p->status == 'lunas')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                                Lunas
                            </span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                                Pending
                            </span>
                        @endif
                    </td>

                    <td class="p-4 border text-center">
                        <a href="{{ route('payments.show', $p->id) }}"
                        class="px-3 py-1 bg-red-400 text-white rounded-lg text-sm hover:bg-red-600">
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

    <div class="mt-4 flex justify-end">
        {{ $payments->links('pagination::tailwind') }}
    </div>

</div>
@endsection