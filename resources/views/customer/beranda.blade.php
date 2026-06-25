@extends('layouts.customer')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- HEADER -->
    {{-- <div class="flex justify-between items-center mb-10">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Macabliss
            </h1>
        </div>

    </div> --}}

    <!-- HERO CARD -->
    <div class="relative bg-gradient-to-r from-red-400 to-red-500 rounded-3xl shadow-xl p-6 md:p-8 mb-8 text-white">

        <div class="max-w-3xl">

            <h1 class="text-3xl md:text-3xl font-bold mb-4">
                Selamat Datang di Macabliss!
            </h1>

            <p class="text-sm md:text-lg leading-relaxed opacity-95">
                Macabliss menyediakan berbagai pilihan makanan
                ringan yang dapat dipesan secara online dengan
                mudah. Pilih produk favorit Anda dan lakukan 
                pemesanan langsung melalui website.
            </p>

        </div>

        <!-- FRESH LABEL -->
        <div class="mt-8 flex justify-end">

            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur px-4 py-2 rounded-full border border-white/20">

                <span class="text-lg">
                    ✨
                </span>

                <span class="font-semibold">
                    Fresh From The Oven
                </span>

            </div>

        </div>

    </div>

    <!-- CARA PEMESANAN -->
    {{-- <div class="mb-10">

        <h2 class="text-2xl font-bold text-gray-800 mb-5">
            Cara Pemesanan
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-xl transition">

                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-2xl mb-4">
                    1️⃣
                </div>

                <h3 class="font-bold text-lg mb-2">
                    Pilih Kategori
                </h3>

                <p class="text-sm text-gray-500">
                    Pilih kategori produk yang ingin dipesan.
                </p>

            </div>

            <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-xl transition">

                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-2xl mb-4">
                    2️⃣
                </div>

                <h3 class="font-bold text-lg mb-2">
                    Tambah Keranjang
                </h3>

                <p class="text-sm text-gray-500">
                    Tambahkan produk ke keranjang belanja.
                </p>

            </div>

            <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-xl transition">

                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-2xl mb-4">
                    3️⃣
                </div>

                <h3 class="font-bold text-lg mb-2">
                    Isi Data Pesanan
                </h3>

                <p class="text-sm text-gray-500">
                    Tentukan metode pengambilan atau pengiriman.
                </p>

            </div>

            <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-xl transition">

                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-2xl mb-4">
                    4️⃣
                </div>

                <h3 class="font-bold text-lg mb-2">
                    Selesaikan Pembayaran
                </h3>

                <p class="text-sm text-gray-500">
                    Bayar melalui QRIS atau COD dan tunggu pesanan diproses.
                </p>

            </div>

        </div>

    </div> --}}

    <!-- JUDUL KATEGORI -->
    <div class="mb-6">

        <h2 class="text-2xl font-bold text-gray-800">
            Kategori Produk
        </h2>

        <p class="text-gray-500 mt-2">
            Pilih salah satu kategori untuk melihat produk yang tersedia.
        </p>

    </div>

    <!-- KATEGORI -->
    @foreach ($categories as $category)
    @endforeach

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-10">

        @foreach ($categories as $category)

            <button
                onclick="showCategory({{ $category->id }})"
                class="flex flex-col items-center
                       hover:scale-105 transition duration-300">

                @if($category->gambar && file_exists(public_path('images/' . $category->gambar)))

                    <img src="{{ asset('images/' . $category->gambar) }}"
                         class="w-28 h-28 md:w-40 md:h-40 lg:w-44 lg:h-44 rounded-full
                                object-cover border-[6px]
                                border-red-100 shadow-xl">

                @else

                    <div class="w-28 h-28 md:w-40 md:h-40 lg:w-44 lg:h-44 rounded-full
                                bg-gray-200 border-[6px]
                                border-red-100 shadow-xl
                                flex items-center justify-center
                                text-sm text-gray-500">

                        No Image

                    </div>

                @endif

                <p class="mt-4 text-xl font-bold
                          text-gray-800 text-center">

                    {{ $category->nama_kategori }}

                </p>

            </button>

        @endforeach

    </div>

    <!-- GALERI PRODUK -->
    <div class="mt-16 mb-10">

        <div class="mb-6">

            <h2 class="text-2xl font-bold text-gray-800">
                Menu Produk
            </h2>

            <p class="text-gray-500 mt-2">
                Beberapa produk yang tersedia di Macabliss.
            </p>

        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-5">

            @foreach ($galeries as $item)

                <div
                    class="group overflow-hidden rounded-3xl shadow-lg bg-white">

                    @if($item->gambar && file_exists(public_path('storage/' . $item->gambar)))

                        <img
                            src="{{ asset('storage/' . $item->gambar) }}"
                            class="w-full h-52 object-cover
                                group-hover:scale-110
                                transition duration-500">

                    @else

                        <div
                            class="w-full h-52
                                bg-gray-200
                                flex items-center
                                justify-center
                                text-gray-500">

                            No Image

                        </div>

                    @endif

                    <div class="p-4">

    <div class="flex justify-between items-start gap-3">

        <div>

            <h3 class="font-bold text-lg text-gray-800">
                {{ $item->nama_produk }}
            </h3>

            <p class="text-red-500 font-semibold mt-1">
                Rp {{ number_format($item->harga,0,',','.') }}
            </p>

        </div>

        <button
            onclick="addItem({{ $item->id }}, {{ $item->harga }})"
            class="bg-red-400 hover:bg-red-500
                   text-white w-10 h-10
                   rounded-full
                   shadow-md
                   flex items-center
                   justify-center
                   transition
                   hover:scale-110">

            +

        </button>

    </div>

</div>

                </div>

            @endforeach

        </div>

    </div>

</div>

<!-- POPUP -->
<div id="popup-overlay"
     class="fixed inset-0 bg-black/40
            backdrop-blur-sm hidden
            items-center justify-center
            z-50 p-4">

    <!-- CONTAINER -->
    <div class="relative w-full max-w-6xl">

        <!-- STICKY CLOSE -->
        <button onclick="closePopup()"
                class="absolute top-6 right-6
                       z-50 w-11 h-11
                       rounded-full
                       bg-white/90 backdrop-blur
                       shadow-lg
                       text-red-500
                       hover:bg-red-500
                       hover:text-white
                       transition
                       flex items-center
                       justify-center
                       text-2xl font-bold">

            ×

        </button>

        <!-- BOX -->
        <div class="bg-white rounded-[35px]
                    shadow-2xl p-8
                    max-h-[90vh]
                    overflow-y-auto
                    animate-popup">

            <!-- TOP -->
            <div class="flex justify-between
                        items-start gap-5
                        mb-8 pr-16">

                <!-- LEFT -->
                <div class="flex-1">

                    <!-- TITLE -->
                    <h2 id="popup-title"
                        class="text-3xl font-bold
                               text-gray-900">

                    </h2>

                    <div class="h-[3px]
                                bg-red-100
                                rounded-full
                                mt-4">

                    </div>

                </div>

                <!-- SEARCH -->
                <input type="text"
                       id="popup-search"
                       placeholder="Cari produk..."
                       class="w-[220px]
                              border border-gray-300
                              rounded-full
                              px-5 py-3
                              text-sm
                              focus:outline-none
                              focus:ring-2
                              focus:ring-red-300">

            </div>

            <!-- PRODUCTS -->
            <div id="popup-products"
                 class="grid grid-cols-1
                        md:grid-cols-2 gap-6">

            </div>

        </div>

    </div>

</div>

<!-- STICKY CART -->
<div id="cart-bar"
     class="fixed bottom-5 left-1/2
            -translate-x-1/2
            w-[82%] max-w-lg
            bg-red-400 text-white
            rounded-2xl shadow-xl
            px-4 py-3 hidden
            justify-between items-center
            z-50">

    <div>

        <p id="cart-count"
           class="font-bold text-lg">

            0 item

        </p>

        <p id="cart-total"
           class="text-sm opacity-90">

            Rp 0

        </p>

    </div>

    <a href="{{ route('customer.pesanan') }}"
       class="bg-white text-red-400
              px-5 py-3 rounded-xl
              font-bold">

        Lihat Pesanan

    </a>

</div>

<!-- IMAGE MODAL -->
<div id="imageModal"
     class="fixed inset-0
            bg-black/70 hidden
            items-center justify-center
            z-[999]">

    <img id="modalImage"
         class="max-w-[90%]
                max-h-[90%]
                rounded-2xl shadow-xl">

</div>

<!-- FOOTER -->
<footer class="bg-white border-t mt-1 rounded-t-3xl overflow-hidden">

    <div class="max-w-7xl mx-auto px-4 md:px-8 py-8">

        <div class="flex flex-col md:flex-row items-center justify-between gap-6">

            <!-- INFO -->
            <div>

                <h3 class="text-2xl font-bold text-red-500">
                    Macabliss
                </h3>


            </div>

            <!-- SOSIAL MEDIA -->
            <div class="flex items-center gap-8">

                <!-- INSTAGRAM -->
                <a
                    href="https://instagram.com/macabliss"
                    target="_blank"
                    class="flex items-center gap-2
                           text-gray-700
                           hover:text-pink-500
                           transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M16 2H8a6 6 0 00-6 6v8a6 6 0 006 6h8a6 6 0 006-6V8a6 6 0 00-6-6z"/>

                        <circle cx="12"
                                cy="12"
                                r="3"/>

                        <circle cx="17.5"
                                cy="6.5"
                                r="1"/>

                    </svg>

                    <span>
                        @macabliss
                    </span>

                </a>

                <!-- WHATSAPP -->
                <a
                    href="https://wa.me/6282152285028"
                    target="_blank"
                    class="flex items-center gap-2
                           text-gray-700
                           hover:text-green-500
                           transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="currentColor"
                         viewBox="0 0 24 24">

                        <path d="M12.04 2C6.56 2 2.1 6.46 2.1 11.94c0 1.76.46 3.47 1.33 4.98L2 22l5.23-1.37a9.88 9.88 0 004.81 1.23h.01c5.48 0 9.94-4.46 9.94-9.94C22 6.46 17.52 2 12.04 2zm0 18.06c-1.49 0-2.94-.4-4.2-1.15l-.3-.18-3.1.81.83-3.02-.2-.31a8.11 8.11 0 01-1.26-4.27c0-4.49 3.65-8.14 8.14-8.14 4.48 0 8.13 3.65 8.13 8.14s-3.65 8.12-8.14 8.12zm4.46-6.09c-.24-.12-1.4-.69-1.62-.77-.22-.08-.38-.12-.54.12-.16.24-.62.77-.76.93-.14.16-.28.18-.52.06-.24-.12-1.02-.38-1.94-1.22-.72-.64-1.21-1.43-1.35-1.67-.14-.24-.02-.37.1-.49.1-.1.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.31-.74-1.79-.2-.48-.4-.42-.54-.42h-.46c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.7 2.6 4.12 3.65.58.25 1.03.4 1.38.51.58.18 1.1.15 1.52.09.46-.07 1.4-.57 1.6-1.13.2-.56.2-1.04.14-1.14-.06-.1-.22-.16-.46-.28z"/>

                    </svg>

                    <span>
                        +62 821-5228-5028
                    </span>

                </a>

            </div>

        </div>

        <!-- COPYRIGHT -->
        <div class="border-t mt-6 pt-4 text-center mx-0">

            <p class="text-gray-400 text-sm">
                © {{ date('Y') }} Macabliss. All Rights Reserved.
            </p>

        </div>

    </div>

</footer>

<style>

.animate-popup{
    animation: popup .25s ease;
}

@keyframes popup{

    from{
        opacity:0;
        transform:scale(.95);
    }

    to{
        opacity:1;
        transform:scale(1);
    }

}

</style>

<script>

let cart = @json(session('cart', []));

// DATA PRODUK
const categoryProducts = {

@foreach ($categories as $category)

"{{ $category->id }}": {

    name: "{{ $category->nama_kategori }}",

    products: [

        @foreach($category->products as $item)

        {

            id: "{{ $item->id }}",
            nama: "{{ $item->nama_produk }}",
            deskripsi: "{{ $item->deskripsi ?? '-' }}",
            harga: "{{ number_format($item->harga, 0, ',', '.') }}",
            hargaRaw: "{{ $item->harga }}",
            rating: "{{ $item->averageRating() ?: 0 }}",
            total: "{{ $item->totalRatings() }}",
            estimasi: "{{ $item->estimasi ?? '-' }}",

            gambar: `

            @if($item->gambar && file_exists(public_path('storage/' . $item->gambar)))

                <img src="{{ asset('storage/' . $item->gambar) }}"
                     onclick="openImage('{{ asset('storage/' . $item->gambar) }}')"
                     class="w-32 h-32 rounded-2xl
                            object-cover cursor-pointer
                            hover:scale-105 transition">

            @else

                <div class="w-32 h-32 rounded-2xl
                            bg-gray-200 flex
                            items-center justify-center
                            text-gray-500">

                    No Image

                </div>

            @endif

            `

        },

        @endforeach

    ]

},

@endforeach

};

// SHOW CATEGORY
function showCategory(id){

    const overlay =
        document.getElementById('popup-overlay');

    const title =
        document.getElementById('popup-title');

    const products =
        document.getElementById('popup-products');

    overlay.classList.remove('hidden');
    overlay.classList.add('flex');

    const category = categoryProducts[id];

    title.innerText = category.name;

    products.innerHTML = '';

    category.products.forEach(item => {

        products.innerHTML += `

        <div class="popup-product
                    bg-white rounded-[30px]
                    shadow-md border
                    border-gray-100
                    p-5 flex gap-5">

            <!-- LEFT -->
            <div class="flex flex-col
                        items-center">

                <!-- IMAGE -->
                <div>

                    ${item.gambar}

                </div>

                <!-- BUTTON -->
                <div class="mt-4 relative
                            w-[140px]
                            h-[46px]">

                    <!-- ADD -->
                    <button
                        onclick="addItem(${item.id}, ${item.hargaRaw})"
                        id="add-btn-${item.id}"
                        class="absolute inset-0
                               border-2 border-red-400
                               text-red-500
                               rounded-full
                               text-sm font-bold
                               hover:bg-red-400
                               hover:text-white
                               transition">

                        Add

                    </button>

                    <!-- COUNTER -->
                    <div id="counter-${item.id}"
                        class="absolute inset-0 hidden
                               items-center justify-between
                               bg-red-400 text-white
                               rounded-full px-5">

                        <button
                            onclick="decreaseItem(${item.id})"
                            class="text-2xl font-bold">

                            -

                        </button>

                        <span id="qty-${item.id}"
                              class="font-bold text-base">

                            1

                        </span>

                        <button
                            onclick="increaseItem(${item.id})"
                            class="text-2xl font-bold">

                            +

                        </button>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="flex-1 flex
                        flex-col justify-center">

                <!-- NAMA -->
                <h3 class="text-xl font-bold
                           text-gray-900">

                    ${item.nama}

                </h3>

                <!-- DESKRIPSI -->
                <p class="text-sm text-gray-500
                          mt-2 line-clamp-2">

                    ${item.deskripsi}

                </p>

                <!-- HARGA -->
                <p class="mt-3 text-lg
                          font-bold text-red-500">

                    Rp ${item.harga}

                </p>

                <!-- ESTIMASI -->
                <p class="mt-2 text-sm text-gray-500">

                    Estimasi: ${item.estimasi}

                </p>

                <!-- RATING -->
                <div class="mt-3">

                    <div class="flex items-center gap-1">

                        ${generateStars(item.id)}

                    </div>

                    <p class="text-sm text-gray-400 mt-1">

                        <span id="rating-text-${item.id}">

                            ${getAverageRating(item.id)}

                        </span>

                    </p>

                </div>

            </div>

        </div>

        `;

    });

    // SEARCH POPUP
    document.getElementById('popup-search')
    .addEventListener('keyup', function(){

        const keyword =
            this.value.toLowerCase();

        document.querySelectorAll('.popup-product')
        .forEach(product => {

            const text =
                product.innerText.toLowerCase();

            if(text.includes(keyword)){

                product.style.display = 'flex';

            } else {

                product.style.display = 'none';

            }

        });

    });

    syncCartUI();

}

// CLOSE POPUP
function closePopup(){

    const overlay =
        document.getElementById('popup-overlay');

    overlay.classList.remove('flex');
    overlay.classList.add('hidden');

}

// CLICK OUTSIDE
document.getElementById('popup-overlay')
.addEventListener('click', function(e){

    if(e.target.id === 'popup-overlay'){

        closePopup();

    }

});

// LOAD
document.addEventListener("DOMContentLoaded", function(){

    updateCartBar();

});

// SYNC UI
function syncCartUI(){

    Object.keys(cart).forEach(id => {

        let item = cart[id];

        let addBtn =
            document.getElementById('add-btn-' + id);

        let counter =
            document.getElementById('counter-' + id);

        let qty =
            document.getElementById('qty-' + id);

        if(addBtn){

            addBtn.classList.add('hidden');

        }

        if(counter){

            counter.classList.remove('hidden');
            counter.classList.add('flex');

        }

        if(qty){

            qty.innerText = item.qty;

        }

    });

}

