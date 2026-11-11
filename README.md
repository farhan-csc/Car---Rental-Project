<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://githubusercontent.com" width="350" alt="Laravel Logo"></a></p>

<h2 align="center">Sistem Informasi Car Rental</h2>

<p align="center">
<img src="https://shields.io" alt="Laravel">
<img src="https://shields.io" alt="Tailwind CSS">
<img src="https://shields.io" alt="MySQL">
<img src="https://shields.io" alt="License">
</p>

## Tentang Proyek

**Sistem Informasi Car Rental** adalah aplikasi web penyewaan mobil yang dibangun menggunakan framework **Laravel** dan **Tailwind CSS**. Aplikasi ini dirancang untuk memudahkan pengelolaan armada mobil, pemesanan sewa oleh pelanggan, serta pencatatan transaksi secara terstruktur.

## Prasyarat Sistem (Requirements)

Pastikan di komputer lokal sudah terinstal perangkat lunak berikut sebelum memulai instalasi:

- **PHP** (minimal versi 8.1 atau lebih baru)
- **Composer**
- **Node.js & NPM**
- **Web Server & MySQL Database** (XAMPP atau Laragon)
- **Git**

## Cara Menjalankan Proyek di Localhost

Ikuti langkah-langkah berikut secara berurutan setelah mengunduh proyek dari GitHub:

### 1. Clone Repositori
Buka terminal (Git Bash / CMD) dan jalankan perintah berikut:
```bash
git clone URL_REPOSITORI_GITHUB_KAMU
cd NAMA_FOLDER_PROYEK
```
*(Ganti `URL_REPOSITORI_GITHUB_KAMU` dengan link repositori GitHub proyek ini).*

### 2. Instal Dependensi PHP dan Frontend
Jalankan perintah berikut untuk mengunduh folder `vendor` dan `node_modules`:
```bash
composer install
npm install
```

### 3. Konfigurasi File Environment (`.env`)
Salin file `.env.example` menjadi `.env`, lalu buat kunci aplikasi (*application key*):
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Pengaturan Database
1. Buka **phpMyAdmin** (melalui XAMPP/Laragon) dan buat sebuah database baru yang kosong (misalnya: `db_car_rental`).
2. Buka file `.env` pada folder proyek menggunakan teks editor (VS Code), lalu sesuaikan pengaturan database berikut:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_car_rental
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Jalankan perintah migrasi untuk membuat tabel database secara otomatis:
   ```bash
   php artisan migrate
   ```
   *(Catatan: Jika terdapat data awal/seeder, gunakan perintah `php artisan migrate --seed`).*

### 5. Jalankan Aplikasi
Buka **dua tab terminal** di dalam folder proyek untuk menjalankan server Laravel dan aset Vite secara bersamaan:

- **Terminal 1 (Server Laravel):**
  ```bash
  php artisan serve
  ```
- **Terminal 2 (Compile Tailwind & Vite):**
  ```bash
  npm run dev
  ```

Setelah kedua server berjalan, buka browser dan akses alamat **`http://127.0.0.1:8000`**.

## License

Proyek ini bersifat *open-source* dan dilisensikan di bawah [MIT license](https://opensource.org).
