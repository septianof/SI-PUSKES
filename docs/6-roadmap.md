# DEVELOPMENT ROADMAP - SISTEM INFORMASI PUSKESMAS (SIPUSKES)

Dokumen ini berisi tahapan pengerjaan aplikasi menggunakan MySQL, Laravel, Livewire, Tailwind CSS & Vite.

**Target Stack:**
- Backend: Laravel 
- Frontend: Blade + Livewire + Tailwind CSS + Vite
- Database: MySQL

---

## 📅 Tahap 1: Inisialisasi & Konfigurasi (Foundation)
*Tujuan: Menyiapkan lingkungan kerja agar siap coding.*

- [x] **Setup Project Laravel**
    - `composer create-project laravel/laravel SI-PUSKES`
    - `cd SI-PUSKES`
- [x] **Install Dependencies**
    - `composer require livewire/livewire`
    - `npm install tailwindcss @tailwindcss/vite`
- [x] **Konfigurasi Database**
    - Buat database MySQL: `si_puskes`
    - Setup file `.env` (DB_DATABASE, DB_USERNAME, dll)
- [x] **Setup Layout Utama**
    - Buat Layout Base (`resources/views/layouts/app.blade.php`)
    - Integrasi Tailwind (`@tailwind` directives di CSS)
    - Buat Sidebar & Navbar Component

---

## 🗄️ Tahap 2: Database Migration (Schema)
*Tujuan: Menerjemahkan Class Diagram menjadi tabel database.*
*Hint: Gunakan `php artisan make:model NamaModel -m`*

- [x] **Tabel Users & Autentikasi**
    - Modifikasi tabel `users`: Tambah kolom `role`, `username`.
- [x] **Tabel Master Data (Referensi)**
    - Tabel `polis`: `nama_poli`, `lokasi`, `tarif_dasar`.
    - Tabel `obats`: `nama_obat`, `stok`, `harga`, `jenis`.
- [x] **Tabel Pasien**
    - Tabel `pasiens`: `no_rm`, `nik`, `nama`, `tgl_lahir`, `alamat`, `no_bpjs`.
- [x] **Tabel Transaksi (Core)**
    - Tabel `kunjungans`: FK `pasien_id`, FK `poli_id`, `status`, `keluhan_awal`, `is_bpjs`.
    - Tabel `rekam_medis`: FK `kunjungan_id`, FK `dokter_id`, `diagnosa`, `tindakan`, `tanda_vital`.
    - Tabel `reseps`: FK `rekam_medis_id`, `status`.
    - Tabel `detail_reseps`: FK `resep_id`, FK `obat_id`, `jumlah`, `dosis`.
    - Tabel `pembayarans`: FK `kunjungan_id`, `total_biaya`, `status_bayar`.
    - Tabel `klaim_bpjs`: FK `kunjungan_id`, `no_sep`, `status_klaim`.
- [x] **Running Migration**
    - `php artisan migrate`

---

## 🔐 Tahap 3: Autentikasi & Role Management
*Tujuan: Memastikan user login sesuai hak akses.*

- [ ] **Fitur Login**
    - Buat Livewire Component: `Auth/Login`
    - Logika validasi username & password.
- [ ] **Middleware / Authorization**
    - Buat Middleware `CheckRole`.
    - Pastikan Petugas tidak bisa akses halaman Dokter, dsb.
- [ ] **Seeding Data (User Dummy)**
    - Buat `DatabaseSeeder` untuk membuat 1 akun Admin, 1 Petugas, 1 Dokter, 1 Apoteker, 1 Kepala Puskesmas (agar bisa tes login).

---

## ⚙️ Tahap 4: Modul Admin & Master Data (CRUD Dasar)
*Tujuan: Mengisi data awal agar sistem bisa digunakan transaksi.*

- [ ] **CRUD Data User** (Admin)
    - Create, Read, Update, Delete User pegawai.
- [ ] **CRUD Data Poli** (Admin)
    - Input nama poli dan tarif dasarnya.
- [ ] **CRUD Data Obat** (Admin/Apoteker)
    - Input stok awal obat dan harga.

---

## 🏥 Tahap 5: Modul Pendaftaran (Front Office)
*Tujuan: Menangani alur kedatangan pasien.*

- [ ] **Pencarian / Registrasi Pasien**
    - Component: `Pendaftaran/CariPasien`
    - Logika: Jika NIK ketemu -> Load Data. Jika tidak -> Form Pasien Baru.
    - Generate No. RM otomatis (misal: 202401-001).
- [ ] **Form Kunjungan**
    - Component: `Pendaftaran/FormKunjungan`
    - Input: Pilih Poli, Pilih Dokter, Ceklis BPJS/Umum.
    - Output: Simpan ke tabel `kunjungans` (Status: `waiting`).

---

## 🩺 Tahap 6: Modul Pemeriksaan (Dokter)
*Tujuan: Dokter input hasil periksa dan resep.*

- [ ] **Dashboard Dokter**
    - Menampilkan daftar pasien dengan status `waiting` di poli dokter tersebut.
- [ ] **Form Rekam Medis**
    - Component: `Dokter/PeriksaPasien`
    - Input: Anamnesa, Tensi, Diagnosa.
- [ ] **Input Resep (Cart System)**
    - Logika tambah obat ke list resep sementara.
    - Tombol "Simpan & Selesai" -> Update status kunjungan jadi `pharmacy` (jika ada obat) atau `payment` (jika tanpa obat).

---

## 💊 Tahap 7: Modul Farmasi (Apoteker)
*Tujuan: Validasi stok dan penyerahan obat.*

- [ ] **Dashboard Farmasi**
    - Menampilkan kunjungan dengan status `pharmacy`.
- [ ] **Proses Resep**
    - Component: `Farmasi/ProsesResep`
    - Tampil detail obat yang diminta dokter.
    - Logika: Validasi stok (Cukup/Kurang).
    - Tombol "Selesai Siapkan" -> Kurangi stok di tabel `obats` -> Update status kunjungan jadi `payment`.

---

## 💰 Tahap 8: Modul Kasir & Pembayaran
*Tujuan: Finalisasi transaksi.*

- [ ] **Dashboard Kasir** (Bisa gabung menu Pendaftaran)
    - Menampilkan kunjungan status `payment`.
- [ ] **Hitung Tagihan**
    - Component: `Kasir/Pembayaran`
    - Query: Ambil biaya tarif poli + total harga obat.
    - Logika: Jika BPJS -> Total Rp 0 -> Masuk tabel `klaim_bpjs`.
    - Logika: Jika Umum -> Input bayar tunai -> Masuk tabel `pembayarans`.
    - Update status kunjungan jadi `finished`.

---

## 📊 Tahap 9: Laporan & Finishing
*Tujuan: Kebutuhan Kepala Puskesmas.*

- [ ] **Laporan Kunjungan**
    - Filter *Date Range*.
    - Export ke PDF (gunakan library `barryvdh/laravel-dompdf`).
- [ ] **Laporan 10 Penyakit Terbanyak**
    - Query SQL `GROUP BY` diagnosa & `COUNT`.
- [ ] **Final Check**
    - Cek UI Responsiveness.
    - Hapus data dummy.