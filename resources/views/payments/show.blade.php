@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Detail Pembayaran</h1>

    <div class="bg-white p-6 rounded-xl shadow">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <div>
                <label class="text-sm text-gray-500">ID Pembayaran</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ $payment->kode_pembayaran }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Kode Pesanan</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ $payment->order->kode }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Metode Bayar</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ $payment->metode }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Tanggal Bayar</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ \Carbon\Carbon::parse($payment->tanggal_bayar)->timezone('Asia/Makassar')->format('d M Y H:i') }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Payment Ref</label>
                <div class="border p-2 rounded bg-gray-50">
                    {{ $payment->payment_ref ?? '-' }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Jumlah Bayar</label>
                <div class="border p-2 rounded bg-gray-50">
                    Rp {{ number_format($payment->jumlah) }}
                </div>
            </div>

        </div>

        <form method="POST" action="{{ route('payments.update', $payment->id) }}">
            @csrf
            @method('PUT')

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                <div class="w-full md:w-auto">
                    <label class="text-sm text-gray-500">Status Pembayaran</label>

                    @if($payment->status == 'lunas')
                        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mt-1 w-fit font-medium">
                            Lunas
                        </div>
                    @else
                        <select name="status"
                            class="mt-1 w-full md:w-56 border px-3 py-2 rounded focus:ring-2 focus:ring-red-300">
                            <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="lunas">
                                Lunas
                            </option>
                        </select>
                    @endif
                </div>

                <div class="flex gap-3 w-full md:w-auto justify-end">
                    <a href="{{ route('payments.index') }}"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                        Kembali
                    </a>

                    @if($payment->status != 'lunas')
                    <button class="bg-red-400 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                        Simpan
                    </button>
                    @endif
                </div>

            </div>

        </form>

    </div>

</div>
@endsection