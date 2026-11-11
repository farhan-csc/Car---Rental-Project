@extends('layouts.admin')

@section('header', 'Kelola Mobil')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-gray-500 text-sm">Daftar semua kendaraan yang terdaftar di sistem.</p>
    <a href="{{ route('admin.cars.create') }}" class="bg-black text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-gray-800 transition">
        + Tambah Mobil
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold">Foto</th>
                    <th class="px-6 py-4 font-semibold">Nama Mobil</th>
                    <th class="px-6 py-4 font-semibold">Plat Nomor</th>
                    <th class="px-6 py-4 font-semibold">Harga / Hari</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                @forelse($cars as $car)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        @if($car->photo_path)
                            <img src="{{ asset('storage/' . $car->photo_path) }}" alt="{{ $car->name }}" class="w-16 h-12 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-[10px] text-gray-400">No Img</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-black">{{ $car->name }}</td>
                    <td class="px-6 py-4">{{ $car->plate_number }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold 
                            @if($car->status == 'aktif') bg-green-100 text-green-800
                            @elseif($car->status == 'nonaktif') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($car->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <!-- Akan dibuat di Sesi 9B -->
                        <a href="{{ route('admin.cars.edit', $car->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data mobil.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection