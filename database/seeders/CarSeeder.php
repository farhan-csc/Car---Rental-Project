<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cars')->insert([
            [
                'name' => 'Toyota Yaris', 'brand' => 'Toyota', 'plate_number' => 'B 1234 XYZ', 
                'year' => 2023, 'transmission' => 'otomatis', 'seats' => 5, 
                'price_per_day' => 350000, 'status' => 'aktif', 
                'description' => 'Mobil hatchback irit dan lincah untuk di kota.',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Toyota Alphard', 'brand' => 'Toyota', 'plate_number' => 'B 8888 VIP', 
                'year' => 2022, 'transmission' => 'otomatis', 'seats' => 7, 
                'price_per_day' => 1500000, 'status' => 'aktif', 
                'description' => 'MPV mewah untuk keluarga atau tamu penting.',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Lexus NX 300', 'brand' => 'Lexus', 'plate_number' => 'B 7777 LXS', 
                'year' => 2023, 'transmission' => 'otomatis', 'seats' => 5, 
                'price_per_day' => 1200000, 'status' => 'aktif', 
                'description' => 'SUV premium dengan kenyamanan maksimal.',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Honda Brio', 'brand' => 'Honda', 'plate_number' => 'B 5555 HND', 
                'year' => 2021, 'transmission' => 'manual', 'seats' => 5, 
                'price_per_day' => 250000, 'status' => 'aktif', 
                'description' => 'City car mungil dan hemat bahan bakar.',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}