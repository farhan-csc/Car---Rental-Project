<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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
    <nav class="absolute top-0 left-0 right-0 z-50 py-5" x-data="{ open: false, activeMenu: 'beranda' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center gap-2 text-white font-bold text-xl tracking-tight cursor-pointer transition-transform hover:scale-105">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    Horizon
                </div>
                
                <!-- Menu Tengah (Desktop) -->
                <div class="hidden md:flex space-x-8 text-sm font-medium text-white/90">
                    <a href="/" @click="activeMenu = 'beranda'" @mouseenter="activeMenu = 'beranda'" class="relative py-1 transition-all duration-300 hover:text-white" :class="activeMenu === 'beranda' ? 'text-white' : ''">
                        Beranda
                        <span x-show="activeMenu === 'beranda'" class="absolute bottom-0 left-0 w-full h-0.5 bg-white transition-all duration-300"></span>
                    </a>
                    <a href="#mobil-kami" @click="activeMenu = 'mobil'" @mouseenter="activeMenu = 'mobil'" class="relative py-1 transition-all duration-300 hover:text-white" :class="activeMenu === 'mobil' ? 'text-white' : ''">
                        Mobil Kami
                        <span x-show="activeMenu === 'mobil'" class="absolute bottom-0 left-0 w-full h-0.5 bg-white transition-all duration-300"></span>
                    </a>
                    <a href="#cara-sewa" @click="activeMenu = 'cara'" @mouseenter="activeMenu = 'cara'" class="relative py-1 transition-all duration-300 hover:text-white" :class="activeMenu === 'cara' ? 'text-white' : ''">
                        Cara Sewa
                        <span x-show="activeMenu === 'cara'" class="absolute bottom-0 left-0 w-full h-0.5 bg-white transition-all duration-300"></span>
                    </a>
                    <a href="#tentang-kami" @click="activeMenu = 'tentang'" @mouseenter="activeMenu = 'tentang'" class="relative py-1 transition-all duration-300 hover:text-white" :class="activeMenu === 'tentang' ? 'text-white' : ''">
                        Tentang Kami
                        <span x-show="activeMenu === 'tentang'" class="absolute bottom-0 left-0 w-full h-0.5 bg-white transition-all duration-300"></span>
                    </a>
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
            <a href="#mobil-kami" class="hover:text-gray-300 transition-transform hover:translate-x-2">Mobil Kami</a>
            <a href="#cara-sewa" class="hover:text-gray-300 transition-transform hover:translate-x-2">Cara Sewa</a>
            <a href="#tentang-kami" class="hover:text-gray-300 transition-transform hover:translate-x-2">Tentang Kami</a>
            
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

    <!-- Footer -->
    <footer class="bg-black text-white pt-16 pb-8 mt-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                
                <!-- Kolom 1: Info Perusahaan -->
                <div>
                    <div class="flex items-center gap-2 font-bold text-xl tracking-tight mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        Horizon
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Our mission is to equip modern explorers with cutting-edge, functional, and stylish bags that elevate every adventure.
                    </p>
                    <p class="text-gray-500 text-xs">&copy;{{ date('Y') }} Horizon. All rights reserved.</p>
                </div>

                <!-- Kolom 2: About -->
                <div>
                    <h4 class="font-bold mb-4 text-sm uppercase tracking-wider">About</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Career</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Support -->
                <div>
                    <h4 class="font-bold mb-4 text-sm uppercase tracking-wider">Support</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Return</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Newsletter & Sosmed -->
                <div>
                    <h4 class="font-bold mb-4 text-sm uppercase tracking-wider">Get Updates</h4>
                    
                    <!-- Form Newsletter (UI Saja) -->
                    <div class="flex bg-gray-800 rounded-lg p-1 mb-6">
                        <input type="email" placeholder="Enter your email" class="bg-transparent border-none text-sm text-white w-full px-3 focus:ring-0 placeholder-gray-500">
                        <button class="bg-white text-black px-4 py-2 rounded-md text-xs font-bold hover:bg-gray-200 transition">Subscribe</button>
                    </div>

                    <!-- Ikon Media Sosial -->
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Garis Bawah & Kebijakan -->
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
                <p class="mb-2 md:mb-0">©{{ date('Y') }} Horizon. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>