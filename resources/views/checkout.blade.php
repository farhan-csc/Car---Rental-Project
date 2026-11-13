@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
    
    <!-- Notifikasi -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-3xl p-8 shadow-sm">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8 border-b border-gray-100 pb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Selesaikan Pesanan Anda</h1>
                <p class="text-gray-500 text-sm mt-1">Kode Booking: <span class="font-bold text-black">{{ $booking->booking_code }}</span></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Batas Waktu Bayar</p>
                <p class="text-lg font-bold text-red-600">{{ \Carbon\Carbon::parse($booking->payment_deadline)->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>

        <!-- Rincian Biaya -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-8">
            <div class="flex justify-between text-sm mb-2"><span class="text-gray-500">Kendaraan</span><span class="font-semibold">{{ $car->name }}</span></div>
            <div class="flex justify-between text-sm mb-4 border-b border-gray-200 pb-4"><span class="text-gray-500">Durasi</span><span class="font-semibold">{{ $booking->rental_days }} Hari</span></div>
            <div class="flex justify-between text-lg"><span class="font-bold text-gray-900">Total Biaya</span><span class="font-extrabold text-black">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span></div>
        </div>

        <!-- LOGIKA: Jika Belum Punya Data Diri/Nama Tamu, Tampilkan Form Pilihan -->
        @if(!session('payment_selected_' . $booking->id))
        <form action="{{ route('booking.process', $booking->payment_token) }}" method="POST">
            @csrf
            @guest
            <div class="mb-8">
                <h3 class="font-bold text-gray-900 mb-4">Data Diri (Anda belum login)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label><input type="text" name="guest_name" value="{{ old('guest_name') }}" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. WhatsApp</label><input type="text" name="guest_phone" value="{{ old('guest_phone') }}" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label><input type="email" name="guest_email" value="{{ old('guest_email') }}" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                </div>
            </div>
            @endguest

            <div class="mb-8">
                <h3 class="font-bold text-gray-900 mb-4">Pilih Metode Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_option" value="lunas" class="peer sr-only" required>
                        <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-black peer-checked:bg-gray-50 transition">
                            <span class="block font-bold text-gray-900">Bayar Lunas</span>
                            <span class="text-sm text-gray-500">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </label>
                    @auth
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_option" value="dp" class="peer sr-only">
                        <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-black peer-checked:bg-gray-50 transition">
                            <span class="block font-bold text-gray-900">Bayar DP 50%</span>
                            <span class="text-sm text-gray-500">Rp {{ number_format($booking->total_amount * 0.5, 0, ',', '.') }}</span>
                        </div>
                    </label>
                    @endauth
                </div>
            </div>
            <button type="submit" class="w-full bg-black text-white font-bold py-3.5 rounded-xl hover:bg-gray-800 transition">Simpan & Lanjut ke Pembayaran</button>
        </form>
        @endif

        <!-- JIKA SUDAH MEMILIH: Tampilkan Form Upload -->
        @if(session('payment_selected_' . $booking->id))
        <div id="upload-section" class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="font-bold text-gray-900 mb-1">Unggah Dokumen & Bukti Transfer</h3>
            <p class="text-sm text-gray-500 mb-6">Silakan unggah KTP, SIM, dan bukti transfer Anda. Format: JPG, PNG, PDF (Maks. 5MB).</p>

            <!-- Kotak Info Rekening -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <p class="text-sm text-blue-800 font-semibold mb-3">Silakan Transfer ke Salah Satu Rekening Berikut:</p>
                <div class="space-y-3">
                    @foreach($bankAccounts as $bank)
                    <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-blue-100">
                        @if($bank->logo_path)
                            <img src="{{ asset('storage/' . $bank->logo_path) }}" class="w-12 h-8 object-contain">
                        @endif
                        <div>
                            <p class="font-bold text-sm text-gray-900">{{ $bank->bank_name }} - {{ $bank->account_number }}</p>
                            <p class="text-xs text-gray-500">a.n {{ $bank->account_name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-sm text-blue-700 mt-4">Nominal Transfer: 
                    <span class="font-bold">
                        @if($booking->payment_option == 'dp') Rp {{ number_format($booking->dp_amount, 0, ',', '.') }} (DP)
                        @else Rp {{ number_format($booking->total_amount, 0, ',', '.') }} (Lunas) @endif
                    </span>
                </p>
            </div>

            <!-- Form Upload -->
            <form action="{{ route('booking.upload', $booking->payment_token) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Upload KTP</label><input type="file" name="ktp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" required></div>
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Upload SIM</label><input type="file" name="sim" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" required></div>
                    <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bukti Transfer</label><input type="file" name="proof" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" required></div>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition">Kirim Dokumen & Bukti Transfer</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection