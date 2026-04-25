@extends('layouts.customer')

@section('content')

<style>
.leaflet-container {
    z-index: 0 !important;
}

#suggestions {
    right: 56px;
}
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="p-6 max-w-4xl mx-auto">

    <h1 class="text-2xl font-semibold mb-6">
        Checkout
    </h1>

    <form action="{{ route('checkout.store') }}" method="POST">
    @csrf
    <input type="hidden" name="ongkir" id="ongkirInput">
    <input type="hidden" name="total_harga" id="totalInput">

    <div class="bg-white rounded-xl shadow border p-6 space-y-6">

        <div class="space-y-4">

            <div>
                <label class="text-sm">Nama<span class="text-red-500">*</span></label>
                <input type="text" name="nama" required class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="text-sm">No. Handphone<span class="text-red-500">*</span></label>
                <input type="text" name="no_hp" required class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- ALAMAT -->
            <div>
                <label class="text-sm">Alamat<span class="text-red-500">*</span></label>

                <div class="relative z-50">

                    <div class="flex items-center gap-2 relative">
                        <input type="text" id="alamat" name="alamat" required class="flex-1 border rounded-lg px-3 py-2
                            placeholder="Masukkan alamat di Samarinda...>

                        <button type="button"
                            onclick="refreshLocation()"
                            class="border rounded-lg p-2 text-gray-500 hover:text-red-500 hover:border-red-400">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div id="suggestions"
                        class="absolute left-0 top-full mt-1 w-full bg-white border rounded-lg shadow hidden max-h-60 overflow-auto z-[9999]">
                    </div>

                </div>

                <!-- MAP -->
                <div class="mt-3">
                    <div id="map" style="height:260px; width:100%; border-radius:12px;"></div>
                </div>
            </div>

            <div>
                <label class="text-sm">Tanggal Kirim<span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_kirim" id="tanggal_kirim" required class="w-full border rounded-lg px-3 py-2">
            </div>

        </div>

        <div>
            <p class="font-semibold mb-2">Metode Pembayaran</p>

            <div class="flex gap-4">
                <label><input type="radio" name="payment" value="QRIS" required> QRIS</label>
                <label><input type="radio" name="payment" value="COD" required> COD</label>
            </div>
        </div>

        <div>
            <p class="font-semibold mb-2">Metode Pengambilan</p>

            <div class="flex gap-4">
                <label><input type="radio" name="delivery_type" value="delivery" checked> Delivery</label>
                <label><input type="radio" name="delivery_type" value="pickup"> Pick Up</label>
            </div>
        </div>

        <div class="border-t pt-4">

            <div class="bg-white border rounded-xl p-4 space-y-3">

                <p class="font-semibold text-gray-700">Pesanan</p>

                @foreach(session('cart', []) as $item)
                <div class="flex justify-between items-center text-sm">

                    <div>
                        <p class="font-medium text-gray-800">
                            {{ $item['nama_produk'] }}
                        </p>
                        <p class="text-xs text-gray-400">
                            x{{ $item['qty'] }}
                        </p>
                    </div>

                    <div class="font-semibold text-gray-700">
                        Rp {{ number_format($item['harga'] * $item['qty'],0,',','.') }}
                    </div>

                </div>
                @endforeach

            </div>

        </div>

        <div class="border-t pt-4">

            <div class="bg-gray-50 rounded-xl p-4 space-y-3">

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Harga Produk</span>
                    <span class="font-medium">
                        Rp {{ number_format(array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], session('cart', []))),0,',','.') }}
                    </span>
                </div>

                <div class="flex justify-between text-sm" id="ongkirWrapper">
                    <span class="text-gray-500">Biaya Pengiriman</span>
                    <span id="ongkir" class="font-medium">
                        Rp 0
                    </span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Biaya Admin</span>
                    <span class="font-medium">
                        Rp 1.000
                    </span>
                </div>

            </div>

        </div>
        
        <div class="mt-4">

            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex justify-between items-center">

                <span class="font-semibold text-gray-700">
                    Total Pembayaran
                </span>

                <span id="totalAkhir" class="text-lg font-bold text-red-500">
                    Rp {{ number_format(array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], session('cart', []))),0,',','.') }}
                </span>

            </div>

        </div>
        

        <button 
            type="submit"
            onclick="this.disabled=true; this.innerText='Memproses...'; this.form.submit();"
            class="w-full bg-red-400 text-white py-3 rounded-lg">
            Bayar Sekarang
        </button>

    </div>

