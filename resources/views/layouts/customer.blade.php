<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Macabliss</title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('favicon.png') }}"
    >

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    >

    <!-- ICON -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="font-sans antialiased bg-gray-100">

    {{-- HEADER HANYA MUNCUL JIKA BUKAN HALAMAN LOGIN --}}
    @if(
        !request()->routeIs('customer.showLogin')
        &&
        Auth::guard('customer')->check()
        &&
        Auth::guard('customer')->user()->role == 'customer'
    )

        <header class="bg-white border-b shadow-sm sticky top-0 z-50">

            <div class="max-w-6xl mx-auto px-3 py-3">

                <div
                    class="flex
                           flex-col
                           sm:flex-row
                           sm:items-center
                           sm:justify-between
                           gap-3"
                >

                    <!-- LOGO -->
                    <div
                        class="flex
                               items-center
                               justify-center
                               sm:justify-start
                               gap-2"
                    >

                        <i
                            data-lucide="cookie"
                            class="w-5 h-5 text-red-500"
                        ></i>

                        <span
                            class="text-base
                                   font-semibold
                                   text-gray-800"
                        >
                            Macabliss
                        </span>

                    </div>


                    <!-- NAV -->
                    <div
                        class="flex
                               flex-wrap
                               items-center
                               justify-center
                               gap-2
                               gap-y-2
                               text-[11px]
                               sm:text-[13px]
                               font-medium"
                    >

                        <!-- BERANDA -->
                        <a
                            href="{{ route('customer.beranda') }}"
                            class="flex items-center gap-1 transition whitespace-nowrap
                            {{
                                request()->routeIs('customer.beranda')
                                ? 'text-red-500'
                                : 'text-gray-500 hover:text-red-500'
                            }}"
                        >

                            <i
                                data-lucide="home"
                                class="w-4 h-4"
                            ></i>

                            <span>Beranda</span>

                        </a>


                        <!-- KERANJANG -->
                        <a
                            href="{{ route('customer.pesanan') }}"
                            class="flex items-center gap-1 transition whitespace-nowrap
                            {{
                                request()->routeIs('customer.pesanan')
                                ? 'text-red-500'
                                : 'text-gray-500 hover:text-red-500'
                            }}"
                        >

                            <i
                                data-lucide="shopping-cart"
                                class="w-4 h-4"
                            ></i>

                            <span>Keranjang</span>

                        </a>


                        <!-- PEMBAYARAN -->
                        <a
                            href="{{ route('customer.pembayaran') }}"
                            class="flex items-center gap-1 transition whitespace-nowrap
                            {{
                                request()->routeIs('customer.pembayaran')
                                ? 'text-red-500'
                                : 'text-gray-500 hover:text-red-500'
                            }}"
                        >

                            <i
                                data-lucide="credit-card"
                                class="w-4 h-4"
                            ></i>

                            <span>Bayar</span>

                        </a>


                        <!-- RIWAYAT -->
                        <a
                            href="{{ route('customer.pesananSaya') }}"
                            class="flex items-center gap-1 transition whitespace-nowrap
                            {{
                                request()->routeIs('customer.pesananSaya')
                                ? 'text-red-500'
                                : 'text-gray-500 hover:text-red-500'
                            }}"
                        >

                            <i
                                data-lucide="package"
                                class="w-4 h-4"
                            ></i>

                            <span>Riwayat</span>

                        </a>


                        <!-- LOGOUT -->
                        <form
                            id="logoutForm"
                            method="POST"
                            action="{{ route('customer.logout') }}"
                        >

                            @csrf

                            <button
                                type="button"
                                onclick="openLogoutModal()"
                                class="
                                    flex
                                    items-center
                                    gap-1
                                    whitespace-nowrap
                                    text-gray-500
                                    hover:text-red-500
                                    transition
                                "
                            >

                                <i
                                    data-lucide="log-out"
                                    class="w-4 h-4"
                                ></i>

                                <span>Logout</span>

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>

    @endif


    <!-- CONTENT -->
    @if(request()->routeIs('customer.showLogin'))

        <main class="min-h-screen">

            @yield('content')

        </main>

    @else

        <main
            class="
                max-w-5xl
                mx-auto
                px-4
                sm:px-6
                lg:px-8
                pt-6
                pb-10
            "
        >

            @yield('content')

        </main>

    @endif


    <!-- MODAL LOGOUT -->
    <div
        id="logoutModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50"
    >

        <div
            class="bg-white rounded-2xl shadow-lg w-80 p-6 text-center"
        >

            <i
                data-lucide="log-out"
                class="w-10 h-10 mx-auto text-red-500 mb-3"
            ></i>

            <h2 class="text-lg font-semibold mb-2">
                Logout
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Yakin ingin keluar dari akun?
            </p>

            <div class="flex justify-center gap-3">

                <button
                    onclick="closeLogoutModal()"
                    class="px-4 py-2 border rounded-xl hover:bg-gray-100"
                >
                    Batal
                </button>

                <button
                    onclick="submitLogout()"
                    class="px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600"
                >
                    Logout
                </button>

            </div>

        </div>

    </div>


    <!-- SCRIPT -->
    <!-- FLOATING CHAT BUTTON -->
    @if(
        Auth::guard('customer')->check()
        &&
        Auth::guard('customer')->user()->role == 'customer'
    )

        <div class="fixed bottom-4 right-4 z-50 group">

    <div
        class="
            absolute
            right-14
            top-1/2
            -translate-y-1/2

            bg-gray-900
            text-white
            text-sm

            px-3
            py-2

            rounded-xl
            shadow-lg

            opacity-0
            invisible

            group-hover:opacity-100
            group-hover:visible

            transition
            duration-300

            whitespace-nowrap
        "
    >

         Chat Admin

    </div>

    <a
        href="{{ route('customer.chat') }}"
        class="
            w-10
            h-10

            bg-red-500
            hover:bg-red-600

            text-white

            rounded-full
            shadow-xl

            flex
            items-center
            justify-center

            transition
            duration-300

            hover:scale-110
        "
    >

        <i
            data-lucide="messages-square"
            class="w-6 h-6"
        ></i>

    </a>

</div>

    @endif
    <script>

        lucide.createIcons();

        function openLogoutModal() {

            document
                .getElementById('logoutModal')
                .classList
                .remove('hidden');

            document
                .getElementById('logoutModal')
                .classList
                .add('flex');

        }

        function closeLogoutModal() {

            document
                .getElementById('logoutModal')
                .classList
                .remove('flex');

            document
                .getElementById('logoutModal')
                .classList
                .add('hidden');

        }

        function submitLogout() {

            document
                .getElementById('logoutForm')
                .submit();

        }

    </script>

</body>
</html>