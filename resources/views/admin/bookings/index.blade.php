@extends('layouts.admin')

@section('header', 'Daftar Pesanan Masuk')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    
    <!-- Tambahkan overflow-x-auto agar tabel bisa di-scroll ke samping di HP -->
    <div class="overflow-x-auto">
        <!-- Tambahkan min-w-[800px] agar tabel tidak mengecil di HP -->
        <table class="w-full min-w-[800px] text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold">Kode Booking</th>
                    <th class="px-6 py-4 font-semibold">Penyewa</th>
                    <th class="px-6 py-4 font-semibold">Kendaraan</th>
                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Tgl Sewa</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-black whitespace-nowrap">{{ $booking->booking_code }}</td>
                    <td class="px-6 py-4">{{ $booking->guest_name ?? $booking->user->name ?? 'Tamu' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $booking->car->name ?? '-' }}</td>
                    
                    <!-- Format Tanggal Dirapikan -->
                    <td class="px-6 py-4 text-xs whitespace-nowrap text-gray-600">
                        {{ \Carbon\Carbon::parse($booking->pickup_at)->format('d M Y') }} 
                        <span class="text-gray-400 mx-1">&rarr;</span> 
                        {{ \Carbon\Carbon::parse($booking->return_due_at)->format('d M Y') }}
                    </td>
                    
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold whitespace-nowrap
                            @if($booking->status == 'menunggu_verifikasi') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'dikonfirmasi') bg-green-100 text-green-800
                            @elseif($booking->status == 'menunggu_pembayaran') bg-red-100 text-red-800
                            @elseif($booking->status == 'selesai') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-block bg-gray-900 text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-700 transition">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection