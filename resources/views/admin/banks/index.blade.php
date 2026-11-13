@extends('layouts.admin')

@section('header', 'Kelola Rekening Bank')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Form Tambah Rekening -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Tambah Rekening Baru</h3>
        <form action="{{ route('admin.banks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Bank</label>
                <input type="text" name="bank_name" class="w-full border-gray-300 rounded-lg text-sm" required placeholder="Contoh: BCA">
            </div>
            <div class="mb-3">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor Rekening</label>
                <input type="text" name="account_number" class="w-full border-gray-300 rounded-lg text-sm" required placeholder="1234567890">
            </div>
            <div class="mb-3">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Atas Nama</label>
                <input type="text" name="account_name" class="w-full border-gray-300 rounded-lg text-sm" required placeholder="PT Horizon Rental">
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Logo Bank (Opsional)</label>
                <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500">
            </div>
            <button type="submit" class="w-full bg-black text-white font-bold py-2.5 rounded-lg text-sm">Simpan Rekening</button>
        </form>
    </div>

    <!-- Daftar Rekening -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Daftar Rekening Aktif</h3>
        <div class="space-y-3">
            @foreach($banks as $bank)
            <div class="flex items-center justify-between p-3 border rounded-lg">
                <div class="flex items-center gap-3">
                    @if($bank->logo_path)
                        <img src="{{ asset('storage/' . $bank->logo_path) }}" class="w-10 h-10 object-contain">
                    @endif
                    <div>
                        <p class="font-bold text-sm">{{ $bank->bank_name }} - {{ $bank->account_number }}</p>
                        <p class="text-xs text-gray-500">a.n {{ $bank->account_name }}</p>
                    </div>
                </div>
                <form action="{{ route('admin.banks.destroy', $bank->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-500 text-xs hover:underline">Hapus</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection