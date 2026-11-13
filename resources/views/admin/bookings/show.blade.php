@extends('layouts.admin')

@section('header', 'Detail Pesanan: ' . $booking->booking_code)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Info Pesanan & Dokumen -->
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 border-b pb-2">Informasi Penyewa</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500 block">Nama</span> <span class="font-medium">{{ $booking->guest_name ?? $booking->user->name ?? '-' }}</span></div>
                <div><span class="text-gray-500 block">No. WhatsApp</span> <span class="font-medium">{{ $booking->guest_phone ?? $booking->user->phone ?? '-' }}</span></div>
                <div><span class="text-gray-500 block">Email</span> <span class="font-medium">{{ $booking->guest_email ?? $booking->user->email ?? '-' }}</span></div>
                <div><span class="text-gray-500 block">Kendaraan</span> <span class="font-medium">{{ $booking->car->name }}</span></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 border-b pb-2">Dokumen Identitas & Bukti Transfer</h3>
            <div class="grid grid-cols-2 gap-4">
                @foreach($booking->documents as $doc)
                    <div class="border rounded-lg p-2">
                        <p class="text-xs font-bold uppercase mb-2">{{ $doc->type }}</p>
                        @if($doc->file_path)
                            <a href="{{ route('admin.file.show', $doc->file_path) }}" target="_blank">
                            <img src="{{ route('admin.file.show', $doc->file_path) }}" alt="{{ $doc->type }}" class="w-full h-32 object-cover rounded">
                            </a>
                        @else
                            <div class="bg-gray-100 h-32 flex items-center justify-center text-gray-500 text-xs rounded">Tidak ada file</div>
                        @endif
                    </div>
                @endforeach

                    @foreach($booking->payments as $pay)
                    <div class="border rounded-lg p-2 col-span-2">
                        <p class="text-xs font-bold uppercase mb-2">
                            Pembayaran ({{ $pay->type }}) - Rp {{ number_format($pay->amount, 0, ',', '.') }}
                            <span class="ml-2 px-2 py-0.5 rounded text-[10px] 
                                @if($pay->method == 'tunai') bg-blue-100 text-blue-700
                                @else bg-gray-200 text-gray-700 @endif">
                                {{ ucfirst($pay->method) }}
                            </span>
                        </p>
                        
                        @if($pay->method == 'transfer')
                            @if($pay->proof_path)
                                <a href="{{ route('admin.file.show', $pay->proof_path) }}" target="_blank">
                                    <img src="{{ route('admin.file.show', $pay->proof_path) }}" alt="Bukti Transfer" class="w-full h-48 object-contain bg-gray-50 rounded">
                                </a>
                            @else
                                <div class="bg-gray-100 h-32 flex items-center justify-center text-gray-500 text-xs rounded">File tidak ditemukan</div>
                            @endif
                        @else
                            <div class="bg-blue-50 h-24 flex items-center justify-center text-blue-500 text-xs rounded font-medium">
                                Dibayar Tunai (Tidak perlu bukti transfer)
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Aksi -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
            <h3 class="font-bold text-gray-900 mb-4">Aksi Admin</h3>
            
            @if($booking->status == 'menunggu_verifikasi')
            <!-- Form Verifikasi Pembayaran (Sesi 7) -->
            <form action="{{ route('admin.bookings.verify', $booking->id) }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <button type="submit" name="action" value="accept" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg transition text-sm">Terima Pembayaran</button>
                    <div class="pt-4 border-t mt-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Alasan Penolakan</label>
                        <textarea name="reject_reason" rows="2" class="w-full border-gray-300 rounded-lg text-sm mb-2"></textarea>
                        <button type="submit" name="action" value="reject" class="w-full bg-red-100 text-red-700 hover:bg-red-200 font-bold py-2.5 rounded-lg transition text-sm">Tolak Pembayaran</button>
                    </div>
                </div>
            </form>

            @elseif($booking->status == 'dikonfirmasi')
            <!-- Form Serah Terima (Pengambilan) -->
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Status Pembayaran: 
                    <span class="font-bold">{{ $booking->payment_option == 'dp' ? 'DP (Sisa Rp ' . number_format($booking->total_amount - $booking->dp_amount, 0, ',', '.') . ')' : 'Lunas' }}</span>
                </p>
                <p class="text-sm text-gray-600 mb-4">Status Dokumen: 
                    <span class="font-bold text-green-600">Valid</span>
                </p>
            </div>

            <!-- TAMBAHKAN TAG FORM DI SINI -->
            <form action="{{ route('admin.bookings.pickup', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @if($booking->payment_option == 'dp')
                <div class="mb-4 bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                    <label class="block text-xs font-bold text-yellow-800 mb-1">Catat Pelunasan (Sisa Bayar)</label>
                    <select name="payment_method" class="w-full border-gray-300 rounded-lg text-sm mb-2" required>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                        
                    <div class="mt-2">
                        <label class="block text-[10px] font-bold text-yellow-800 mb-1">Upload Bukti (Opsional, jika transfer)</label>
                        <input type="file" name="proof_settlement" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-yellow-100 file:text-yellow-800 hover:file:bg-yellow-200">
                    </div>
                </div>
                @endif

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Catatan Kondisi Mobil (Opsional)</label>
                    <textarea name="pickup_note" rows="2" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Contoh: Bodi mulus, bensin penuh"></textarea>
                </div>

                <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-lg transition text-sm">
                    Konfirmasi Kendaraan Diambil
                </button>
            </form>
            <!-- TAG FORM DITUTUP DI SINI -->

            @elseif($booking->status == 'sedang_disewa')
            <!-- Form Pengembalian Kendaraan -->
            <form action="{{ route('admin.bookings.return', $booking->id) }}" method="POST">
                @csrf
                <div class="mb-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <label class="block text-xs font-bold text-blue-800 mb-1">Catatan Kondisi Saat Kembali (Opsional)</label>
                    <textarea name="return_note" rows="2" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Contoh: Lecet di bumper depan"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition text-sm">
                    Konfirmasi Kendaraan Dikembalikan
                </button>
            </form>

            @elseif($booking->status == 'terlambat')
            <!-- Tampilan Denda & Form Pembayaran Denda -->
            <div class="mb-4 bg-red-50 p-4 rounded-lg border border-red-100">
                <h4 class="font-bold text-red-800 mb-2">Keterlambatan Terdeteksi</h4>
                <p class="text-sm text-red-700">Terlambat: <span class="font-bold">{{ $booking->late_days }} Hari</span></p>
                <p class="text-sm text-red-700">Total Denda: <span class="font-bold text-lg">Rp {{ number_format($booking->late_fee, 0, ',', '.') }}</span></p>
            </div>

            <form action="{{ route('admin.bookings.payPenalty', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Metode Pembayaran Denda</label>
                    <select name="payment_method" class="w-full border-gray-300 rounded-lg text-sm mb-2" required>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                    <div class="mt-2">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">Upload Bukti (Opsional)</label>
                        <input type="file" name="proof_penalty" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                </div>
                <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-lg transition text-sm">
                    Catat Pembayaran Denda & Selesaikan
                </button>
            </form>

            @else
            <!-- Status Lainnya (Selesai, Dibatalkan, dll) -->
            <div class="p-4 bg-gray-50 rounded-lg text-center text-sm text-gray-500">
                Pesanan ini sedang dalam status: <span class="font-bold uppercase">{{ str_replace('_', ' ', $booking->status) }}</span>.
                @if($booking->status == 'selesai')
                    <br><span class="text-green-600 font-bold mt-2 block">Mobil sudah dikembalikan.</span>
                @endif
            </div>
            @endif

            <!-- FORM PEMBATALAN MANUAL (Selalu Tampil Jika Pesanan Belum Selesai/Dibatalkan) -->
            @if(!in_array($booking->status, ['selesai', 'dibatalkan', 'kedaluwarsa']))
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="font-bold text-red-600 mb-2 text-sm">Aksi Darurat / Pembatalan</h3>
                <p class="text-xs text-gray-500 mb-3">Gunakan form ini jika pelanggan tidak datang (No-Show) atau meminta pembatalan.</p>
                
                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="cancel_status" class="w-full border-gray-300 rounded-lg text-sm mb-2" required>
                            <option value="dibatalkan">Batalkan Pesanan (Dibatalkan)</option>
                            <option value="kedaluwarsa">Tandai Kedaluwarsa (Kedaluwarsa)</option>
                        </select>
                        <textarea name="cancel_reason" rows="2" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Alasan wajib diisi (Contoh: Pelanggan tidak datang / No-Show)" required></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-100 text-red-700 hover:bg-red-200 font-bold py-2.5 rounded-lg transition text-sm">
                        Proses Pembatalan
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection