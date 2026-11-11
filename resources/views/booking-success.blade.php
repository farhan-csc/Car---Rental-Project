@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 mt-16 text-center">
    
    <!-- Ikon Centang Besar -->
    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Pesanan Berhasil Dibuat!</h1>
    <p class="text-gray-500 mb-8">Terima kasih telah memesan. Dokumen dan bukti transfer Anda sedang dalam proses verifikasi oleh Admin kami.</p>

    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-left mb-8">
        <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-4">
            <span class="text-gray-500 text-sm">Kode Booking</span>
            <span class="font-bold text-lg text-black">{{ $booking->booking_code }}</span>
        </div>
        <div class="flex justify-between items-center mb-2">
            <span class="text-gray-500 text-sm">Status</span>
            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full uppercase">Menunggu Verifikasi</span>
        </div>
        <p class="text-sm text-gray-500 mt-4">Simpan kode booking ini. Anda dapat menghubungi Admin untuk informasi lebih lanjut.</p>
    </div>

    <a href="/" class="inline-block bg-black text-white font-bold py-3 px-8 rounded-xl hover:bg-gray-800 transition">
        Kembali ke Beranda
    </a>
</div>
@endsection