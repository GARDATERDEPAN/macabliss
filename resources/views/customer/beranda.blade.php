@extends('layouts.customer')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <h1 class="text-2xl font-bold text-gray-900">
            Beranda
        </h1>

        <!-- RIGHT -->
        <div class="flex items-center gap-3">

            <!-- SEARCH -->
            <input type="text" id="searchInput"
                placeholder="Cari nama produk..."
                class="border rounded-lg px-3 py-2 text-sm w-[220px]
                       focus:outline-none focus:ring-2 focus:ring-red-300">

            {{-- <!-- BUTTON -->
            <a href="{{ route('customer.pesanan') }}"
               class="bg-red-400 text-white px-4 py-2 rounded-lg 
                      text-sm hover:bg-red-500 shadow-sm hover:shadow-md transition">
                Lihat Pesanan
            </a> --}}

        </div>

    </div>

    <div class="bg-white border rounded-xl shadow-sm p-5 mb-6 flex items-start gap-4">

    <!-- ICON -->
    <div class="bg-red-100 text-red-500 p-3 rounded-full">
        <i data-lucide="map-pin" class="w-6 h-6"></i>
    </div>

    <!-- TEXT -->
    <div class="flex-1">
        <h3 class="font-semibold text-gray-800">
            Lokasi Rumah Produksi Macabliss
        </h3>

        <p class="text-sm text-gray-500">
            Jl. Merdeka Gang Otok No. 54 B, Samarinda
        </p>

        <!-- BUTTON -->
        <a href="https://www.google.com/maps?q=-0.4863,117.1665"
           target="_blank"
           class="inline-block mt-2 text-sm text-blue-600 hover:underline">
            Lihat di Google Maps →
        </a>
    </div>

</div>

    <!-- LIST -->
    <div class="bg-white rounded-xl shadow-sm border">

        @forelse($products as $item)
        <div class="p-5 flex gap-4 items-start hover:bg-gray-50 transition border-b last:border-b-0"
             data-product="{{ $item->nama_produk }}">

            <!-- TEXT -->
            <div class="flex-1">
                <h3 class="text-base font-semibold text-gray-900 leading-tight">
                    {{ $item->nama_produk }}
                </h3>

                <p class="text-xs text-gray-400 italic mt-1">
                    {{ $item->deskripsi ?? '-' }}
                </p>

                <p class="mt-2 text-sm font-semibold text-gray-900">
                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    Estimasi : 2 Hari
                </p>
            </div>

            <!-- IMAGE + ACTION -->
            <div class="flex flex-col items-center w-[90px] gap-2">

                <!-- GAMBAR -->
                @if($item->gambar && file_exists(public_path('storage/' . $item->gambar)))
                    <img src="{{ asset('storage/' . $item->gambar) }}" 
                         onclick="openImage('{{ asset('storage/' . $item->gambar) }}')"
                         class="w-16 h-16 object-cover rounded cursor-pointer 
                                transition duration-300 hover:scale-110">
                @else
                    <div class="w-16 h-16 bg-gray-200 flex items-center justify-center text-xs text-gray-500 rounded">
                        No Image
                    </div>
                @endif

                <!-- BUTTON -->
                <div class="relative w-full h-[34px]">

                    <!-- ADD -->
                    <button onclick="addItem({{ $item->id }}, {{ $item->harga }})" 
                        id="add-btn-{{ $item->id }}"
                        class="absolute inset-0 bg-red-400 text-white 
                               rounded-full text-xs flex items-center justify-center
                               shadow-sm hover:shadow-md hover:scale-105 active:scale-95 transition">
                        Add
                    </button>

                    <!-- COUNTER -->
                    <div id="counter-{{ $item->id }}" 
                        class="absolute inset-0 hidden items-center justify-between 
                               bg-red-400 text-white rounded-full px-2">

                        <button onclick="decreaseItem({{ $item->id }})" 
                                class="w-6 text-center font-bold">-</button>

                        <span id="qty-{{ $item->id }}" 
                              class="w-8 text-center text-sm font-medium">
                            1
                        </span>

                        <button onclick="increaseItem({{ $item->id }})" 
                                class="w-6 text-center font-bold">+</button>

                    </div>

                </div>

            </div>

        </div>

        @empty
        <div class="p-6 text-center text-gray-500">
            Tidak ada produk
        </div>
        @endforelse

        <div id="emptySearch" class="p-6 text-center text-gray-400 hidden">
            <p class="text-sm">Produk tidak ditemukan</p>
        </div>

    </div>

</div>

<!-- 🔥 STICKY CART -->
<div id="cart-bar" 
     class="fixed bottom-4 left-1/2 -translate-x-1/2 
            w-[92%] max-w-md bg-red-400 text-white 
            rounded-xl shadow-lg px-4 py-3 
            flex justify-between items-center hidden">

    <div>
        <p id="cart-count" class="text-sm font-semibold">0 item</p>
        <p id="cart-total" class="text-xs">Rp 0</p>
    </div>

    <a href="{{ route('customer.pesanan') }}"
       class="bg-white text-red-500 px-4 py-2 rounded-lg text-sm font-medium">
        Lihat Pesanan
    </a>
</div>

<!-- MODAL ZOOM IMAGE -->
<div id="imageModal" 
     class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">

    <img id="modalImage" 
         class="max-w-[90%] max-h-[90%] rounded-lg shadow-lg">

</div>

<!-- SCRIPT -->
<script>
let hasProduct = {{ $products->count() > 0 ? 'true' : 'false' }};
let cart = @json(session('cart', []));

document.addEventListener("DOMContentLoaded", function(){

    updateCartBar();

    Object.keys(cart).forEach(id => {

        let item = cart[id];

        let addBtn = document.getElementById('add-btn-' + id);
        let counter = document.getElementById('counter-' + id);
        let qty = document.getElementById('qty-' + id);

        if(addBtn) addBtn.classList.add('hidden');

        if(counter){
            counter.classList.remove('hidden');
            counter.classList.add('flex');
        }

        if(qty){
            qty.innerText = item.qty;
        }

    });

});

// SEARCH
document.getElementById('searchInput').addEventListener('keyup', function() {
    let keyword = this.value.toLowerCase();
    let found = 0;

    document.querySelectorAll('[data-product]').forEach(item => {
        let name = item.getAttribute('data-product').toLowerCase();

        if (name.includes(keyword)) {
            item.style.display = 'flex';
            found++;
        } else {
            item.style.display = 'none';
        }
    });

    let empty = document.getElementById('emptySearch');

    // 🔥 LOGIKA PENTING
    if (hasProduct && found === 0 && keyword !== '') {
        empty.classList.remove('hidden');
    } else {
        empty.classList.add('hidden');
    }
});

// ADD
function addItem(id, harga) {

    let formData = new FormData();
    formData.append('id', id);
    formData.append('_token', '{{ csrf_token() }}');

    fetch("{{ route('cart.add') }}", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if(data.success){

            if(!cart[id]){
                cart[id] = { qty: 0, harga: harga };
            }

            cart[id].qty++;

            updateCartBar();

            document.getElementById('add-btn-' + id).classList.add('hidden');

            let counter = document.getElementById('counter-' + id);
            counter.classList.remove('hidden');
            counter.classList.add('flex');

            document.getElementById('qty-' + id).innerText = cart[id].qty;
        }

    });
}

// COUNTER
function increaseItem(id) {
    addItem(id, cart[id].harga);
}

function decreaseItem(id) {

    let formData = new FormData();
    formData.append('id', id);
    formData.append('qty', cart[id].qty - 1);
    formData.append('_token', '{{ csrf_token() }}');

    fetch("{{ route('cart.update') }}", {
        method: "POST",
        body: formData
    }).then(() => {

        if(cart[id].qty > 1){
            cart[id].qty--;
            document.getElementById('qty-' + id).innerText = cart[id].qty;
        } else {
            delete cart[id];
            document.getElementById('counter-' + id).classList.add('hidden');
            document.getElementById('add-btn-' + id).classList.remove('hidden');
        }

        updateCartBar();

    });
}

// UPDATE CART BAR
function updateCartBar() {
    let totalItem = 0;
    let totalHarga = 0;

    Object.values(cart).forEach(item => {
        totalItem += item.qty;
        totalHarga += item.qty * item.harga;
    });

    if(totalItem > 0){
        document.getElementById('cart-bar').classList.remove('hidden');
        document.getElementById('cart-count').innerText = totalItem + " item";
        document.getElementById('cart-total').innerText = "Rp " + totalHarga.toLocaleString('id-ID');
    } else {
        document.getElementById('cart-bar').classList.add('hidden');
    }
}

// ZOOM IMAGE
function openImage(src) {
    let modal = document.getElementById('imageModal');
    let img = document.getElementById('modalImage');

    img.src = src;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

document.getElementById('imageModal').addEventListener('click', function() {
    this.classList.add('hidden');
});

</script>

@endsection