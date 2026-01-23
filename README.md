# SI-PUSKES (Sistem Informasi Puskesmas) 🏥

**SI-PUSKES** adalah aplikasi berbasis web untuk manajemen operasional Puskesmas, mulai dari pendaftaran pasien, rekam medis elektronik, manajemen farmasi, hingga pembayaran kasir.

Aplikasi ini dibangun sebagai tugas kuliah Rekayasa Perangkat Lunak untuk mendigitalkan alur kerja Puskesmas.

![Dashboard Preview](docs/screenshot-dashboard.png)


---

## 🚀 Fitur Utama

Aplikasi ini mencakup modul-modul berikut sesuai *Use Case Scenario*:

* **🔐 Autentikasi & Role Management**
    * Multi-level user: Admin, Pendaftaran, Dokter, Apoteker, Kepala Puskesmas.
    * Keamanan menggunakan Password Hashing & Middleware Authorization.

* **📋 Modul Pendaftaran (Front Office)**
    * Pencarian data pasien (NIK/Nama).
    * Registrasi Pasien Baru dengan **Auto-Generate No. RM** (Rekam Medis).
    * Pendaftaran Kunjungan ke Poli (Umum, Gigi, KIA, dll).

* **🩺 Modul Pemeriksaan (Dokter)**
    * Dashboard Antrean Pasien per Poli.
    * Input Rekam Medis (Anamnesa, Diagnosa, Tanda Vital).
    * **E-Resep:** Input obat & dosis (Cart System).
    * Melihat Riwayat Rekam Medis Pasien sebelumnya.

* **💰 Modul Kasir**
    * Otomatisasi hitung tagihan (Jasa Poli + Total Harga Obat).
    * Dukungan status penjamin: **Umum** (Bayar Tunai) atau **BPJS** (Gratis/Klaim).
    * Cetak Struk Pembayaran (Simulasi).

* **💊 Modul Farmasi & Obat**
    * Master Data Obat & Stok.
    * Penerimaan Resep dari Dokter & Konfirmasi Penyerahan Obat.

* **📊 Modul Kepala Puskesmas**
    * Laporan Kunjungan per Poli.
    * Laporan 10 Penyakit Terbanyak.
    * Laporan Pemakaian Obat.

---

## 🛠 Teknologi yang Digunakan

* **Backend:** Laravel 
* **Frontend:** Livewire + Tailwind CSS 
* **Database:** MySQL
* **Environment:** Docker (Laravel Sail)
* **Tunneling:** Ngrok (Untuk akses publik/demo)

---

## 📦 Cara Instalasi & Menjalankan (Via Docker)

Pastikan di komputer Anda sudah terinstall **Docker Desktop** dan statusnya *Running*.

### 1️⃣ Clone Repository
```bash
git clone https://github.com/septianof/si-puskes.git
cd si-puskes
```

### 2️⃣ Copy Environment File
```bash
cp .env.example .env
```

### 3️⃣ Jalankan Docker Container
```bash
docker compose up -d
```

> **Note:** Pastikan Docker Desktop sudah running sebelum menjalankan perintah ini.

### 4️⃣ Install Dependencies
```bash
# Install PHP Dependencies
docker compose exec laravel.test composer install

# Install Node Dependencies
docker compose exec laravel.test npm install

# Build Frontend Assets (Tailwind CSS)
docker compose exec laravel.test npm run build
```

### 5️⃣ Setup Database
```bash
# Generate Application Key
docker compose exec laravel.test php artisan key:generate

# Migrasi Database & Seeding Data Dummy
docker compose exec laravel.test php artisan migrate:fresh --seed
```

### 6️⃣ Akses Aplikasi

Buka browser dan akses:
```
http://localhost
```

---

## 🔐 Akun Demo (Login)

Gunakan kredensial berikut untuk login sesuai role yang ingin dicoba:

| Role | Username | Password | Deskripsi |
|------|----------|----------|-----------|
| **Admin** | `admin` | `password123` | Mengelola User & Master Data (Poli/Obat) |
| **Pendaftaran** | `pendaftaran` | `password123` | Mendaftarkan Pasien & Kasir Pembayaran |
| **Dokter Umum** | `dokter_umum` | `password123` | Pemeriksaan Poli Umum |
| **Dokter Gigi** | `dokter_gigi` | `password123` | Pemeriksaan Poli Gigi |
| **Dokter KIA** | `dokter_kia` | `password123` | Pemeriksaan Poli KIA |
| **Dokter Lansia** | `dokter_lansia` | `password123` | Pemeriksaan Poli Lansia |
| **Dokter TB** | `dokter_tb` | `password123` | Pemeriksaan Poli TB |
| **Apoteker** | `apoteker` | `password123` | Mengelola Stok & Resep Obat |
| **Kepala Puskesmas** | `kepala` | `password123` | Melihat Laporan & Dashboard |

> **💡 Info:** Lihat file [`database/seeders/UserSeeder.php`](database/seeders/UserSeeder.php) untuk detail lengkap akun seeder.

---

## 🔄 Perintah Berguna

```bash
# Stop container
docker compose down

# Restart container
docker compose restart

# Lihat logs
docker compose logs -f laravel.test

# Akses shell container
docker compose exec laravel.test bash
```