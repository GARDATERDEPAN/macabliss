<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Macabliss</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ICON -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

    <!-- HEADER -->
    <header class="bg-white border-b shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 py-3.5 flex items-center justify-between">

            <!-- LOGO -->
            <div class="flex items-center gap-2">
                <i data-lucide="cookie" class="w-5 h-5 text-red-500"></i>
                <span class="text-base font-semibold text-gray-800">
                    Macabliss
                </span>
            </div>

            <!-- NAV -->
            <nav class="flex items-center gap-6 text-sm font-medium ml-4">

                <!-- BERANDA -->
                <a href="{{ route('customer.beranda') }}" 
                   class="flex items-center gap-1 transition
                   {{ request()->routeIs('customer.beranda') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    <span>Beranda</span>
                </a>

                <!-- KERANJANG -->
                <a href="{{ route('customer.pesanan') }}" 
                   class="flex items-center gap-1 transition
                   {{ request()->routeIs('customer.pesanan') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    <span>Keranjang</span>
                </a>

                <!-- RIWAYAT -->
                <a href="{{ route('customer.pesananSaya') }}" 
                   class="flex items-center gap-1 transition
                   {{ request()->routeIs('customer.pesananSaya') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                    <i data-lucide="package" class="w-4 h-4"></i>
                    <span>Riwayat</span>
                </a>

                <!-- PEMBAYARAN -->
                <a href="{{ route('customer.pembayaran') }}" 
                   class="flex items-center gap-1 transition
                   {{ request()->routeIs('customer.pembayaran') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    <span>Bayar</span>
                </a>

                <!--  TENTANG KAMI -->
                <a href="{{ route('customer.tentang') }}" 
                   class="flex items-center gap-1 transition
                   {{ request()->routeIs('customer.tentang') ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <span>Informasi</span>
                </a>

            </nav>

        </div>
    </header>

    <!-- CONTENT -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-10">
        @yield('content')
    </main>

    <!-- SCRIPT -->
    <script>
        lucide.createIcons();
    </script>

</body>
</html>