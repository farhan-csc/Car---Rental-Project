@extends('layouts.admin')

@section('header')
    <div>
        <h1 class="text-xl font-bold text-gray-900">Hallo, {{ auth()->user()->name }}!</h1>
        <p class="text-sm text-gray-500">Business Manager</p>
    </div>
@endsection

@section('content')
<!-- Kartu Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card 1: Total Pendapatan -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-gray-400">+12%</span>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <p class="text-sm text-gray-500 mt-1">Total Pendapatan</p>
    </div>

    <!-- Card 2: Total Pesanan -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <span class="text-xs font-semibold text-gray-400">+5%</span>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900">{{ $totalBookings }}</h3>
        <p class="text-sm text-gray-500 mt-1">Total Pesanan</p>
    </div>

    <!-- Card 3: Menunggu Verifikasi -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-red-500">Perlu Aksi</span>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900">{{ $pendingVerifications }}</h3>
        <p class="text-sm text-gray-500 mt-1">Menunggu Verifikasi</p>
    </div>
</div>

<!-- Grafik Placeholder (Untuk memiripkan desain referensi) -->
<div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm mb-8">
    
    <!-- Grafik Pendapatan Dinamis -->
<div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-lg text-gray-900">Grafik Transaksi</h3>
        
        <!-- Dropdown Filter -->
        <form action="{{ route('admin.dashboard') }}" method="GET">
            <select name="filter" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-lg bg-gray-50 text-gray-600 focus:ring-0 cursor-pointer">
                <option value="5days" {{ $filter == '5days' ? 'selected' : '' }}>5 Hari Terakhir</option>
                <option value="1week" {{ $filter == '1week' ? 'selected' : '' }}>1 Minggu Terakhir</option>
                <option value="1month" {{ $filter == '1month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                <option value="1year" {{ $filter == '1year' ? 'selected' : '' }}>1 Tahun Terakhir</option>
            </select>
        </form>
    </div>
    
    <!-- Grafik Batang Dinamis -->
    <div class="flex items-end justify-between h-64 gap-2 px-2 mt-4 overflow-x-auto">
        @if($chartData->isEmpty())
            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Belum ada data pada periode ini.</div>
        @else
            @php
                $maxAmount = $chartData->max('total_amount') ?: 1; 
            @endphp

            @foreach($chartData as $data)
            @php
                $heightPercent = ($data->total_amount / $maxAmount) * 100;
                $heightPercent = max($heightPercent, 10); 
            @endphp
            <div class="flex flex-col items-center justify-end gap-3 w-full min-w-[40px] h-full group">
                <div class="w-full bg-gray-100 rounded-t-md relative h-full flex items-end">
                    <div class="w-full bg-black rounded-t-md transition-all duration-300 group-hover:bg-gray-800 relative" style="height: {{ $heightPercent }}%;">
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-black text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">
                            Rp {{ number_format($data->total_amount / 1000, 0) }}k
                        </div>
                    </div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium truncate w-full text-center">{{ $data->booking_code }}</span>
            </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Tabel Transaksi Terbaru -->
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 flex justify-between items-center border-b border-gray-50">
        <h3 class="font-bold text-lg text-gray-900">Transaction History</h3>
        <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-600 font-medium hover:text-black">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-50">
                    <th class="px-6 py-4 font-semibold">TrxID</th>
                    <th class="px-6 py-4 font-semibold">Date</th>
                    <th class="px-6 py-4 font-semibold">Penyewa</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                @foreach($recentBookings as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-black">{{ $booking->booking_code }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}</td>
                    <td class="px-6 py-4">{{ $booking->guest_name ?? $booking->user->name ?? 'Tamu' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                            @if($booking->status == 'menunggu_verifikasi') bg-yellow-50 text-yellow-700
                            @elseif($booking->status == 'dikonfirmasi') bg-green-50 text-green-700
                            @elseif($booking->status == 'menunggu_pembayaran') bg-red-50 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-right">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection