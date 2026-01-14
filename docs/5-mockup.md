# PUSKESMAS APP - UI MOCKUPS (ASCII WIREFRAME)

Dokumen ini berisi rancangan antarmuka kasar untuk Sistem Informasi Puskesmas berbasis Web (Laravel + Livewire).

**Teknologi UI:** Tailwind CSS (Sidebar Layout)

---

## 1. Halaman Login (Semua Aktor)
Layout sederhana, bersih, terpusat di tengah layar.

+---------------------------------------------------------------+
|                                                               |
|           +---------------------------------------+           |
|           |          LOGO PUSKESMAS               |           |
|           |                                       |           |
|           |  [     Username / NIP          ]      |           |
|           |                                       |           |
|           |  [     Password                ]      |           |
|           |                                       |           |
|           |       [   MASUK SISTEM   ]            |           |
|           |                                       |           |
|           |      Lupa Password? Hubungi Admin     |           |
|           +---------------------------------------+           |
|                                                               |
+---------------------------------------------------------------+

---

## 2. Dashboard & Layout Utama (Contoh: Admin/Kepala)
Menggunakan Sidebar Layout khas Dashboard modern.

+------------------+--------------------------------------------+
|  PUSKESMAS APP   |  [User: Kepala Puskesmas]  [Logout]        |
+------------------+--------------------------------------------+
|                  |                                            |
|  [ Dashboard   ] |  Dashboard Ringkasan                       |
|  [ Laporan     ] |                                            |
|  [ Master Data ] |  +-------------------+ +-----------------+ |
|  [ Settings    ] |  | Total Kunjungan   | | Pasien BPJS     | |
|                  |  |       150         | |       85        | |
|                  |  +-------------------+ +-----------------+ |
|                  |                                            |
|                  |  +---------------------------------------+ |
|                  |  | Grafik Kunjungan 7 Hari Terakhir      | |
|                  |  | [|||||||||||||||||||||||||||||||||||] | |
|                  |  +---------------------------------------+ |
|                  |                                            |
+------------------+--------------------------------------------+

---

## 3. Modul Pendaftaran (Aktor: Petugas)
Fitur gabungan: Pendaftaran Baru, Antrean, dan Pembayaran (Kasir).

+------------------+--------------------------------------------+
|  MENU PETUGAS    |  Halaman Pendaftaran & Kasir               |
+------------------+--------------------------------------------+
| [ Pendaftaran  ] |                                            |
| [ Data Pasien  ] |  [+ Pasien Baru]  [Cari Pasien (NIK/RM)...]|
| [ Kasir/Bayar  ] |                                            |
|                  |  TABEL ANTREAN PENDAFTARAN HARI INI        |
|                  |  +-------+------------+--------+--------+  |
|                  |  | No.RM | Nama       | Poli   | Status |  |
|                  |  +-------+------------+--------+--------+  |
|                  |  | 001   | Budi S.    | Umum   | Menunggu |  |
|                  |  | 002   | Siti A.    | Gigi   | Di Poli  |  |
|                  |  | 003   | Ahmad D.   | Umum   | Bayar    |-> [Btn: Bayar]
|                  |  +-------+------------+--------+--------+  |
|                  |                                            |
|                  |  ----------------------------------------  |
|                  |  FORM PENDAFTARAN CEPAT (Modal/Card)       |
|                  |  Nama: [.............]  BPJS: [Chk Box]    |
|                  |  Poli: [Dropdown v]     [Btn: DAFTAR]      |
+------------------+--------------------------------------------+

---

## 4. Modul Pemeriksaan (Aktor: Dokter)
Fokus pada input rekam medis dan resep.