</form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
let map = L.map('map').setView([-0.5022, 117.1536], 13);
let marker;

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'OSM'
}).addTo(map);

// FIX map blank
setTimeout(() => map.invalidateSize(), 300);

// AUTO GPS
navigator.geolocation.getCurrentPosition(pos => {
    let lat = pos.coords.latitude;
    let lng = pos.coords.longitude;

    map.setView([lat, lng], 16);
    setMarker(lat, lng);
});

// CLICK MAP
map.on('click', function(e) {
    setMarker(e.latlng.lat, e.latlng.lng);
});

// REFRESH GPS
function refreshLocation() {

    if (!navigator.geolocation) {
        alert("GPS tidak tersedia");
        return;
    }

    navigator.geolocation.getCurrentPosition(pos => {
        let lat = pos.coords.latitude;
        let lng = pos.coords.longitude;

        map.setView([lat, lng], 16);
        setMarker(lat, lng);
    });
}

// 🔥 FIX UTAMA (ALAMAT FULL)
function setMarker(lat, lng) {

    currentLat = lat;
    currentLng = lng;

    updateOngkir();

    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng]).addTo(map);
    
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&addressdetails=1&lat=${lat}&lon=${lng}`)
        .then(res => res.json())
        .then(data => {

            let a = data.address;

            let city = a.city || a.town || a.county || '';

            if (!city.toLowerCase().includes('samarinda')) {
                alert('Lokasi harus di Samarinda!');
                return;
            }

            // 🔥 FIX LEBIH LENGKAP
            let jalan = (
                a.road ||
                a.residential ||
                a.pedestrian ||
                a.footway ||
                a.neighbourhood ||
                a.hamlet ||
                ''
            )
            .replace(/^jalan\s+/i, '')
            .replace(/^jl\.?\s+/i, '')
            .trim();

            let kelurahan = a.suburb || a.village || a.hamlet || a.neighbourhood || '';
            let kecamatan = (
                a.city_district ||
                a.suburb ||
                a.town ||
                a.county ||
                a.state_district ||
                ''
            );
            let kota = 'Samarinda';
            let kodepos = a.postcode || '';

            let alamat = [
                jalan ? `Jl. ${jalan}` : '',
                kelurahan,
                kecamatan,
                kota,
                kodepos
            ].filter(Boolean).join(', ');

            document.getElementById('alamat').value = alamat || 'Lokasi tidak lengkap';
        });
}

/* AUTOCOMPLETE */
let debounceTimer;
const input = document.getElementById('alamat');
const suggestionBox = document.getElementById('suggestions');

input.addEventListener('input', function () {

    clearTimeout(debounceTimer);

    let query = this.value;

    if (query.length < 3) {
        suggestionBox.classList.add('hidden');
        return;
    }

    debounceTimer = setTimeout(() => {

        fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&q=${query}, Samarinda&limit=5`)
            .then(res => res.json())
            .then(data => {

                suggestionBox.innerHTML = '';

                data.forEach(item => {

                    if (!item.display_name.toLowerCase().includes('samarinda')) return;

                    let div = document.createElement('div');
                    div.className = "px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm";

                    let a = item.address || {};

                    let jalan = a.road || '';
                    let kelurahan = a.suburb || a.village || '';
                    let kecamatan = a.city_district || a.town || '';
                    let kodepos = a.postcode || '';

                    let formatted = [
                        jalan,
                        kelurahan,
                        kecamatan,
                        kodepos
                    ].filter(Boolean).join(', ');

                    div.innerText = formatted;

                    div.onclick = () => {

                        input.value = formatted;
                        suggestionBox.classList.add('hidden');

                        map.setView([item.lat, item.lon], 16);

                        if (marker) map.removeLayer(marker);
                        marker = L.marker([item.lat, item.lon]).addTo(map);
                    };

                    suggestionBox.appendChild(div);
                });

                suggestionBox.classList.remove('hidden');
            });

    }, 400);
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('#alamat') && !e.target.closest('#suggestions')) {
        suggestionBox.classList.add('hidden');
    }
});

