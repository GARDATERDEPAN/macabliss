<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Macabliss</title>

    <!-- FONT SAMA KAYAK APP -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-100 via-white to-red-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md">

    <!-- CARD -->
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-xl border border-gray-100 p-8 animate-fade">

        <!-- TITLE -->
        <h1 class="text-3xl font-bold text-center text-red-400 mb-2 tracking-wide">
            Macabliss
        </h1>
        <p class="text-center text-gray-400 text-sm mb-6">
            Admin Panel Login
        </p>

        <!-- LOGIN FORM -->
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="text-sm text-gray-600">Email</label>
                <input type="text" name="email"
                    class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-200 
                    focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300
                    transition shadow-sm">
            </div>

            <div class="mb-2">
                <label class="text-sm text-gray-600">Password</label>
                <input type="password" name="password"
                    class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-200 
                    focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300
                    transition shadow-sm">
            </div>

            <div class="text-sm text-gray-500 mb-5 text-center">
                <button type="button" onclick="showReset()" 
                    class="hover:text-red-400 transition">
                    Forgot Password?
                </button>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-red-400 to-red-500 text-white py-2 rounded-lg 
                hover:from-red-500 hover:to-red-600 transition shadow-md hover:shadow-lg">
                Login
            </button>

        </form>

        <!-- RESET FORM -->
        <form id="resetForm" class="hidden">

            <p class="text-center text-red-400 font-semibold mb-5">
                Reset Password
            </p>

            <div class="mb-4">
                <label class="text-sm text-gray-600">New Password</label>
                <input type="password"
                    class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-200 
                    focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300
                    transition shadow-sm">
            </div>

            <div class="mb-4">
                <label class="text-sm text-gray-600">Confirm Password</label>
                <input type="password"
                    class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-200 
                    focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300
                    transition shadow-sm">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-red-400 to-red-500 text-white py-2 rounded-lg 
                hover:from-red-500 hover:to-red-600 transition shadow-md hover:shadow-lg">
                Change Password
            </button>

            <div class="text-center mt-4">
                <button type="button" onclick="showLogin()" 
                    class="text-sm text-gray-500 hover:text-red-400 transition">
                    ← Back
                </button>
            </div>

        </form>

    </div>

</div>

<script>
function showReset() {
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('resetForm').classList.remove('hidden');
}

function showLogin() {
    document.getElementById('resetForm').classList.add('hidden');
    document.getElementById('loginForm').classList.remove('hidden');
}
</script>

<style>
.animate-fade {
    animation: fadeIn 0.4s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

</body>
</html>