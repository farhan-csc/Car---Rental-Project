<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', function () {
    // Ambil mobil aktif, urutkan dari yang terbaru, tampilkan 8 mobil
    $cars = \Illuminate\Support\Facades\DB::table('cars')
                ->where('status', 'aktif')
                ->orderBy('created_at', 'desc') // Urutkan dari yang paling baru ditambahkan
                ->take(8) // Tampilkan 8 mobil
                ->get();
    return view('welcome', compact('cars'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\Admin\AdminDashboardController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [AdminDashboardController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminDashboardController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/verify', [AdminDashboardController::class, 'verifyPayment'])->name('bookings.verify');

    // Rute untuk melihat file private (KTP/SIM/Bukti Transfer)
    Route::get('/file/{path}', function ($path) {
        $filePath = storage_path('app/private/' . $path);
        if (!\File::exists($filePath)) {
            abort(404);
        }
        return response()->file($filePath);
    })->where('path', '.*')->name('file.show');

    // Rute untuk Serah Terima (Pengambilan) & Pengembalian
    Route::post('/bookings/{booking}/pickup', [AdminDashboardController::class, 'pickup'])->name('bookings.pickup');
    Route::post('/bookings/{booking}/return', [AdminDashboardController::class, 'returnCar'])->name('bookings.return');

    Route::post('/bookings/{booking}/return', [AdminDashboardController::class, 'returnCar'])->name('bookings.return');
    Route::post('/bookings/{booking}/pay-penalty', [AdminDashboardController::class, 'payPenalty'])->name('bookings.payPenalty');

    // Rute Kelola Mobil
    Route::get('/cars', [AdminDashboardController::class, 'cars'])->name('cars.index');
    Route::get('/cars/create', [AdminDashboardController::class, 'createCar'])->name('cars.create');
    Route::post('/cars', [AdminDashboardController::class, 'storeCar'])->name('cars.store');

    Route::get('/cars/{car}/edit', [AdminDashboardController::class, 'editCar'])->name('cars.edit');
    Route::put('/cars/{car}', [AdminDashboardController::class, 'updateCar'])->name('cars.update');
    Route::delete('/cars/{car}', [AdminDashboardController::class, 'destroyCar'])->name('cars.destroy');

    Route::post('/bookings/{booking}/cancel', [AdminDashboardController::class, 'cancelBooking'])->name('bookings.cancel');

    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');

    Route::get('/banks', [AdminDashboardController::class, 'bankAccounts'])->name('banks.index');
    Route::post('/banks', [AdminDashboardController::class, 'storeBank'])->name('banks.store');
    Route::delete('/banks/{id}', [AdminDashboardController::class, 'destroyBank'])->name('banks.destroy');
});

// Rute untuk melihat detail mobil
Route::get('/mobil/{id}', function ($id) {
    $car = \Illuminate\Support\Facades\DB::table('cars')->find($id);
    if (!$car) {
        abort(404, 'Mobil tidak ditemukan');
    }
    return view('car-detail', compact('car'));
})->name('car.detail');

use App\Http\Controllers\BookingController;

Route::post('/mobil/{id}/booking', [BookingController::class, 'store'])->name('booking.store');

// Rute Halaman Checkout & Pembayaran
Route::get('/checkout/{token}', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/checkout/{token}', [BookingController::class, 'processCheckout'])->name('booking.process');

Route::post('/checkout/{token}/upload', [BookingController::class, 'uploadDocuments'])->name('booking.upload');

Route::get('/checkout/{token}/success', [BookingController::class, 'success'])->name('booking.success');

