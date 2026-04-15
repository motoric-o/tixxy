<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center">Tixxy</h1>

<p align="center">
    Aplikasi pemesanan tiket berbasis web yang dibangun dengan Laravel.
</p>

## Deskripsi Singkat

Tixxy adalah aplikasi pemesanan tiket event yang mengintegrasikan pengelolaan acara dan sistem ticketing dalam satu platform. Projek ini dibuat sebagai pemenuhan tugas untuk mata kuliah Pemrograman Web Lanjut.

## Latar Belakang

Projek ini dikembangkan oleh tim dari program studi Teknik Informatika, Universitas Kristen Maranatha.

Tim:
* **Christian Anthony Hermawan - 2472008 (Ketua Tim)**
* **Jonathan Valent W. - 2472010**
* **Rico Dharmawan - 2472041**
* **Jayden Marvel Ethanael - 2472048**

## Fitur Utama

### 🔐 Authentication & Authorization
* **Register & Login**: Akses pendaftaran dan masuk akun yang aman.
* **Role Management**: Dukungan peran pengguna yang mencakup Admin, Organizer, dan User.
* **Password Hashing**: Proteksi keamanan kata sandi pengguna.
* **Middleware Route**: Pembatasan akses halaman berdasarkan autentikasi dan peran.

### 📅 Manajemen Event
* **CRUD Event**: Fitur lengkap untuk menambah, membaca, mengubah, dan menghapus acara.
* **Upload Banner**: Pengunggahan visual promosi untuk halaman detail acara.
* **Kategori Event**: Pengelompokan acara untuk mempermudah pencarian.
* **Jadwal & Lokasi**: Informasi terperinci mengenai waktu dan tempat pelaksanaan.
* **Kuota Tiket**: Penentuan batas maksimal peserta pada setiap acara.

### 🎟️ Sistem Ticketing
* **Pemilihan Jenis Tiket**: Dukungan untuk berbagai kategori tiket seperti VIP, Regular, dll.
* **Manajemen Stok Otomatis**: Pemotongan ketersediaan tiket secara *real-time* saat transaksi berlangsung.
* **Generate E-ticket (QR Code)**: Pembuatan tiket elektronik dengan kode unik.
* **Validasi Tiket**: Simulasi *scan* tiket untuk akses masuk acara.
* **Queue & Waiting List**: Sistem antrean untuk mengakomodasi lonjakan pemesanan.
* **Email Notification**: Pengiriman otomatis detail e-ticket dan QR Code ke email pengguna.

### 📊 Dashboard & Reporting
* **Statistik Penjualan**: Rekapitulasi penjualan tiket yang mudah dibaca.
* **Grafik Transaksi**: Visualisasi tren penjualan harian maupun bulanan.
* **Total Revenue**: Ringkasan keseluruhan pendapatan yang dihasilkan.
* **Event Performance Analytics**: Evaluasi performa dari masing-masing acara yang diselenggarakan.
* **Export Report**: Kemudahan mengunduh laporan ke dalam format Excel atau PDF.

### 💳 Payment Integration
* **Simulasi Pembayaran**: Uji coba integrasi proses transaksi pembayaran.
* **Status Transaksi**: Pemantauan status pemesanan secara langsung (*pending, paid, failed*).

## Teknologi

Platform & Framework pembuatan aplikasi:
* **Frontend**: Blade Templates, Tailwind CSS, Alpine.js, charts.js
* **Backend**: Laravel 12 (PHP 8.2+)
* **Database**: PostgreSQL
* **External API**: QRServer, Html5-QRCode

Suggested System Requirement:
* **PHP**: 8.5.4
* **Database**: PostgreSQL 18.3
* **Node.js**: 24.11.1

## Instalasi dan Penggunaan

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lokal:

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/motoric-o/tixxy.git](https://github.com/motoric-o/tixxy.git)
    cd tixxy
    ```

2.  **Install Dependensi PHP & JavaScript**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Salin file contoh konfigurasi dan buat file `.env` baru.
    ```bash
    cp .env.example .env
    ```

4.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

5.  **Migrasi & Seeding Database**
    ```bash
    php artisan migrate --seed
    ```

6.  **Storage Link**
    Menghubungkan direktori penyimpanan publik.
    ```bash
    php artisan storage:link
    ```

7.  **Interval Automated Work**
    Menjalankan *task scheduler* untuk proses otomatisasi di latar belakang.
    ```bash
    php artisan schedule:work
    ```

8.  **Jalankan Aplikasi**
    Jalan server development untuk frontend:
    ```bash
    npm run dev
    ```
    Dan di terminal baru, jalankan server backend:
    ```bash
    php artisan serve
    ```

Aplikasi dapat diakses di `http://localhost:8000`.

## Lisensi

[MIT license](https://opensource.org/licenses/MIT).