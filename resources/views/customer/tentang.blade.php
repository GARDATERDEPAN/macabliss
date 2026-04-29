@extends('layouts.customer')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

    <!-- HEADER -->
    <div class="text-center mt-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Tentang Macabliss
        </h1>
        <p class="text-gray-500 mt-2 text-sm sm:text-base">
            Solusi pemesanan kue secara online yang praktis, cepat, dan terpercaya.
        </p>
    </div>

    <!-- 🔥 FOTO MACABLISS -->
    <div class="overflow-hidden rounded-xl shadow">
        <img src="{{ asset('images/macabliss.png') }}" 
             class="w-full h-56 sm:h-72 object-cover hover:scale-105 transition duration-300">
    </div>

    <!-- 🔥 LOKASI -->
    <div class="bg-white p-5 sm:p-6 rounded-xl shadow">

        <h2 class="text-lg sm:text-xl font-semibold mb-4 text-center">
            Lokasi Rumah Produksi
        </h2>

        <div class="flex flex-col md:flex-row items-center gap-5">

            <!-- ICON -->
            <div class="bg-red-100 text-red-500 p-4 rounded-full">
                <i data-lucide="map-pin" class="w-6 h-6"></i>
            </div>

            <!-- TEXT -->
            <div class="text-center md:text-left">
                <h3 class="font-semibold text-gray-800 text-base sm:text-lg">
                    Macabliss
                </h3>

                <p class="text-gray-500 text-sm sm:text-base">
                    Jl. Merdeka Gang Otok No. 54 B, Samarinda
                </p>

                <a href="https://www.google.com/maps?q=-0.4863,117.1665"
                target="_blank"
                class="inline-block mt-2 text-sm text-blue-600 hover:underline">
                    Lihat di Google Maps →
                </a>
            </div>

        </div>

    </div>

    <!-- STORY -->
    <div class="bg-white p-5 sm:p-6 rounded-xl shadow">
        <h2 class="text-lg sm:text-xl text-center font-semibold mb-3">Cerita Kami</h2>
        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
            Macabliss adalah usaha UMKM yang bergerak di bidang penjualan kue seperti soft cookies dan brownies cookies.
            Berawal dari produksi rumahan, Macabliss berkembang menjadi layanan pemesanan online yang memudahkan pelanggan
            dalam melakukan pembelian tanpa harus datang langsung ke toko.
        </p>
    </div>

    <!-- VISI MISI -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div class="bg-white p-5 rounded-xl shadow">
            <h3 class="font-semibold mb-2 text-base text-center sm:text-lg">Visi</h3>
            <p class="text-gray-600 text-sm sm:text-base">
                Menjadi brand kue lokal yang terpercaya dengan kualitas terbaik dan pelayanan digital yang modern.
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <h3 class="font-semibold mb-2 text-base text-center sm:text-lg">Misi</h3>
            <ul class="text-gray-600 list-disc pl-5 space-y-1 text-sm sm:text-base">
                <li>Menyediakan produk berkualitas tinggi</li>
                <li>Memberikan pelayanan cepat dan mudah</li>
                <li>Mengembangkan sistem pemesanan online</li>
                <li>Menjaga kepuasan pelanggan</li>
            </ul>
        </div>

    </div>

    <!-- PRODUK -->
    <div class="bg-white p-5 sm:p-6 rounded-xl shadow">
        <h2 class="text-lg sm:text-xl text-center font-semibold mb-3">Produk Kami</h2>
        <p class="text-gray-600 text-sm sm:text-base mb-4">
            Macabliss menyediakan berbagai pilihan kue seperti:
        </p>

        <!-- 🔥 FOTO PRODUK -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

            <div class="overflow-hidden rounded-xl shadow">
                <img src="{{ asset('images/cookies.jpg') }}" 
                     class="w-full h-40 sm:h-48 object-cover hover:scale-105 transition duration-300">
            </div>

            <div class="overflow-hidden rounded-xl shadow">
                <img src="{{ asset('images/brownies.jpg') }}" 
                     class="w-full h-40 sm:h-48 object-cover hover:scale-105 transition duration-300">
            </div>

        </div>

        <ul class="list-disc pl-5 text-gray-600 text-sm sm:text-base">
            <li>Soft Cookies</li>
            <li>Brownies Cookies</li>
            <li>Dan lain sebagainya...</li>
        </ul>
    </div>

    <!-- FITUR -->
    <div>
        <h2 class="text-lg sm:text-xl font-semibold mb-4 text-center">
            Fitur Aplikasi
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

            <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
                <div class="text-lg">🛒</div>
                <b>Keranjang</b>
                <p class="text-sm text-gray-600 mt-1">
                    Menyimpan produk sebelum checkout
                </p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
                <div class="text-lg">📦</div>
                <b>Pemesanan</b>
                <p class="text-sm text-gray-600 mt-1">
                    Order tanpa datang ke toko
                </p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
                <div class="text-lg">💳</div>
                <b>Pembayaran</b>
                <p class="text-sm text-gray-600 mt-1">
                    Mendukung pembayaran digital
                </p>
            </div>

        </div>
    </div>

</div>
@endsection