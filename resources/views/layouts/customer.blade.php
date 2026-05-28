<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Macabliss</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ICON -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

    <!-- HEADER -->
    <header class="bg-white border-b shadow-sm sticky top-0 z-50">

        <div class="max-w-6xl mx-auto
                    px-3 py-3">

            <!-- WRAPPER -->
            <div class="flex flex-col
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                        gap-3">

                <!-- LOGO -->
                <div class="flex items-center
                            justify-center
                            sm:justify-start
                            gap-1 flex-shrink-0">

                    <i data-lucide="cookie"
                    class="w-5 h-5 text-red-500">

                    </i>

                    <span class="text-base
                                font-semibold
                                text-gray-800">

                        Macabliss

                    </span>

                </div>

                <!-- NAV -->
                <nav class="flex flex-wrap
                            items-center
                            justify-center
                            gap-x-4 gap-y-2
                            text-[11px]
                            sm:text-[13px]
                            font-medium">

                    <!-- BERANDA -->
                    <a href="{{ route('customer.beranda') }}"
                    class="flex items-center
                            gap-1 transition whitespace-nowrap
                    {{ request()->routeIs('customer.beranda')
                            ? 'text-red-500'
                            : 'text-gray-500 hover:text-red-500' }}">

                        <i data-lucide="home"
                        class="w-4 h-4">

                        </i>

                        <span>

                            Beranda

                        </span>

                    </a>

                    <!-- KERANJANG -->
                    <a href="{{ route('customer.pesanan') }}"
                    class="flex items-center
                            gap-1 transition whitespace-nowrap
                    {{ request()->routeIs('customer.pesanan')
                            ? 'text-red-500'
                            : 'text-gray-500 hover:text-red-500' }}">

                        <i data-lucide="shopping-cart"
                        class="w-4 h-4">

                        </i>

                        <span>

                            Keranjang

                        </span>

                    </a>

                    <!-- PEMBAYARAN -->
                    <a href="{{ route('customer.pembayaran') }}"
                    class="flex items-center
                            gap-1 transition whitespace-nowrap
                    {{ request()->routeIs('customer.pembayaran')
                            ? 'text-red-500'
                            : 'text-gray-500 hover:text-red-500' }}">

                        <i data-lucide="credit-card"
                        class="w-4 h-4">

                        </i>

                        <span>

                            Bayar

                        </span>

                    </a>

                    <!-- RIWAYAT -->
                    <a href="{{ route('customer.pesananSaya') }}"
                    class="flex items-center
                            gap-1 transition whitespace-nowrap
                    {{ request()->routeIs('customer.pesananSaya')
                            ? 'text-red-500'
                            : 'text-gray-500 hover:text-red-500' }}">

                        <i data-lucide="package"
                        class="w-4 h-4">

                        </i>

                        <span>

                            Riwayat

                        </span>

                    </a>

                </nav>

            </div>

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