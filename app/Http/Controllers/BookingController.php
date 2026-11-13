<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Booking;

class BookingController extends Controller
{
    // 1. Fungsi saat klik "Pesan Sekarang" di Detail Mobil
    public function store(Request $request, $car_id)
    {
        $request->validate([
            'pickup_at' => 'required|date|after_or_equal:today',
            'return_due_at' => 'required|date|after:pickup_at',
        ]);

        $car = DB::table('cars')->find($car_id);

        $pickup = Carbon::parse($request->pickup_at);
        $return = Carbon::parse($request->return_due_at);
        
        $diffDays = ceil($pickup->diffInHours($return) / 24);
        $rentalDays = $diffDays < 1 ? 1 : $diffDays;
        $totalAmount = $rentalDays * $car->price_per_day;

        // Cek Bentrok
        $isBentrok = DB::table('bookings')
            ->where('car_id', $car_id)
            ->whereIn('status', ['menunggu_pembayaran', 'menunggu_verifikasi', 'dikonfirmasi', 'sedang_disewa'])
            ->where(function ($query) use ($pickup, $return) {
                $query->where('pickup_at', '<', $return)
                    ->where('return_due_at', '>', $pickup);
            })
            ->exists();

        if ($isBentrok) {
            return back()->withErrors(['bentrok' => 'Maaf, mobil ini sudah disewa pada tanggal tersebut. Silakan pilih tanggal lain.'])->withInput();
        }

        // Hitung Batas Bayar
        $now = Carbon::now();
        $hoursUntilPickup = $now->diffInHours($pickup, false);
        
        if ($hoursUntilPickup < 24) {
            $paymentDeadline = $now->copy()->addMinutes(60); // 1 jam
        } else {
            $paymentDeadline = $now->copy()->addMinutes(120); // 2 jam
        }

        // Simpan Pesanan
        $booking = Booking::create([
            'booking_code' => 'BK-' . strtoupper(Str::random(6)),
            'user_id' => auth()->check() ? auth()->id() : null,
            'car_id' => $car_id,
            'pickup_at' => $pickup,
            'return_due_at' => $return,
            'rental_days' => $rentalDays,
            'price_per_day' => $car->price_per_day,
            'total_amount' => $totalAmount,
            'payment_option' => 'lunas', 
            'status' => 'menunggu_pembayaran',
            'payment_deadline' => $paymentDeadline,
            'payment_token' => Str::random(32),
            'policy_accepted_at' => now(),
        ]);

        return redirect()->route('booking.checkout', $booking->payment_token);
    }

    // 2. Menampilkan Halaman Checkout
    public function checkout($token)
    {
        $booking = Booking::where('payment_token', $token)->firstOrFail();
        $car = DB::table('cars')->find($booking->car_id);
        
        // Ambil semua rekening bank
        $bankAccounts = DB::table('bank_accounts')->get();
        
        return view('checkout', compact('booking', 'car', 'bankAccounts'));
    }

    // 3. Memproses Pilihan Pembayaran & Data Diri
    public function processCheckout(Request $request, $token)
    {
        $booking = Booking::where('payment_token', $token)->firstOrFail();

        $rules = ['payment_option' => 'required|in:lunas,dp'];
        if (!auth()->check()) {
            $rules['guest_name'] = 'required|string|max:100';
            $rules['guest_phone'] = 'required|string|max:20';
            $rules['guest_email'] = 'required|email|max:150';
        }
        $request->validate($rules);

        if (!auth()->check() && $request->payment_option === 'dp') {
            return back()->withErrors(['payment_option' => 'Tamu (belum login) wajib membayar Lunas.'])->withInput();
        }

        $dpAmount = null;
        if ($request->payment_option === 'dp') {
            $dpPercent = DB::table('settings')->where('key', 'dp_percent')->value('value') ?? 50;
            $dpAmount = ceil(($dpPercent / 100) * $booking->total_amount);
        }

        $booking->update([
            'guest_name' => $request->guest_name ?? $booking->guest_name,
            'guest_phone' => $request->guest_phone ?? $booking->guest_phone,
            'guest_email' => $request->guest_email ?? $booking->guest_email,
            'payment_option' => $request->payment_option,
            'dp_amount' => $dpAmount,
        ]);

        session(['payment_selected_' . $booking->id => true]);

        return redirect()->route('booking.checkout', $token)->with('success', 'Data diri dan metode pembayaran berhasil disimpan. Silakan lanjutkan unggah dokumen di bawah.');
    }

    // 4. Memproses Upload Dokumen
    public function uploadDocuments(Request $request, $token)
    {
        $booking = Booking::where('payment_token', $token)->firstOrFail();

        $request->validate([
            'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'sim' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'proof' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('ktp')) {
            $ktpPath = $request->file('ktp')->store('documents/ktp', 'local');
            DB::table('documents')->insert([
                'booking_id' => $booking->id, 'user_id' => $booking->user_id,
                'type' => 'ktp', 'file_path' => $ktpPath, 'status' => 'menunggu_verifikasi',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        if ($request->hasFile('sim')) {
            $simPath = $request->file('sim')->store('documents/sim', 'local');
            DB::table('documents')->insert([
                'booking_id' => $booking->id, 'user_id' => $booking->user_id,
                'type' => 'sim', 'file_path' => $simPath, 'status' => 'menunggu_verifikasi',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('payments', 'local');
            $amountToPay = $booking->payment_option === 'dp' ? $booking->dp_amount : $booking->total_amount;
            $paymentType = $booking->payment_option === 'dp' ? 'dp' : 'pelunasan';

            DB::table('payments')->insert([
                'booking_id' => $booking->id, 'type' => $paymentType, 'amount' => $amountToPay,
                'method' => 'transfer', 'proof_path' => $proofPath, 'status' => 'menunggu_verifikasi',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // Ubah status
        $booking->update(['status' => 'menunggu_verifikasi']);

        // Hapus session
        session()->forget('payment_selected_' . $booking->id);

        // Arahkan ke halaman sukses
        return redirect()->route('booking.success', $booking->payment_token);
    }

    // 5. Menampilkan Halaman Sukses
    public function success($token)
    {
        $booking = Booking::where('payment_token', $token)->firstOrFail();
        return view('booking-success', compact('booking'));
    }
}