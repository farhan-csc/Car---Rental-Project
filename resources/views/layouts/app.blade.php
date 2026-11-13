<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Horizon Car Rental</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- TAMBAHKAN ALPINE.JS UNTUK ANIMASI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html, body { overflow-x: hidden; } 
    </style>
</head>
<body class="antialiased bg-white">
    
    <!-- Navbar Transparan (x-data untuk Alpine) -->
    <nav class="absolute top-0 left-0 right-0 z-50 py-5" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center gap-2 text-white font-bold text-xl tracking-tight cursor-pointer transition-transform hover:scale-105">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    Horizon
                </div>
                
                <!-- Menu Tengah (Desktop) -->
                <div class="hidden md:flex space-x-8 text-sm font-medium text-white/90">
                    <a href="/" class="text-white font-semibold border-b-2 border-white pb-1 hover:scale-105 transition-transform">Beranda</a>
                    <a href="#" class="hover:text-white transition-transform hover:scale-105">Mobil Kami</a>
                    <a href="#" class="hover:text-white transition-transform hover:scale-105">Cara Sewa</a>
                    <a href="#" class="hover:text-white transition-transform hover:scale-105">Tentang Kami</a>
                </div>

                <!-- Menu Kanan (Auth) -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-white hover:text-gray-200 transition hidden md:block">Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-sm font-medium text-white hover:text-gray-200 transition hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span class="hidden md:inline">Log out</span>
                            </button>
                        </form>
                    @else
                        <!-- Log in (Sejajar Sign Up) -->
                        <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-gray-200 transition-transform hover:scale-105">Log in</a>
                        <!-- Sign Up -->
                        <a href="{{ route('register') }}" class="bg-white text-gray-900 px-3 py-1.5 md:px-4 md:py-2 rounded-lg font-semibold hover:bg-gray-100 transition shadow-sm text-xs md:text-sm hover:scale-105 hover:shadow-md">Sign Up</a>
                    @endauth

                    <!-- Tombol Hamburger (HP) dengan Animasi Rotasi -->
                    <button @click="open = !open" class="md:hidden text-white focus:outline-none ml-1 transition-transform duration-300" :class="{ 'rotate-90': open }">
                        <svg x-show="!open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        
        <!-- Menu Mobile Dropdown -->
        <div x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden bg-black/95 backdrop-blur-md absolute top-full left-0 right-0 py-6 px-6 flex flex-col space-y-4 text-white text-base font-medium shadow-xl border-t border-gray-800"
            style="display: none;">
            
            <a href="/" class="hover:text-gray-300 transition-transform hover:translate-x-2">Beranda</a>
            <a href="#" class="hover:text-gray-300 transition-transform hover:translate-x-2">Mobil Kami</a>
            <a href="#" class="hover:text-gray-300 transition-transform hover:translate-x-2">Cara Sewa</a>
            <a href="#" class="hover:text-gray-300 transition-transform hover:translate-x-2">Tentang Kami</a>
            
            @auth
                <div class="h-px bg-gray-700 my-2"></div>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-400 hover:text-blue-300 transition-transform hover:translate-x-2">Dashboard Admin</a>
                @endif
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="text-red-400 hover:text-red-300 transition-transform hover:translate-x-2">Log out</a>
                <form id="logout-form-mobile" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
            @endauth
        </div>
    </nav>

    <!-- Konten Utama -->
    <main>
        @yield('content')
    </main>

</body>
</html>