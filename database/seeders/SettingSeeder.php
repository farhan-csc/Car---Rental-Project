<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            ['key' => 'dp_percent', 'value' => '50', 'description' => 'Persentase DP'],
            ['key' => 'late_fee_percent', 'value' => '100', 'description' => 'Persentase denda per hari'],
            ['key' => 'late_tolerance_minutes', 'value' => '120', 'description' => 'Toleransi keterlambatan'],
            ['key' => 'payment_deadline_minutes', 'value' => '120', 'description' => 'Batas waktu bayar standar'],
            ['key' => 'payment_deadline_short_minutes', 'value' => '60', 'description' => 'Batas waktu bayar jika < 24 jam'],
            ['key' => 'short_deadline_threshold_hours', 'value' => '24', 'description' => 'Ambang batas waktu singkat'],
            ['key' => 'upload_max_mb', 'value' => '5', 'description' => 'Maksimal ukuran file upload'],
            ['key' => 'bank_account', 'value' => 'BCA 1234567890 a.n Rental Mobil', 'description' => 'Rekening tujuan transfer'],
            ['key' => 'session_timeout_minutes', 'value' => '30', 'description' => 'Waktu timeout sesi'],
            ['key' => 'login_max_attempts', 'value' => '5', 'description' => 'Maksimal percobaan login'],
            ['key' => 'login_window_minutes', 'value' => '15', 'description' => 'Jendela waktu percobaan login'],
        ]);
    }
}