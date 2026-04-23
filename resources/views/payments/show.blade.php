@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Detail Pembayaran</h1>

    <div class="bg-white p-6 rounded-xl shadow">

        <div class="grid grid-cols-2 gap-4 mb-6">

            <div>
                <label class="text-sm text-gray-500">ID Pembayaran</label>
                <div class="border p-2 rounded bg-gray-50">{{ $payment->kode_pembayaran }}</div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Kode Pesanan</label>
                <div class="border p-2 rounded bg-gray-50">{{ $payment->order->kode }}</div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Metode Bayar</label>
                <div class="border p-2 rounded bg-gray-50">{{ $payment->metode }}</div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Tanggal Bayar</label>
                <div class="border p-2 rounded bg-gray-50">{{ $payment->tanggal_bayar }}</div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Payment Ref</label>
                <div class="border p-2 rounded bg-gray-50">{{ $payment->payment_ref }}</div>
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

            <div class="flex justify-between items-center">

                <div>
                    <label class="text-sm text-gray-500">Status Pembayaran</label>

                    @if($payment->status == 'lunas')
                        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mt-1">
                            Lunas
                        </div>
                    @else
                        <select name="status"
                            class="border px-3 py-2 rounded mt-1">
                            <option value="pending">Pending</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    @endif
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('payments.index') }}"
                        class="px-4 py-2 border rounded-lg">
                        Kembali
                    </a>

                    @if($payment->status != 'lunas')
                    <button class="bg-red-400 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        Simpan
                    </button>
                    @endif
                </div>

            </div>

        </form>

    </div>

</div>
@endsection