@extends('layouts.admin')

@section('header', 'Pengaturan Sistem')

@section('content')
<div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <h3 class="font-bold text-gray-900 mb-6 border-b pb-2">Pengaturan Biaya & Denda</h3>

        <!-- PERSENTASE DP -->
        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Persentase DP (%)</label>
            <input type="number" name="dp_percent" value="{{ $settings->where('key', 'dp_percent')->first()->value ?? 50 }}" class="w-full border-gray-300 rounded-lg text-sm" required min="1" max="100">
            <p class="text-[10px] text-gray-400 mt-1">Contoh: 50 (artinya 50% dari total sewa).</p>
        </div>

        <!-- PERSENTASE DENDA -->
        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Persentase Denda Keterlambatan (%)</label>
            <input type="number" name="late_fee_percent" value="{{ $settings->where('key', 'late_fee_percent')->first()->value ?? 100 }}" class="w-full border-gray-300 rounded-lg text-sm" required min="1">
            <p class="text-[10px] text-gray-400 mt-1">Contoh: 100 (artinya denda 100% dari harga sewa harian per hari terlambat).</p>
        </div>

        <div class="flex justify-end mt-8 border-t pt-6">
            <button type="submit" class="bg-black text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-gray-800 transition">Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endsection