lucide.createIcons();

const inputTanggal = document.getElementById('tanggal_kirim');

let today = new Date();
let minDate = new Date();
minDate.setDate(today.getDate() + 2);

let yyyy = minDate.getFullYear();
let mm = String(minDate.getMonth() + 1).padStart(2, '0');
let dd = String(minDate.getDate()).padStart(2, '0');

inputTanggal.min = `${yyyy}-${mm}-${dd}`;

['nama','no_hp','alamat'].forEach(id => {
    const el = document.querySelector(`[name=${id}]`);

    if(localStorage.getItem(id)){
        el.value = localStorage.getItem(id);
    }

    el.addEventListener('input', () => {
        localStorage.setItem(id, el.value);
    });
});

// 🔥 KOORDINAT TOKO
const tokoLat = -0.4866;
const tokoLng = 117.1661;

// 🔥 HITUNG JARAK (HAVERSINE)
function hitungJarak(lat1, lon1, lat2, lon2) {
    let R = 6371;
    let dLat = (lat2 - lat1) * Math.PI / 180;
    let dLon = (lon2 - lon1) * Math.PI / 180;

    let a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) *
        Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon/2) * Math.sin(dLon/2);

    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// 🔥 STATE
let currentLat = null;
let currentLng = null;

// 🔥 ONGKIR ZONASI (FINAL)
function hitungOngkir(jarak) {

    if (jarak <= 1) return 0;
    if (jarak <= 3) return 5000;
    if (jarak <= 5) return 9000;
    if (jarak <= 8) return 13000;
    if (jarak <= 12) return 17000;
    if (jarak <= 15) return 21000;

    return 25000;
}

// 🔥 UPDATE ONGKIR
function updateOngkir() {

    let deliveryType = document.querySelector('input[name="delivery_type"]:checked').value;

    let harga = {{ array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], session('cart', []))) }};
    let admin = 1000;
    let ongkir = 0;

    if (deliveryType === 'delivery' && currentLat && currentLng) {

        let jarak = hitungJarak(tokoLat, tokoLng, currentLat, currentLng);

        // 🔥 FIX: KALIBRASI AGAR MIRIP GOOGLE MAPS
        jarak = jarak * 1.5;

        // 🔥 BULATKAN BIAR STABIL
        jarak = Math.round(jarak * 10) / 10;

        // 🔥 DEBUG (LIAT DI CONSOLE)
        console.log("Jarak setelah kalibrasi:", jarak);

        ongkir = hitungOngkir(jarak);
    }

    document.getElementById('ongkir').innerText = 'Rp ' + ongkir.toLocaleString('id-ID');

    let total = harga + ongkir + admin;

    document.getElementById('totalAkhir').innerText = 'Rp ' + total.toLocaleString('id-ID');

    document.getElementById('ongkirWrapper').style.display =
        deliveryType === 'pickup' ? 'none' : 'flex';

    document.getElementById('ongkirInput').value = ongkir;
    document.getElementById('totalInput').value = total;
}

// 🔥 LISTENER RADIO
document.querySelectorAll('input[name="delivery_type"]').forEach(el => {
    el.addEventListener('change', updateOngkir);
});

document.addEventListener("DOMContentLoaded", function () {
    updateOngkir();
});
</script>

@endsection