+------------------+--------------------------------------------+
|  MENU DOKTER     |  Poli: UMUM | Pasien: Budi S. (RM-001)     |
+------------------+--------------------------------------------+
| [ Antrean Poli ] |                                            |
| [ Riwayat RM   ] |  +---------------------------------------+ |
|                  |  | 1. TANDA VITAL                        | |
|                  |  | Tensi: [120/80]  Suhu: [36.5] BB:[60] | |
|                  |  +---------------------------------------+ |
|                  |                                            |
|                  |  +---------------------------------------+ |
|                  |  | 2. PEMERIKSAAN & DIAGNOSA             | |
|                  |  | Keluhan: [Demam 3 hari...]            | |
|                  |  | Diagnosa (ICD-10): [A01.0 - Typhoid]  | |
|                  |  +---------------------------------------+ |
|                  |                                            |
|                  |  +---------------------------------------+ |
|                  |  | 3. RESEP OBAT                         | |
|                  |  | [ Paracetamol ] [ 3x1 ] [Add]         | |
|                  |  | List:                                 | |
|                  |  | - Amoxicillin 500mg (3x1) [x]         | |
|                  |  +---------------------------------------+ |
|                  |                                            |
|                  |         [ SIMPAN & SELESAI ]               |
+------------------+--------------------------------------------+

---

## 5. Modul Farmasi (Aktor: Apoteker)
Menerima resep digital dan validasi stok.

+------------------+--------------------------------------------+
|  MENU APOTEKER   |  Daftar Resep Masuk                        |
+------------------+--------------------------------------------+
| [ Resep Masuk  ] |                                            |
| [ Stok Obat    ] |  +------+-----------+--------------+-----+ |
| [ Laporan      ] |  | Jam  | Pasien    | Dokter       | Aksi| |
|                  |  +------+-----------+--------------+-----+ |
|                  |  | 08:30| Budi S.   | Dr. Tirta    | [>] |-> Klik Detail
|                  |  | 09:00| Siti A.   | Dr. Gigi     | [>] | |
|                  |  +------+-----------+--------------+-----+ |
|                  |                                            |
|                  |  DETAIL RESEP (View Panel)                 |
|                  |  ----------------------------------------  |
|                  |  R/ Paracetamol 500mg (10 tab)             |
|                  |     Stok: 100 -> [ OK / Kosong ]           |
|                  |  R/ Vitamin C (5 tab)                      |
|                  |     Stok: 50  -> [ OK / Kosong ]           |
|                  |                                            |
|                  |  [ PROSES & SERAHKAN OBAT ]                |
+------------------+--------------------------------------------+

---

## 6. Modul Laporan (Aktor: Kepala Puskesmas)
Fitur filtering dan tabel data.

+------------------+--------------------------------------------+
|  MENU KEPALA     |  Laporan Kunjungan                         |
+------------------+--------------------------------------------+
| [ Dashboard    ] |                                            |
| [ Lap. Kunjungan]|  Filter Periode:                           |
| [ Lap. Penyakit] |  [ 01-01-2025 ] s/d [ 31-01-2025 ] [Cari]  |
| [ Lap. Obat    ] |                                            |
|                  |  [Btn: Export PDF] [Btn: Print]            |
|                  |                                            |
|                  |  +------------+--------+--------+--------+ |
|                  |  | Tanggal    | Poli   | Jumlah | BPJS   | |
|                  |  +------------+--------+--------+--------+ |
|                  |  | 01-01-2025 | Umum   | 20     | 15     | |
|                  |  | 01-01-2025 | Gigi   | 5      | 2      | |
|                  |  | ...        | ...    | ...    | ...    | |
|                  |  +------------+--------+--------+--------+ |
|                  |                                            |
+------------------+--------------------------------------------+

### Tips Implementasi Tailwind & Livewire:
1.  **Komponen:** Pecah mockup di atas menjadi komponen Livewire.
    * `Resources/Views/Livewire/Auth/Login.blade.php`
    * `Resources/Views/Livewire/Poli/PemeriksaanForm.blade.php`
2.  **Layout:** Gunakan `layouts/app.blade.php` untuk *Sidebar* dan *Header* agar tidak perlu *copy-paste* kode navigasi di setiap halaman.
3.  **Modal:** Untuk form input cepat (seperti tambah pasien baru), gunakan *Modal* (Pop-up) Tailwind agar petugas tidak perlu pindah halaman.