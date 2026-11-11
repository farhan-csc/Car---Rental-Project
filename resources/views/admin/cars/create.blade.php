@extends('layouts.admin')

@section('header', 'Tambah Mobil Baru')

@section('content')
<div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nama Mobil -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Mobil</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-lg text-sm" required placeholder="Contoh: Toyota Avanza">
            </div>

            <!-- Merek -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Merek</label>
                <input type="text" name="brand" value="{{ old('brand') }}" class="w-full border-gray-300 rounded-lg text-sm" required placeholder="Contoh: Toyota">
            </div>

            <!-- Plat Nomor -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Plat Nomor</label>
                <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="w-full border-gray-300 rounded-lg text-sm" required placeholder="Contoh: B 1234 XYZ">
            </div>

            <!-- Tahun -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun</label>
                <input type="number" name="year" value="{{ old('year', date('Y')) }}" class="w-full border-gray-300 rounded-lg text-sm" required>
            </div>

            <!-- Transmisi -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Transmisi</label>
                <select name="transmission" class="w-full border-gray-300 rounded-lg text-sm" required>
                    <option value="otomatis">Otomatis</option>
                    <option value="manual">Manual</option>
                </select>
            </div>

            <!-- Jumlah Kursi -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jumlah Kursi</label>
                <input type="number" name="seats" value="{{ old('seats') }}" class="w-full border-gray-300 rounded-lg text-sm" required placeholder="Contoh: 5">
            </div>

            <!-- Harga per Hari -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Harga Sewa / Hari (Rp)</label>
                <input type="number" name="price_per_day" value="{{ old('price_per_day') }}" class="w-full border-gray-300 rounded-lg text-sm" required placeholder="Contoh: 350000">
            </div>

            <!-- Foto Mobil -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Foto Mobil (Opsional)</label>
                <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG. Maks 5MB.</p>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deskripsi (Opsional)</label>
            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Contoh: Mobil keluarga yang nyaman...">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.cars.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-100 transition">Batal</a>
            <button type="submit" class="bg-black text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-gray-800 transition">Simpan Mobil</button>
        </div>
    </form>
</div>
@endsection