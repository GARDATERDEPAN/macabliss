@extends('layouts.app')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">

    <!-- KIRI -->
    <div>
        <h1 class="text-2xl font-bold">Daftar Produk</h1>
    </div>

    <!-- KANAN (FILTER + BUTTON) -->
    <div class="flex items-center gap-3">

        <!-- FILTER -->
        <form id="filterForm" method="GET" action="{{ route('products.index') }}" class="flex gap-2">

            <!-- SEARCH -->
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama produk..."
                class="border px-3 py-2 rounded w-56 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-red-300"
                onkeyup="submitWithDelay()">

            <!-- STATUS -->
            <select name="status" class="border px-3 py-2 pr-10 rounded bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-red-300"
                onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

        </form>

        <!-- BUTTON -->
        <a href="{{ route('products.create') }}" 
           class="bg-red-400 text-white px-6 py-2 rounded-lg hover:bg-red-800">
            Tambah Produk
        </a>

    </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-left border-collapse">

            <!-- HEAD -->
            <thead class="bg-red-300">
                <tr>
                    <th class="p-4 border text-center">Gambar</th>
                    <th class="p-4 border text-center">Nama Produk</th>
                    <th class="p-4 border text-center">Harga</th>
                    <th class="p-4 border text-center">Status</th>
                    <th class="p-4 border text-center">Aksi</th>
                </tr>
            </thead>
            
            <!-- BODY -->
            <tbody>

                @forelse($products as $product)
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">

                    <!-- GAMBAR -->
                    <td class="p-4 border text-center">
                       @if($product->gambar && file_exists(public_path('storage/' . $product->gambar)))
                            <img src="{{ asset('storage/' . $product->gambar) }}" 
                                class="w-16 h-16 object-cover rounded mx-auto">
                        @else
                            <div class="w-16 h-16 bg-gray-200 flex items-center justify-center text-xs text-gray-500 rounded mx-auto">
                                No Image
                            </div>
                        @endif
                    </td>

                    <!-- NAMA -->
                    <td class="p-4 border font-medium">
                        {{ $product->nama_produk }}
                    </td>

                    <!-- HARGA -->
                    <td class="p-4 border text-center">
                        Rp {{ number_format($product->harga) }}
                    </td>

                    <!-- STATUS DINAMIS -->
                    <td class="p-4 border text-center">
                         @if($product->status == 'active')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded">Active</span>
                         @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded">Inactive</span>
                         @endif
                    </td>

                    <!-- AKSI ICON -->
                    <td class="p-4 border text-center">
                        <div class="flex justify-center gap-3">

                            <!-- EDIT -->
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="w-5 h-5" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 5h2M12 3v2m6.364 1.636l-9.9 9.9a2 2 0 01-.878.516l-4 1a1 1 0 01-1.213-1.213l1-4a2 2 0 01.516-.878l9.9-9.9a2 2 0 012.828 0l1.747 1.747a2 2 0 010 2.828z"/>
                                </svg>
                            </a>

                            <!-- DELETE (MODAL TRIGGER) -->
                            <button onclick="openModal('{{ route('products.destroy', $product->id) }}')"
                                class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="w-5 h-5" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 3h6a1 1 0 011 1v2H8V4a1 1 0 011-1z"/>
                                </svg>
                            </button>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4">Tidak ada data</td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4 flex justify-end">
    {{ $products->links('pagination::tailwind') }}
    </div>

</div>

<!-- MODAL DELETE -->
<div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-lg w-80 p-6 text-center">
        
        <h2 class="text-lg font-semibold mb-2">Hapus Produk</h2>
        <p class="text-sm text-gray-500 mb-4">
            Yakin ingin menghapus produk ini?
        </p>

        <div class="flex justify-center gap-3">
            <button onclick="closeModal()" 
                class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                Batal
            </button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Hapus
                </button>
            </form>
        </div>

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

function openModal(actionUrl) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
    document.getElementById('deleteForm').action = actionUrl;
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

@endsection