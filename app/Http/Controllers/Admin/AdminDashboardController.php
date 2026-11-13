<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalBookings = Booking::count();
        $pendingVerifications = Booking::where('status', 'menunggu_verifikasi')->count();
        $totalRevenue = DB::table('payments')->where('status', 'diverifikasi')->sum('amount');
        
        $recentBookings = Booking::with('car')->orderBy('created_at', 'desc')->take(5)->get();

        // LOGIKA FILTER GRAFIK
        $filter = $request->get('filter', '1month'); // Default 1 bulan
        $query = Booking::query();

        if ($filter == '5days') {
            $query->where('created_at', '>=', now()->subDays(5));
        } elseif ($filter == '1week') {
            $query->where('created_at', '>=', now()->subWeek());
        } elseif ($filter == '1year') {
            $query->where('created_at', '>=', now()->subYear());
        } else { // 1month
            $query->where('created_at', '>=', now()->subMonth());
        }

        // Ambil data untuk grafik (maksimal 10 data agar rapi)
        $chartData = $query->orderBy('created_at', 'asc')->take(10)->get();

        return view('admin.dashboard', compact('totalBookings', 'pendingVerifications', 'totalRevenue', 'recentBookings', 'chartData', 'filter'));
    }

    public function bookings()
    {
        $bookings = Booking::with('car')->orderBy('created_at', 'desc')->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['car', 'documents', 'payments']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function verifyPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'action' => 'required|in:accept,reject',
            'reject_reason' => 'required_if:action,reject|string|nullable'
        ]);

        DB::beginTransaction();
        try {
            if ($request->action === 'accept') {
                // Sesuai FR-073: Ubah status pesanan menjadi Dikonfirmasi
                $booking->update(['status' => 'dikonfirmasi']);
                
                // Sesuai FR-072: Catat verifikasi pembayaran
                DB::table('payments')->where('booking_id', $booking->id)->update([
                    'status' => 'diverifikasi',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                // Verifikasi dokumen
                DB::table('documents')->where('booking_id', $booking->id)->update([
                    'status' => 'valid',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                $message = 'Pembayaran berhasil diverifikasi. Status pesanan: Dikonfirmasi.';
            } else {
                // LOGIKA BARU SESUAI FR-075
                $deadline = \Carbon\Carbon::parse($booking->payment_deadline);
                
                if (\Carbon\Carbon::now()->greaterThan($deadline)) {
                    // Jika sudah lewat batas waktu, ubah status jadi kedaluwarsa
                    $booking->update(['status' => 'kedaluwarsa']);
                    $message = 'Pembayaran ditolak. Batas waktu sudah lewat, pesanan menjadi Kedaluwarsa.';
                } else {
                    // Jika belum lewat batas waktu, kembalikan ke menunggu pembayaran
                    $booking->update(['status' => 'menunggu_pembayaran']);
                    $message = 'Pembayaran ditolak. Pesanan dikembalikan ke status Menunggu Pembayaran.';
                }
                
                // Update status payment di tabel payments
                DB::table('payments')->where('booking_id', $booking->id)->update([
                    'status' => 'ditolak',
                    'reject_reason' => $request->reject_reason,
                ]);
            }

            DB::commit();
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    // Fungsi untuk mencatat pengambilan kendaraan (UC-12)
    public function pickup(Request $request, Booking $booking)
    {
        // Validasi pastikan status sudah dikonfirmasi
        if ($booking->status !== 'dikonfirmasi') {
            return back()->withErrors(['error' => 'Pesanan belum dikonfirmasi atau sudah diproses.']);
        }

        // Validasi: Cegah pengambilan jika tanggal masih jauh di masa depan
        $pickupDate = \Carbon\Carbon::parse($booking->pickup_at);
        $today = \Carbon\Carbon::now();

        // Jika hari ini masih sebelum tanggal pengambilan (dengan toleransi H-1)
        if ($today->lt($pickupDate->copy()->subDay())) {
            return back()->withErrors(['error' => 'Belum waktunya pengambilan. Jadwal pengambilan adalah ' . $pickupDate->format('d M Y, H:i') . '.']);
        }

        // Jika ada sisa pembayaran (DP), Admin mencatat pelunasan
        $amountToPay = $booking->payment_option === 'dp' ? ($booking->total_amount - $booking->dp_amount) : 0;
        
                if ($amountToPay > 0) {
            $request->validate([
                'payment_method' => 'required|in:tunai,transfer',
                'pickup_note' => 'nullable|string',
                'proof_settlement' => 'nullable|image|max:5120' // Validasi file opsional
            ]);

            $proofPath = null;
            // Cek jika Admin mengunggah file bukti pelunasan
            if ($request->hasFile('proof_settlement')) {
                $proofPath = $request->file('proof_settlement')->store('payments', 'local');
            }

            // Catat pembayaran pelunasan
            DB::table('payments')->insert([
                'booking_id' => $booking->id,
                'type' => 'pelunasan',
                'amount' => $amountToPay,
                'method' => $request->payment_method,
                'proof_path' => $proofPath, // Simpan path (bisa null jika tunai)
                'status' => 'diverifikasi', // Langsung diverifikasi karena dicatat Admin
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update status pesanan menjadi sedang_disewa
        $booking->update([
            'status' => 'sedang_disewa',
            'picked_up_at' => now(), // FR-084: Simpan waktu aktual
            'pickup_note' => $request->pickup_note
        ]);

        return back()->with('success', 'Kendaraan berhasil diserahkan. Status pesanan: Sedang Disewa.');
    }

    // Fungsi untuk mencatat pengembalian kendaraan & menghitung denda (UC-13, UC-14)
    public function returnCar(Request $request, Booking $booking)
    {
        // Pastikan status sedang disewa atau terlambat
        if (!in_array($booking->status, ['sedang_disewa', 'terlambat'])) {
            return back()->withErrors(['error' => 'Status pesanan tidak valid untuk pengembalian.']);
        }

        $request->validate([
            'return_note' => 'nullable|string'
        ]);

        $now = \Carbon\Carbon::now();
        
        // 1. Tentukan Batas Pengembalian (BR-13)
        // Batas = Jam pengambilan terjadwal pada hari terakhir sewa
        $pickupDate = \Carbon\Carbon::parse($booking->pickup_at);
        $returnDueDate = \Carbon\Carbon::parse($booking->return_due_at);
        $returnDeadline = $returnDueDate->copy()->setTime($pickupDate->hour, $pickupDate->minute, 0);

        // 2. Hitung Selisih Waktu (FR-090)
        $lateMinutes = $returnDeadline->diffInMinutes($now, false); // false agar bisa negatif jika lebih awal
        
        $lateDays = 0;
        $lateFee = 0;

        // 3. Logika Denda (BR-09 & FR-091, FR-092, FR-093)
        // Jika selisih > 120 menit (2 jam)
        if ($lateMinutes > 120) {
            // Hitung hari terlambat (dibulatkan ke atas)
            // Kurangi 120 menit toleransi sebelum membagi 24 jam
            $lateDays = ceil(($lateMinutes - 120) / (24 * 60)); 
            
            // Ambil persentase denda dari settings (default 100)
            $lateFeePercent = DB::table('settings')->where('key', 'late_fee_percent')->value('value') ?? 100;
            
            // Denda = hari terlambat x harga per hari pesanan x persentase
            $lateFee = $lateDays * $booking->price_per_day * ($lateFeePercent / 100);
        }

        // 4. Update Data Pesanan
        $booking->update([
            'returned_at' => $now,
            'return_note' => $request->return_note,
            'late_days' => $lateDays,
            'late_fee' => $lateFee,
            // Jika ada denda, status jadi 'terlambat' (menunggu bayar denda). Jika tidak, 'selesai'.
            'status' => $lateFee > 0 ? 'terlambat' : 'selesai'
        ]);

        // Jika ada denda, kita hentikan di sini. Admin harus menagih denda dulu.
        if ($lateFee > 0) {
            return back()->with('success', "Kendaraan dikembalikan. Terlambat {$lateDays} hari. Denda: Rp " . number_format($lateFee, 0, ',', '.') . ". Silakan catat pembayaran denda.");
        }

        return back()->with('success', 'Kendaraan berhasil dikembalikan tepat waktu. Pesanan Selesai.');
    }

        public function payPenalty(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:tunai,transfer',
            'proof_penalty' => 'nullable|image|max:5120'
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_penalty')) {
            $proofPath = $request->file('proof_penalty')->store('payments', 'local');
        }

        // Catat pembayaran denda
        DB::table('payments')->insert([
            'booking_id' => $booking->id,
            'type' => 'denda',
            'amount' => $booking->late_fee,
            'method' => $request->payment_method,
            'proof_path' => $proofPath,
            'status' => 'diverifikasi',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ubah status menjadi selesai
        $booking->update(['status' => 'selesai']);

        return back()->with('success', 'Pembayaran denda berhasil dicatat. Pesanan telah Selesai.');
    }

    // 1. Menampilkan Daftar Mobil
    public function cars()
    {
        $cars = DB::table('cars')->orderBy('created_at', 'desc')->get();
        return view('admin.cars.index', compact('cars'));
    }

    // 2. Menampilkan Form Tambah Mobil
    public function createCar()
    {
        return view('admin.cars.create');
    }

    // 3. Menyimpan Mobil Baru
    public function storeCar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'brand' => 'required|string|max:50',
            'plate_number' => 'required|string|max:15|unique:cars',
            'year' => 'required|integer|min:1990|max:' . date('Y'),
            'transmission' => 'required|in:manual,otomatis',
            'seats' => 'required|integer|min:1',
            'price_per_day' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Maks 5MB
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            // Simpan foto ke storage/app/public/cars
            $photoPath = $request->file('photo')->store('cars', 'public');
        }

        DB::table('cars')->insert([
            'name' => $request->name,
            'brand' => $request->brand,
            'plate_number' => strtoupper($request->plate_number),
            'year' => $request->year,
            'transmission' => $request->transmission,
            'seats' => $request->seats,
            'price_per_day' => $request->price_per_day,
            'status' => 'aktif', // Default aktif
            'description' => $request->description,
            'photo_path' => $photoPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.cars.index')->with('success', 'Mobil berhasil ditambahkan.');
    }

    // 1. Menampilkan Form Edit
    public function editCar($id)
    {
        $car = DB::table('cars')->find($id);
        if (!$car) abort(404);
        return view('admin.cars.edit', compact('car'));
    }

    // 2. Menyimpan Perubahan Data Mobil
    public function updateCar(Request $request, $id)
    {
        $car = DB::table('cars')->find($id);
        if (!$car) abort(404);

        $request->validate([
            'name' => 'required|string|max:100',
            'brand' => 'required|string|max:50',
            'plate_number' => 'required|string|max:15|unique:cars,plate_number,' . $id,
            'year' => 'required|integer',
            'transmission' => 'required|in:manual,otomatis',
            'seats' => 'required|integer|min:1',
            'price_per_day' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif,perawatan',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $photoPath = $car->photo_path;
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($car->photo_path && \Storage::disk('public')->exists($car->photo_path)) {
                \Storage::disk('public')->delete($car->photo_path);
            }
            // Simpan foto baru
            $photoPath = $request->file('photo')->store('cars', 'public');
        }

        DB::table('cars')->where('id', $id)->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'plate_number' => strtoupper($request->plate_number),
            'year' => $request->year,
            'transmission' => $request->transmission,
            'seats' => $request->seats,
            'price_per_day' => $request->price_per_day,
            'status' => $request->status,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.cars.index')->with('success', 'Data mobil berhasil diperbarui.');
    }

    // 3. Hapus Mobil (Dengan proteksi FR-018)
    public function destroyCar($id)
    {
        // Cek apakah mobil pernah disewa (ada di tabel bookings)
        $hasBookings = DB::table('bookings')->where('car_id', $id)->exists();
        
        if ($hasBookings) {
            return back()->withErrors(['error' => 'Mobil tidak dapat dihapus karena memiliki riwayat pesanan. Silakan ubah statusnya menjadi Nonaktif.']);
        }

        $car = DB::table('cars')->find($id);
        if ($car && $car->photo_path) {
            \Storage::disk('public')->delete($car->photo_path);
        }

        DB::table('cars')->where('id', $id)->delete();

        // Arahkan ke halaman daftar mobil, bukan ke halaman edit sebelumnya
        return redirect()->route('admin.cars.index')->with('success', 'Mobil berhasil dihapus.');
    }

    public function cancelBooking(Request $request, Booking $booking)
    {
        // Validasi alasan wajib diisi (FR-076)
        $request->validate([
            'cancel_reason' => 'required|string',
            'cancel_status' => 'required|in:dibatalkan,kedaluwarsa'
        ]);

        // Update status pesanan
        $booking->update([
            'status' => $request->cancel_status,
            'cancel_reason' => $request->cancel_reason,
            'cancelled_at' => now(),
        ]);

        $message = $request->cancel_status === 'dibatalkan' 
            ? 'Pesanan berhasil dibatalkan.' 
            : 'Pesanan berhasil diubah menjadi Kedaluwarsa.';

        return back()->with('success', $message);
    }

    // 1. Menampilkan Halaman Pengaturan
    public function settings()
    {
        // Ambil semua pengaturan dari tabel settings
        $settings = DB::table('settings')->get();
        return view('admin.settings', compact('settings'));
    }

    // 2. Menyimpan Perubahan Pengaturan
    public function updateSettings(Request $request)
    {
        $request->validate([
            'dp_percent' => 'required|integer|min:1|max:100',
            'late_fee_percent' => 'required|integer|min:1',
        ]);

        DB::table('settings')->where('key', 'dp_percent')->update(['value' => $request->dp_percent]);
        DB::table('settings')->where('key', 'late_fee_percent')->update(['value' => $request->late_fee_percent]);

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    // 1. Menampilkan Halaman Kelola Rekening
    public function bankAccounts()
    {
        $banks = DB::table('bank_accounts')->get();
        return view('admin.banks.index', compact('banks'));
    }

    // 2. Menyimpan Rekening Baru
    public function storeBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:50',
            'account_number' => 'required|string|max:30',
            'account_name' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('bank_logos', 'public');
        }

        DB::table('bank_accounts')->insert([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'logo_path' => $logoPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    // 3. Menghapus Rekening
    public function destroyBank($id)
    {
        $bank = DB::table('bank_accounts')->find($id);
        if ($bank && $bank->logo_path) {
            \Storage::disk('public')->delete($bank->logo_path);
        }
        DB::table('bank_accounts')->where('id', $id)->delete();
        return back()->with('success', 'Rekening bank berhasil dihapus.');
    }
}