// ADD
function addItem(id, harga){

    let formData = new FormData();

    formData.append('id', id);

    formData.append(
        '_token',
        '{{ csrf_token() }}'
    );

    fetch("{{ route('cart.add') }}", {

        method: "POST",
        body: formData

    })

    .then(res => res.json())

    .then(data => {

        if(data.success){

            if(!cart[id]){

                cart[id] = {

                    qty: 0,
                    harga: harga

                };

            }

            cart[id].qty++;

            updateCartBar();
            syncCartUI();

        }

    });

}

// INCREASE
function increaseItem(id){

    addItem(id, cart[id].harga);

}

// DECREASE
function decreaseItem(id){

    let formData = new FormData();

    formData.append('id', id);

    formData.append(
        'qty',
        cart[id].qty - 1
    );

    formData.append(
        '_token',
        '{{ csrf_token() }}'
    );

    fetch("{{ route('cart.update') }}", {

        method: "POST",
        body: formData

    })

    .then(() => {

        if(cart[id].qty > 1){

            cart[id].qty--;

        } else {

            delete cart[id];

        }

        updateCartBar();
        refreshPopup();

    });

}

// REFRESH
function refreshPopup(){

    const currentTitle =
        document.getElementById(
            'popup-title'
        ).innerText;

    for(let id in categoryProducts){

        if(
            categoryProducts[id].name
            === currentTitle
        ){

            showCategory(id);

        }

    }

}

// CART
function updateCartBar(){

    let totalItem = 0;
    let totalHarga = 0;

    Object.values(cart).forEach(item => {

        totalItem += item.qty;

        totalHarga +=
            item.qty * item.harga;

    });

    const cartBar =
        document.getElementById(
            'cart-bar'
        );

    if(totalItem > 0){

        cartBar.classList.remove('hidden');
        cartBar.classList.add('flex');

        document.getElementById(
            'cart-count'
        ).innerText =
        totalItem + ' item';

        document.getElementById(
            'cart-total'
        ).innerText =
        'Rp ' +
        totalHarga.toLocaleString(
            'id-ID'
        );

    } else {

        cartBar.classList.remove('flex');
        cartBar.classList.add('hidden');

    }

}

// IMAGE
function openImage(src){

    const modal =
        document.getElementById(
            'imageModal'
        );

    const img =
        document.getElementById(
            'modalImage'
        );

    img.src = src;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

}

// GENERATE STARS
function generateStars(productId){

    let html = '';

    let current =
        localStorage.getItem(
            'user_rating_' + productId
        ) || 0;

    let locked = current > 0;

    for(let i = 1; i <= 5; i++){

        html += `

        <button
            ${locked
                ? 'disabled'
                : `onclick="saveRating(${productId}, ${i})"`
            }

            class="
                ${i <= current
                    ? 'text-yellow-400'
                    : 'text-gray-300'}

                text-2xl

                ${locked
                    ? 'cursor-default'
                    : 'hover:scale-110'}

                transition">

            ★

        </button>

        `;

    }

    return html;

}

// SAVE RATING
function saveRating(productId, rating){

    // CHECK USER SUDAH RATING?
    let alreadyRated =
        localStorage.getItem(
            'user_rating_' + productId
        );

    // JIKA SUDAH
    if(alreadyRated){

        alert(
            'Kamu sudah memberi rating ⭐'
        );

        return;

    }

    // SIMPAN USER RATING
    localStorage.setItem(
        'user_rating_' + productId,
        rating
    );

    // GET SEMUA RATING
    let ratings =
        JSON.parse(
            localStorage.getItem(
                'ratings_' + productId
            )
        ) || [];

    // TAMBAH RATING BARU
    ratings.push(rating);

    // SAVE
    localStorage.setItem(
        'ratings_' + productId,
        JSON.stringify(ratings)
    );

    // REFRESH
    refreshPopup();

}

// GET AVG
function getAverageRating(productId){

    let ratings =
        JSON.parse(
            localStorage.getItem(
                'ratings_' + productId
            )
        ) || [];

    if(ratings.length === 0){

        return 'Belum ada rating';

    }

    let total = 0;

    ratings.forEach(rate => {

        total += parseInt(rate);

    });

    let avg =
        (total / ratings.length)
        .toFixed(1);

    return `
        ⭐ ${avg}
        (${ratings.length})
    `;

}

// CLOSE IMAGE
document.getElementById('imageModal')
.addEventListener('click', function(){

    this.classList.remove('flex');
    this.classList.add('hidden');

});

</script>

@endsection