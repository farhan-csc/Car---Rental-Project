@extends('layouts.admin')

@section('header', 'Edit Mobil: ' . $car->name)

@section('content')
<div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Mobil</label>
                <input type="text" name="name" value="{{ old('name', $car->name) }}" class="w-full border-gray-300 rounded-lg text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Merek</label>
                <input type="text" name="brand" value="{{ old('brand', $car->brand) }}" class="w-full border-gray-300 rounded-lg text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Plat Nomor</label>
                <input type="text" name="plate_number" value="{{ old('plate_number', $car->plate_number) }}" class="w-full border-gray-300 rounded-lg text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun</label>
                <input type="number" name="year" value="{{ old('year', $car->year) }}" class="w-full border-gray-300 rounded-lg text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Transmisi</label>
                <select name="transmission" class="w-full border-gray-300 rounded-lg text-sm" required>
                    <option value="otomatis" {{ $car->transmission == 'otomatis' ? 'selected' : '' }}>Otomatis</option>
                    <option value="manual" {{ $car->transmission == 'manual' ? 'selected' : '' }}>Manual</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jumlah Kursi</label>
                <input type="number" name="seats" value="{{ old('seats', $car->seats) }}" class="w-full border-gray-300 rounded-lg text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Harga Sewa / Hari (Rp)</label>
                <input type="number" name="price_per_day" value="{{ old('price_per_day', $car->price_per_day) }}" class="w-full border-gray-300 rounded-lg text-sm" required>
            </div>
            
            <!-- Pilihan Status (FR-015) -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Kendaraan</label>
                <select name="status" class="w-full border-gray-300 rounded-lg text-sm" required>
                    <option value="aktif" {{ $car->status == 'aktif' ? 'selected' : '' }}>Aktif (Tampil di Katalog)</option>
                    <option value="nonaktif" {{ $car->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                    <option value="perawatan" {{ $car->status == 'perawatan' ? 'selected' : '' }}>Perawatan (Disembunyikan)</option>
                </select>
            </div>

            <!-- Foto -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ganti Foto (Opsional)</label>
                @if($car->photo_path)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $car->photo_path) }}" alt="Foto Lama" class="w-32 h-20 object-cover rounded-lg border">
                        <p class="text-[10px] text-gray-400 mt-1">Foto saat ini</p>
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg text-sm">{{ old('description', $car->description) }}</textarea>
        </div>

        <div class="flex justify-between items-center border-t pt-6">
            <!-- Tombol Hapus (Kiri) -->
            <button type="button" onclick="if(confirm('Yakin ingin menghapus mobil ini?')) { document.getElementById('delete-form').submit(); }" class="text-red-600 hover:text-red-800 text-sm font-bold">
                Hapus Mobil
            </button>

            <!-- Tombol Simpan & Batal (Kanan) -->
            <div class="flex gap-3">
                <a href="{{ route('admin.cars.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="bg-black text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-gray-800 transition">Simpan Perubahan</button>
            </div>
        </div>
    </form>

    <!-- Form Hapus Terpisah -->
    <form id="delete-form" action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection