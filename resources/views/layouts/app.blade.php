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
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Memastikan halaman tidak ada scroll horizontal */
        html, body { overflow-x: hidden; } 
    </style>
</head>
<body class="antialiased bg-white">
    
    <!-- Navbar Transparan (Hanya untuk halaman depan) -->
    <nav class="absolute top-0 left-0 right-0 z-50 py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center gap-2 text-white font-bold text-xl tracking-tight cursor-pointer">
                    <svg class="w-6 h-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    Horizon
                </div>
                
                <!-- Menu Tengah (Sembunyikan di HP) -->
                <div class="hidden md:flex space-x-8 text-sm font-medium text-white/90">
                    <a href="/" class="text-white font-semibold border-b-2 border-white pb-1">Beranda</a>
                    <a href="#" class="hover:text-white transition">Mobil Kami</a>
                    <a href="#" class="hover:text-white transition">Cara Sewa</a>
                    <a href="#" class="hover:text-white transition">Tentang Kami</a>
                </div>

                <!-- Menu Kanan -->
                <div class="flex items-center space-x-3 md:space-x-4 text-sm font-medium text-white">
                    <!-- Tombol Bahasa -->
                    <div class="hidden md:flex items-center gap-1 bg-white/20 px-3 py-1.5 rounded-full backdrop-blur-sm cursor-pointer hover:bg-white/30 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        <span class="text-xs font-semibold">EN</span>
                    </div>
                    
                <!-- Menu Kanan (Auth) -->
                <div class="flex items-center space-x-3 md:space-x-6">
                    @auth
                        <!-- Cek Role: Jika Admin, tampilkan Dashboard -->
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-white-600 hover:text-black transition">Dashboard</a>
                        @endif

                        <!-- Tombol Logout dengan Ikon SVG (Warna Abu-abu Elegan) -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-sm font-medium text-white-500 hover:text-black transition">
                                <!-- Ikon Logout SVG -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Log out</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-white-600 hover:text-black">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-white text-gray-900 px-5 py-2 rounded-lg font-semibold hover:bg-gray-100 transition shadow-sm text-sm">Sign Up</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main>
        @yield('content')
    </main>

</body>
</html>