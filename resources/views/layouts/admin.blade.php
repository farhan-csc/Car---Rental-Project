<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Horizon Car Rental</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Admin -->
        <aside class="w-20 lg:w-64 bg-white border-r border-gray-100 hidden lg:flex flex-col transition-all duration-300 z-20">
            <!-- Logo -->
            <div class="h-20 flex items-center justify-center lg:justify-start lg:px-8 border-b border-gray-50">
                <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white font-bold text-xl shrink-0">H</div>
                <span class="hidden lg:block ml-3 text-xl font-bold tracking-tight text-gray-900">Horizon</span>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-3 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center lg:justify-start gap-4 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-black' : 'text-gray-400 hover:text-black hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="hidden lg:block font-medium text-sm">Dashboard</span>
                </a>

                <a href="{{ route('admin.bookings.index') }}" class="flex items-center justify-center lg:justify-start gap-4 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-100 text-black' : 'text-gray-400 hover:text-black hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span class="hidden lg:block font-medium text-sm">Pesanan Masuk</span>
                </a>

                <a href="{{ route('admin.cars.index') }}" class="flex items-center justify-center lg:justify-start gap-4 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.cars.*') ? 'bg-gray-100 text-black' : 'text-gray-400 hover:text-black hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span class="hidden lg:block font-medium text-sm">Kelola Mobil</span>
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center justify-center lg:justify-start gap-4 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.settings') ? 'bg-gray-100 text-black' : 'text-gray-400 hover:text-black hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="hidden lg:block font-medium text-sm">Pengaturan</span>
                </a>

                <a href="{{ route('admin.banks.index') }}" class="flex items-center justify-center lg:justify-start gap-4 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.banks.*') ? 'bg-gray-100 text-black' : 'text-gray-400 hover:text-black hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    <span class="hidden lg:block font-medium text-sm">Rekening Bank</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center lg:justify-start gap-4 px-3 py-3 w-full rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="hidden lg:block font-medium text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Bottom Navigation (Hanya Mobile) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 h-16 bg-white border-t border-gray-200 flex justify-around items-center z-50">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('admin.dashboard') ? 'text-black' : 'text-gray-400' }}">
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="text-[10px] font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('admin.bookings.*') ? 'text-black' : 'text-gray-400' }}">
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="text-[10px] font-medium">Pesanan</span>
            </a>
            <a href="{{ route('admin.cars.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('admin.cars.*') ? 'text-black' : 'text-gray-400' }}">
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="text-[10px] font-medium">Mobil</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full h-full">
                @csrf
                <button type="submit" class="flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-red-500">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="text-[10px] font-medium">Keluar</span>
                </button>
            </form>
        </nav>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto pb-16 md:pb-0">
            <!-- Topbar -->
            <header class="bg-white h-20 flex items-center justify-between px-8 flex-shrink-0">
                <div>
                    @yield('header') <!-- Ini akan memanggil sapaan Good Morning -->
                </div>
                <div class="flex items-center gap-4">
                    <!-- Search Bar Dummy -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-gray-200 w-64">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    
                    <!-- Ikon Notifikasi & Profil -->
                    <div class="flex items-center gap-3 border-l border-gray-100 pl-4">
                        <button class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </button>
                        <div class="flex items-center gap-2 cursor-pointer">
                            <div class="w-9 h-9 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold text-gray-600">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 md:p-8 flex-1 pb-24 lg:pb-8">
                <!-- Notifikasi -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>