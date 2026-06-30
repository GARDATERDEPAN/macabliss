@extends('layouts.customer')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

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

    <!-- JUDUL KATEGORI -->
    <div class="mb-6">

        <h2 class="text-center text-2xl font-bold text-gray-800">
            Kategori Produk
        </h2>

        <p class="text-center text-gray-500 mt-2">
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

                @if($category->gambar)

                    <img src="{{ asset('storage/' . $category->gambar) }}"
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
    <div class="mt-16 mb-16">

        <div class="mb-6">

            <h2 class="text-center text-2xl font-bold text-gray-800">
                Menu Produk
            </h2>

            <p class="text-center text-gray-500 mt-2">
                Beberapa produk yang tersedia di Macabliss.
            </p>

        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">

            @foreach ($galeries as $item)

                <div
                    class="group overflow-hidden rounded-3xl shadow-lg bg-white">

                    @if($item->gambar)

                        <img
                            src="{{ asset('storage/' . $item->gambar) }}"
                            class="w-full h-52 object-cover">

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

                        <h3 class="font-bold text-lg text-gray-800">
                            {{ $item->nama_produk }}
                        </h3>

                        <div class="mt-3 flex items-center justify-between gap-3">

                            <p class="text-red-500 font-semibold text-md">
                                Rp {{ number_format($item->harga,0,',','.') }}
                            </p>

                            <div
                                class="
                                    relative
                                    w-20 md:w-24
                                    h-9
                                    flex-shrink-0
                                "
                            >

    <button
        onclick="addItem({{ $item->id }}, {{ $item->harga }})"
        id="gallery-add-btn-{{ $item->id }}"
        class="
            absolute inset-0
            flex items-center justify-center
            border-2 border-red-400
            bg-white
            text-red-500
            rounded-full
            text-xs md:text-sm
            font-semibold
            hover:bg-red-400
            hover:text-white
            transition
        "
    >
        Add
    </button>

    <div
        id="gallery-counter-{{ $item->id }}"
        class="
            absolute inset-0 hidden
            items-center justify-between
            bg-red-400
            text-white
            rounded-full
            px-3
            text-sm
        ">

        <button
            onclick="decreaseItem({{ $item->id }})"
            class="text-base font-bold">

            -

        </button>

        <span
            id="gallery-qty-{{ $item->id }}"
            class="text-base font-bold">

            1

        </span>

        <button
            onclick="increaseItem({{ $item->id }})"
            class="text-lg font-bold">

            +

        </button>

    </div>

</div>

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
                       class="w-[160px]
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

<!-- TESTIMONIAL -->
<section class="mt-0 mb-8">

    <!-- TITLE -->
    <div class="text-center mb-8">

        <h2
            class="text-2xl
                   font-bold
                   text-gray-800"
        >
            Testimoni Pelanggan
        </h2>

        <p
            class="text-gray-500
                   mt-2"
        >
            Apa kata pelanggan tentang produk Macabliss
        </p>

    </div>


    <!-- GRID -->
    <div
        class="w-full
            grid
            grid-cols-2
            md:grid-cols-3
            gap-4 md:gap-5"
    >

        @forelse($testimonials->take(6) as $testimonial)

            <div
                class="bg-white
                       rounded-3xl
                       shadow-lg
                       hover:shadow-xl
                       transition
                       p-4
                       min-h-[170px]"
            >

                <!-- RATING -->
                <div
                    class="flex
                           gap-1
                           mb-4"
                >

                    @for($i = 1; $i <= 5; $i++)

                        <span
                            class="
                            text-lg
                            {{ $i <= $testimonial->rating
                                ? 'text-yellow-400'
                                : 'text-gray-300'
                            }}
                            "
                        >
                            ★
                        </span>

                    @endfor

                </div>


                <!-- KOMENTAR -->
                <div
                    class="px-1
                           min-h-[72px]
                           "
                >

                    <p
                        class="text-gray-600
                               italic
                                leading-6
                                text-sm
                                line-clamp-3"
                    >
                        "{{ $testimonial->komentar }}"
                    </p>

                </div>


                <!-- GARIS -->
                <div
                    class="border-t
                           border-gray-100
                            w-[90%]
           mx-auto
           mt-2
           mb-2"
                ></div>


                <!-- CUSTOMER -->
                <div
                    class="flex
                           items-center
                           gap-3"
                >

                    <!-- AVATAR -->
                    <div
                        class="w-12
                               h-12
                               rounded-full
                               bg-red-100
                               flex
                               items-center
                               justify-center
                               text-red-500
                               font-bold
                               text-lg
                               shrink-0"
                    >

                        {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}

                    </div>


                    <!-- INFO -->
                    <div>

                        <h4
                            class="font-semibold
                                   text-gray-900
                                   text-base"
                        >
                            {{ $testimonial->user->name }}
                        </h4>

                        <p
                            class="text-sm
                                   text-gray-500"
                        >
                            {{ $testimonial->product->nama_produk }}
                        </p>

                    </div>

                </div>

            </div>

        @empty

            <div
                class="col-span-full
                       text-center
                       py-10
                       text-gray-400"
            >

                Belum ada testimoni pelanggan.

            </div>

        @endforelse

    </div>

</section>

<!-- FOOTER -->
<footer class="bg-white border-t mt-2 rounded-t-3xl overflow-hidden">

    <div class="max-w-7xl mx-auto px-4 md:px-8 py-8">

        <div class="flex flex-col md:flex-row items-center justify-between gap-6">

            <!-- INFO -->
            <div>

                <h3 class="text-2xl font-bold text-red-500">
                    Macabliss
                </h3>


            </div>

            <!-- SOSIAL MEDIA -->
            <div class="flex items-center gap-6">

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
            average_rating: {{ $item->averageRating() ?? 0 }},
            total_ratings: {{ $item->totalRatings() ?? 0 }},
            estimasi: "{{ $item->estimasi ?? '-' }}",
            gambar: "{{ $item->gambar ? asset('storage/' . $item->gambar) : '' }}"
        }@if(!$loop->last),@endif

        @endforeach

    ]

}@if(!$loop->last),@endif

@endforeach

};

function generateStars(rating) {

    rating = parseFloat(rating);

    let html = '';

    for (let i = 1; i <= 5; i++) {

        let fill = 0;

        if (rating >= i) {

            fill = 100;

        }
        else if (rating >= i - 0.5) {

            fill = 50;

        }

        html += `
        <div class="relative w-5 h-5">

            <!-- STAR ABU -->
            <svg
                class="absolute inset-0 w-5 h-5 text-gray-300"
                fill="currentColor"
                viewBox="0 0 20 20">

                <path d="M9.049 2.927c.3-.921 1.603-.921
                1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969
                0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0
                00-.364 1.118l1.07 3.292c.3.921-.755
                1.688-1.54 1.118l-2.8-2.034a1 1 0
                00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118
                l1.07-3.292a1 1 0 00-.364-1.118L2.98
                8.72c-.783-.57-.38-1.81.588-1.81h3.461a1
                1 0 00.951-.69l1.07-3.292z"/>
            </svg>

            <!-- STAR KUNING -->
            <div
                class="absolute inset-0 overflow-hidden"
                style="width:${fill}%">

                <svg
                    class="w-5 h-5 text-yellow-400"
                    fill="currentColor"
                    viewBox="0 0 20 20">

                    <path d="M9.049 2.927c.3-.921 1.603-.921
                    1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969
                    0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0
                    00-.364 1.118l1.07 3.292c.3.921-.755
                    1.688-1.54 1.118l-2.8-2.034a1 1 0
                    00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118
                    l1.07-3.292a1 1 0 00-.364-1.118L2.98
                    8.72c-.783-.57-.38-1.81.588-1.81h3.461a1
                    1 0 00.951-.69l1.07-3.292z"/>
                </svg>

            </div>

        </div>
        `;

    }

    return html;

}

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
            <div class="flex flex-col items-center">

                <!-- IMAGE -->
                <div>

                    ${
                        item.gambar
                        ? `
                            <img src="${item.gambar}"
                                onclick="openImage('${item.gambar}')"
                                class="w-32 h-32 rounded-2xl
                                       object-cover cursor-pointer
                                       hover:scale-105 transition">
                        `
                        : `
                            <div class="w-32 h-32 rounded-2xl
                                        bg-gray-200 flex
                                        items-center justify-center
                                        text-gray-500">

                                No Image

                            </div>
                        `
                    }

                </div>

                <!-- BUTTON -->
                <div class="mt-4 relative
                            w-[140px]
                            h-[46px]">

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
            <div class="flex-1 flex flex-col justify-center">

                <h3 class="text-xl font-bold text-gray-900">

                    ${item.nama}

                </h3>

                <p class="text-sm text-gray-500 mt-2 line-clamp-2">

                    ${item.deskripsi}

                </p>

                <p class="mt-3 text-lg font-bold text-red-500">

                    Rp ${item.harga}

                </p>

                <p class="mt-2 text-sm text-gray-500">

                    Estimasi: ${item.estimasi}

                </p>

                <!-- RATING -->
                <div class="mt-3">

                    ${
                        item.total_ratings > 0
                        ? `
                            <div class="flex items-center gap-1 mb-1">

                                ${generateStars(item.average_rating)}

                            </div>

                            <p class="text-sm text-gray-500">

                                ${item.average_rating}
                                (${item.total_ratings} ulasan)

                            </p>
                        `
                        : `
                            <p class="text-sm text-gray-400">

                                Belum ada ulasan

                            </p>
                        `
                    }

                </div>

            </div>

        </div>

        `;

    });


    document.getElementById('popup-search')
    .addEventListener('keyup', function(){

        const keyword = this.value.toLowerCase();

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
    syncCartUI();

});

// SYNC UI
function syncCartUI(){

    // RESET POPUP BUTTON
    document.querySelectorAll('[id^="add-btn-"]')
    .forEach(btn => {

        let id = btn.id.replace('add-btn-', '');

        let counter =
            document.getElementById(
                'counter-' + id
            );

        if(cart[id]){

            btn.classList.add('hidden');

            if(counter){

                counter.classList.remove('hidden');
                counter.classList.add('flex');

                let qty =
                    document.getElementById(
                        'qty-' + id
                    );

                if(qty){

                    qty.innerText =
                        cart[id].qty;

                }

            }

        } else {

            btn.classList.remove('hidden');

            if(counter){

                counter.classList.remove('flex');
                counter.classList.add('hidden');

            }

        }

    });

    // RESET GALERI
    document.querySelectorAll('[id^="gallery-add-btn-"]')
    .forEach(btn => {

        let id =
            btn.id.replace(
                'gallery-add-btn-',
                ''
            );

        let counter =
            document.getElementById(
                'gallery-counter-' + id
            );

        if(cart[id]){

            btn.classList.add('hidden');

            if(counter){

                counter.classList.remove('hidden');
                counter.classList.add('flex');

                let qty =
                    document.getElementById(
                        'gallery-qty-' + id
                    );

                if(qty){

                    qty.innerText =
                        cart[id].qty;

                }

            }

        } else {

            btn.classList.remove('hidden');

            if(counter){

                counter.classList.remove('flex');
                counter.classList.add('hidden');

            }

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

        // POPUP
        let addBtn =
            document.getElementById(
                'add-btn-' + id
            );

        let counter =
            document.getElementById(
                'counter-' + id
            );

        if(addBtn){

            addBtn.classList.remove('hidden');

        }

        if(counter){

            counter.classList.remove('flex');
            counter.classList.add('hidden');

        }

        // GALERI
        let galleryAddBtn =
            document.getElementById(
                'gallery-add-btn-' + id
            );

        let galleryCounter =
            document.getElementById(
                'gallery-counter-' + id
            );

        if(galleryAddBtn){

            galleryAddBtn.classList.remove('hidden');

        }

        if(galleryCounter){

            galleryCounter.classList.remove('flex');
            galleryCounter.classList.add('hidden');

        }

    }

    updateCartBar();
    syncCartUI();

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

// CLOSE IMAGE
document.getElementById('imageModal')
.addEventListener('click', function(){

    this.classList.remove('flex');
    this.classList.add('hidden');

});

</script>

@endsection