<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrunchyBite - @yield('title', 'Home')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
    <header class="bg-[#FFF1E6] backdrop-blur shadow-md fixed top-0 w-full z-50 border-b border-[#E7D3B0]">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

            <!-- LOGO & BRAND -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <!-- Logo image (kalau ada) -->
                {{-- <img src="/images/logo.png" alt="Cinnameows" class="w-10 h-10"> --}}

                <div class="leading-tight">
                    <h1 class="text-2xl font-bold text-[#5A3E2B] tracking-wide">
                        Cinnameows Bakery
                    </h1>
                    <p class="text-xs text-[#B7794A] italic">
                        Sweet & Delicious
                    </p>

                    </p>
                </div>
            </a>

            <!-- MENU TOGGLE (MOBILE) -->
            <button id="menu-toggle" class="md:hidden text-[#6B4F3F] focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- DESKTOP NAV -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                @auth
                    <!-- RIWAYAT -->
                    <a href="{{ route('orders.index') }}" class="text-[#8B5E34] hover:text-[#C08457] transition"
                        title="Riwayat Pesanan">
                        🧾
                    </a>

                    <!-- CART -->
                    <a href="{{ route('cart.index') }}" class="relative text-[#6B4F3F] hover:text-[#C08457] transition"
                        title="Keranjang">
                        🛒
                        @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                        @if ($cartCount > 0)
                            <span
                                class="absolute -top-2 -right-3 bg-[#C08457] text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- USER -->
                    <span class="text-[#6B4F3F]">
                        Hi, <b>{{ Auth::user()->name }}</b> 🐾
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-500 hover:underline text-sm">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-[#C08457] hover:underline">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-[#C08457] text-white px-4 py-2 rounded-full hover:bg-[#a86a42] transition">
                        Register
                    </a>
                @endauth
            </nav>
        </div>

        <!-- MOBILE MENU -->
        <div id="mobile-menu" class="hidden md:hidden bg-[#FFF7ED] border-t px-4 py-4 space-y-3 text-sm">
            @auth
                <div class="text-[#6B4F3F] font-medium">
                    Hi, {{ Auth::user()->name }} 🐱
                </div>
                <a href="{{ route('orders.index') }}" class="block text-[#6B4F3F]">Riwayat</a>
                <a href="{{ route('cart.index') }}" class="block text-[#6B4F3F]">Keranjang</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-500">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-[#C08457]">Login</a>
                <a href="{{ route('register') }}" class="block text-[#C08457]">Register</a>
            @endauth
        </div>
    </header>


    <!-- Konten Halaman -->
    <main class="flex-grow">
        @yield('content')
    </main>
</body>

</html>