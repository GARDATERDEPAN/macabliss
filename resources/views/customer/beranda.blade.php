@extends('layouts.customer')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-10">

        <div>
            <h1 class="text-4xl font-bold text-gray-900">
                Macabliss
            </h1>
        </div>

    </div>

    <!-- KATEGORI -->
    <div class="grid grid-cols-2 gap-10">

        @foreach ($categories as $category)

            <button
                onclick="showCategory({{ $category->id }})"
                class="flex flex-col items-center
                       hover:scale-105 transition duration-300">

                @if($category->gambar && file_exists(public_path('images/' . $category->gambar)))

                    <img src="{{ asset('images/' . $category->gambar) }}"
                         class="w-44 h-44 rounded-full
                                object-cover border-[6px]
                                border-red-100 shadow-xl">

                @else

                    <div class="w-44 h-44 rounded-full
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