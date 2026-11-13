@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="relative min-h-[650px] md:h-[650px] flex items-center w-[calc(100%-2rem)] mx-auto mt-4 rounded-xl overflow-hidden shadow-lg">
        <!-- Background Image -->
        <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Car" class="absolute inset-0 w-full h-full object-cover">
        <!-- Overlay Gelap -->
        <div class="absolute inset-0 bg-black/50"></div>
        
        <!-- Konten di atas gambar -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-40 md:mt-32 lg:mt-64 z-10">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 tracking-tight leading-tight md:whitespace-nowrap">
                Rent a Car for <br class="block md:hidden">Every Journey
            </h1>
            <!-- Jarak teks diperlebar agar tidak tertutup tab -->
            <p class="text-base md:text-lg text-gray-200 mb-16 md:mb-12 max-w-xl">Experience the epitome of amazing journey with our top picks.</p>
            
            <!-- Kotak Pencarian Bergaya -->
            <div class="relative bg-white p-2 rounded-xl shadow-2xl flex flex-col md:flex-row gap-2 max-w-4xl">
                <!-- Tabs Sewa -->
                <div class="absolute -top-12 left-0 flex gap-2">
                    <button class="bg-white text-black rounded-t-lg px-6 py-2.5 text-xs font-bold shadow-sm">
                        Lepas Kunci
                    </button>
                    <button class="bg-black/30 backdrop-blur-md text-white rounded-t-lg px-6 py-2.5 text-xs font-medium hover:bg-black/50 transition">
                        Dengan Sopir
                    </button>
                </div>

                <!-- Input 1 -->
                <div class="flex-1 min-w-0 px-4 py-3 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col justify-center">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 whitespace-nowrap">Pick Up Location</label>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <input type="text" placeholder="City, airport or station" class="w-full border-none p-0 text-sm font-medium text-gray-900 focus:ring-0 placeholder-gray-400 bg-transparent outline-none truncate">
                    </div>
                </div>

                <!-- Input 2 -->
                <div class="flex-1 min-w-0 px-4 py-3 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col justify-center">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 whitespace-nowrap">Pick Up Date & Time</label>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <input type="datetime-local" class="w-full border-none p-0 text-sm font-medium text-gray-900 focus:ring-0 bg-transparent outline-none cursor-pointer">
                    </div>
                </div>

                <!-- Input 3 -->
                <div class="flex-1 min-w-0 px-4 py-3 flex flex-col justify-center">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 whitespace-nowrap">Return Date & Time</label>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <input type="datetime-local" class="w-full border-none p-0 text-sm font-medium text-gray-900 focus:ring-0 bg-transparent outline-none cursor-pointer">
                    </div>
                </div>

                <!-- Tombol Search (Diperbaiki: Tidak melar, tapi memenuhi tinggi container) -->
                <div class="p-2 pb-4 md:pb-2 flex items-center justify-center shrink-0">
                    <button class="w-full md:w-auto h-auto md:h-full py-3 md:py-0 bg-black text-white px-8 rounded-lg text-sm font-bold hover:bg-gray-800 transition flex items-center justify-center gap-2 shadow-md">
                        Search
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Picks Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 mt-0">
        <div class="mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Top picks vehicle this month</h2>
            <p class="text-gray-500 mt-2 text-sm">Experience the epitome of amazing journey with our top picks.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @forelse($cars as $car)
                <div class="group cursor-pointer bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <!-- Gambar Mobil -->
                    <div class="h-44 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden mb-4 relative">
                        <span class="absolute top-3 left-3 bg-gray-200/80 backdrop-blur-sm text-gray-700 text-[10px] font-semibold px-3 py-1 rounded-full z-10 uppercase tracking-wide">
                            {{ $car->brand }}
                        </span>
                        
                        @if($car->photo_path)
                            <img src="{{ asset('storage/' . $car->photo_path) }}" alt="{{ $car->name }}" class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                        @else
                            <img src="{{ asset('images/toyota_alphart.png') }}" alt="{{ $car->name }}" class="w-full h-full object-contain group-hover:scale-110 transition duration-300 drop-shadow-md">
                        @endif
                    </div>
                    
                    <!-- Info Mobil -->
                    <div>
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $car->name }}</h3>
                            <span class="text-xs font-semibold text-gray-700 flex items-center gap-1">⭐ 4.8</span>
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-4 flex gap-3 items-center">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>{{ ucfirst($car->transmission) }}</span>
                            </div>
                            <span class="text-gray-300">•</span> 
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span>{{ $car->seats }} Seats</span>
                            </div>
                        </div>
                        
                        <div class="flex items-end justify-between mt-4 border-t border-gray-100 pt-3">
                            <div>
                                <span class="block text-[10px] text-gray-400 font-medium mb-0.5">Start from</span>
                                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($car->price_per_day / 1000, 0, ',', '.') }}k <span class="text-xs font-normal text-gray-500">/ day</span></p>
                            </div>
                            <a href="{{ route('car.detail', $car->id) }}" class="bg-black hover:bg-gray-800 text-white px-5 py-2 rounded-lg text-xs font-bold transition-all duration-300 shadow-sm hover:scale-105 active:scale-95 hover:shadow-md">Sewa</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-10 text-gray-500">
                    Belum ada data mobil. Silakan jalankan Seeder.
                </div>
            @endforelse
        </div>
    </div>
@endsection