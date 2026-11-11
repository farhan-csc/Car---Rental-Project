@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
    
    <!-- Tombol Kembali -->
    <a href="/" class="text-sm font-semibold text-gray-500 hover:text-black mb-6 inline-block">&larr; Kembali ke Beranda</a>

    <!-- Pesan Error Bentrok -->
    @if ($errors->has('bentrok'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Gagal!</strong>
            <span class="block sm:inline">{{ $errors->first('bentrok') }}</span>
        </div>
    @endif

    <!-- Pesan Sukses -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        
        <!-- Bagian Kiri: Gambar Mobil -->
        <div class="bg-gray-50 rounded-3xl p-8 flex items-center justify-center h-[450px] border border-gray-100 shadow-sm relative">
            <span class="absolute top-6 left-6 bg-white text-gray-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm uppercase tracking-widest z-10">
                {{ $car->brand }}
            </span>
            @if($car->photo_path)
                <img src="{{ asset('storage/' . $car->photo_path) }}" alt="{{ $car->name }}" class="w-full h-full object-contain drop-shadow-2xl">
            @else
                <!-- Pastikan file mobil.png ada di public/images/ -->
                <img src="{{ asset('images/toyota_alphart.png') }}" alt="{{ $car->name }}" class="w-full h-full object-contain drop-shadow-2xl">
            @endif
        </div>

        <!-- Bagian Kanan: Detail & Form -->
        <div class="flex flex-col justify-center">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">{{ $car->name }}</h1>
            
            <div class="flex flex-wrap gap-3 mb-6 text-sm font-medium text-gray-600">
                <!-- Ikon Transmisi (Gear) -->
                <span class="flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-lg">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>{{ ucfirst($car->transmission) }}</span>
                </span>

                <!-- Ikon Kursi (User) -->
                <span class="flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-lg">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>{{ $car->seats }} Kursi</span>
                </span>

                <!-- Ikon Kalender (Calendar) -->
                <span class="flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-lg">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>{{ $car->year }}</span>
                </span>
            </div>

            <p class="text-gray-500 mb-8 leading-relaxed text-sm md:text-base">{{ $car->description }}</p>

            <!-- Form Booking -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Form Pemesanan</h3>
                
                <form action="{{ route('booking.store', $car->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Ambil</label>
                            <input type="datetime-local" name="pickup_at" id="pickup_at" class="w-full border-gray-300 rounded-lg focus:ring-black focus:border-black text-sm p-2.5" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Kembali</label>
                            <input type="datetime-local" name="return_due_at" id="return_due_at" class="w-full border-gray-300 rounded-lg focus:ring-black focus:border-black text-sm p-2.5" required>
                        </div>
                    </div>

                    <!-- Ringkasan Biaya -->
                    <div class="bg-gray-50 rounded-xl p-5 mb-6 text-sm border border-gray-100">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-500">Harga per hari</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-500">Durasi Sewa</span>
                            <span id="duration_text" class="font-semibold text-gray-900">0 Hari</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3 mt-3">
                            <span class="font-bold text-gray-900">Total Biaya</span>
                            <!-- Mengubah warna biru menjadi hitam -->
                            <span id="total_price" class="font-extrabold text-black text-lg">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-black text-white font-bold py-3.5 rounded-xl hover:bg-gray-800 transition text-sm">
                        Pesan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pricePerDay = {{ $car->price_per_day }};
        const pickupInput = document.getElementById('pickup_at');
        const returnInput = document.getElementById('return_due_at');
        const durationText = document.getElementById('duration_text');
        const totalPriceText = document.getElementById('total_price');

        function calculatePrice() {
            const pickup = new Date(pickupInput.value);
            const returnDate = new Date(returnInput.value);

            if (pickupInput.value && returnInput.value && returnDate > pickup) {
                const diffTime = Math.abs(returnDate - pickup);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const finalDays = diffDays < 1 ? 1 : diffDays;
                const total = finalDays * pricePerDay;

                durationText.innerText = finalDays + ' Hari';
                totalPriceText.innerText = 'Rp ' + total.toLocaleString('id-ID');
            } else {
                durationText.innerText = '0 Hari';
                totalPriceText.innerText = 'Rp 0';
            }
        }

        pickupInput.addEventListener('change', calculatePrice);
        returnInput.addEventListener('change', calculatePrice);
    });
</script>
@endsection
