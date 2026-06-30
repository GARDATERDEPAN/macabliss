@php

$unreadChats = \App\Models\Chat::where(
    'sender',
    'customer'
)
->where(
    'is_read',
    false
)
->count();

@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-lg hidden md:block">

        <div class="px-6 py-5 font-bold text-xl border-b flex items-center gap-2">
            <i data-lucide="cookie" class="text-red-500"></i>
            Macabliss
        </div>

        <nav class="p-4 space-y-2">

            {{-- KHUSUS ADMIN --}}
            @if(auth()->user()->role == 'admin')

                <a href="/dashboard"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg
                   {{ request()->is('dashboard') ? 'bg-red-300 text-red-600 font-semibold' : 'hover:bg-red-100' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Dashboard
                </a>

                <a href="/products"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg
                   {{ request()->is('products*') ? 'bg-red-300 text-red-600 font-semibold' : 'hover:bg-red-100' }}">
                    <i data-lucide="box" class="w-4 h-4"></i>
                    Produk
                </a>

                <a href="/categories"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg
                   {{ request()->is('categories*') ? 'bg-red-300 text-red-600 font-semibold' : 'hover:bg-red-100' }}">

                    <i data-lucide="shapes" class="w-4 h-4"></i>
                    Kategori

                </a>

                <a href="/ongkir"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg
                   {{ request()->is('ongkir*') ? 'bg-red-300 text-red-600 font-semibold' : 'hover:bg-red-100' }}">

                    <i data-lucide="route" class="w-4 h-4"></i>
                    Ongkir

                </a>

            @endif


            {{-- ADMIN & KASIR --}}
            @if(in_array(auth()->user()->role, ['admin', 'kasir']))

                <a href="/orders"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg
                   {{ request()->is('orders*') ? 'bg-red-300 text-red-600 font-semibold' : 'hover:bg-red-100' }}">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                    Pesanan
                </a>

                <a href="/payments"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg
                   {{ request()->is('payments*') ? 'bg-red-300 text-red-600 font-semibold' : 'hover:bg-red-100' }}">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    Pembayaran
                </a>

                <a href="{{ route('admin.chat') }}"
                    class="flex items-center justify-between
                    px-4 py-2 rounded-lg
                    {{ request()->is('chat*') ? 'bg-red-300 text-red-600 font-semibold' : 'hover:bg-red-100' }}">

                        <div class="flex items-center gap-2">

                            <i data-lucide="message-circle" class="w-4 h-4"></i>

                            Chat

                        </div>

                        @if($unreadChats > 0)

                            <span
                                class="bg-red-500
                                    text-white
                                    text-xs
                                    min-w-[20px]
                                    h-5
                                    px-1
                                    flex
                                    items-center
                                    justify-center
                                    rounded-full">

                                {{ $unreadChats }}

                            </span>

                        @endif

                    </a>

            @endif

        </nav>

    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <header class="bg-white border-b">

            <div class="px-6 py-3.5 flex justify-end items-center">

                <div class="relative">

                    <!-- BUTTON -->
                    <button onclick="toggleDropdown()"
                        class="flex items-center gap-3 px-2 py-1 rounded-lg hover:bg-gray-100 transition">

                        <div class="w-8 h-8 bg-red-400 text-white flex items-center justify-center rounded-full">

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        </div>

                        <span class="text-sm font-medium text-gray-700">

                            {{ auth()->user()->name }}

                        </span>

                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>

                    </button>

                    <!-- DROPDOWN -->
                    <div id="dropdownMenu"
                        class="hidden absolute right-0 mt-2 w-44 bg-white border rounded-xl shadow-lg z-50 overflow-hidden">

                        <form id="logoutForm"
                              method="POST"
                              action="{{ route('logout') }}"
                              onsubmit="return confirmLogout(event)">

                            @csrf

                            <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-100 transition">

                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                Logout

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>

        <!-- CONTENT -->
        <main class="p-6">
            @yield('content')
        </main>

        <!-- MODAL LOGOUT -->
        <div id="logoutModal"
             class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

            <div class="bg-white rounded-xl shadow-lg w-80 p-6 text-center animate-scale">

                <i data-lucide="log-out"
                   class="w-10 h-10 mx-auto text-red-500 mb-3"></i>

                <h2 class="text-lg font-semibold mb-2">
                    Logout
                </h2>

                <p class="text-sm text-gray-500 mb-4">
                    Yakin ingin keluar dari akun?
                </p>

                <div class="flex justify-center gap-3">

                    <button onclick="closeLogoutModal()"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">

                        Batal

                    </button>

                    <button onclick="submitLogout()"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">

                        Logout

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- INIT ICON -->
<script>
    lucide.createIcons();
</script>

<script>

function toggleDropdown() {

    const menu =
        document.getElementById('dropdownMenu');

    menu.classList.toggle('hidden');

}

// KLIK LUAR = CLOSE
window.addEventListener('click', function(e) {

    const dropdown =
        document.getElementById('dropdownMenu');

    if (
        !e.target.closest('#dropdownMenu') &&
        !e.target.closest('[onclick="toggleDropdown()"]')
    ) {

        dropdown.classList.add('hidden');

    }

});

function confirmLogout(e) {

    e.preventDefault();

    document
        .getElementById('logoutModal')
        .classList.remove('hidden');

    document
        .getElementById('logoutModal')
        .classList.add('flex');

    return false;

}

function closeLogoutModal() {

    document
        .getElementById('logoutModal')
        .classList.add('hidden');

}

function submitLogout() {

    document
        .getElementById('logoutForm')
        .submit();

}

</script>

<style>

.animate-scale {

    animation: scaleIn 0.2s ease;

}

@keyframes scaleIn {

    from {

        transform: scale(0.9);
        opacity: 0;

    }

    to {

        transform: scale(1);
        opacity: 1;

    }

}

</style>

@yield('scripts')

</body>
</html>