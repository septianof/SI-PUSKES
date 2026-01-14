# Skenario Use Case - Sistem Informasi Puskesmas

[cite_start]Dokumen ini menjelaskan alur interaksi berdasarkan Narasi Langkah tugas[cite: 53].

---

## 1. Modul Pendaftaran (Front Office)

### UC-01: Proses Pendaftaran & Verifikasi Pasien
**Aktor:** Pasien, Petugas Pendaftaran
[cite_start]**Referensi PDF:** Langkah 1-4 [cite: 54-67]

| Komponen | Deskripsi |
| :--- | :--- |
| **Pre-condition** | Pasien datang ke loket pendaftaran. |
| **Main Flow** | 1. Pasien menuju loket pendaftaran.<br>2. Petugas mengecek status pasien (Baru/Lama).<br>3. [cite_start]**Jika Pasien Baru:** Petugas mengisi identitas, Sistem membuat No. RM Baru[cite: 57].<br>4. [cite_start]**Jika Pasien Lama:** Petugas mencari dan memverifikasi data (alamat, kontak)[cite: 59].<br>5. Petugas menanyakan Poli Tujuan.<br>6. [cite_start]Petugas mendaftarkan kunjungan ke poli & mencetak nomor antrean [cite: 62-63].<br>7. Petugas mengecek status kepesertaan (Umum/BPJS). |
| **Alternative Flow (BPJS)** | [cite_start]**Langkah 7b:** Jika pasien BPJS, petugas memasukkan nomor BPJS dan menandai kunjungan sebagai "Klaim BPJS"[cite: 67]. |
| **Post-condition** | [cite_start]Pasien mendapatkan nomor antrean dan menunggu di ruang tunggu poli[cite: 68]. |

---

## 2. Modul Pemeriksaan (Poli)

### UC-02: Pemeriksaan Dokter & Rekam Medis
**Aktor:** Dokter
[cite_start]**Referensi PDF:** Langkah 7 [cite: 70-76]

| Komponen | Deskripsi |
| :--- | :--- |
| **Pre-condition** | [cite_start]Pasien sudah masuk ruang pemeriksaan[cite: 69]. |
| **Main Flow** | 1. [cite_start]Dokter melihat data identitas dan riwayat rekam medis di sistem[cite: 73].<br>2. Dokter memeriksa pasien.<br>3. [cite_start]Dokter mencatat tanda vital, keluhan, hasil pemeriksaan, dan diagnosis ke sistem[cite: 74].<br>4. **Pengecekan Obat:** Dokter memutuskan apakah pasien butuh obat.<br>5. [cite_start]Jika perlu, Dokter membuat resep obat di sistem[cite: 76]. |
| **Post-condition** | Data medis tersimpan. Jika ada resep, data terkirim ke Farmasi. |

---

## 3. Modul Pembayaran (Kasir)

### UC-03: Pembayaran & Finalisasi Kunjungan
**Aktor:** Petugas Pendaftaran (sebagai Kasir)
[cite_start]**Referensi PDF:** Langkah 8 [cite: 77-80]

| Komponen | Deskripsi |
| :--- | :--- |
| **Trigger** | Pasien selesai diperiksa dokter. |
| **Main Flow (Umum)** | 1. [cite_start]Sistem menghitung biaya layanan (jasa medis + obat nanti).<br>2. Petugas pendaftaran menerima pembayaran tunai.<br>3. Petugas mencatat status "Lunas" di sistem. |
| **Main Flow (BPJS)** | 1. Biaya ditandai sebagai klaim BPJS di sistem.<br>2. [cite_start]Tidak ada pembayaran tunai di loket[cite: 80]. |
| **Post-condition** | Administrasi keuangan selesai. |

---

## 4. Modul Farmasi

### UC-04: Penyerahan Obat
**Aktor:** Apoteker
[cite_start]**Referensi PDF:** Langkah 10 [cite: 82-88]

| Komponen | Deskripsi |
| :--- | :--- |
| **Pre-condition** | [cite_start]Pasien menyerahkan resep/datang ke farmasi[cite: 81]. |
| **Main Flow** | 1. [cite_start]Apoteker mengambil data resep dari sistem[cite: 84].<br>2. Apoteker mengecek stok obat fisik.<br>3. **Jika Stok Cukup:**<br>&nbsp;&nbsp;&nbsp;a. Obat disiapkan.<br>&nbsp;&nbsp;&nbsp;b. Obat diserahkan ke pasien.<br>&nbsp;&nbsp;&nbsp;c. [cite_start]Sistem mengurangi stok obat[cite: 87]. |
| **Alternative Flow** | **Jika Stok Tidak Cukup:**<br>1. Apoteker menandai resep "Obat Tidak Lengkap" di sistem.<br>2. [cite_start]Apoteker menjelaskan kepada pasien[cite: 88]. |
| **Post-condition** | [cite_start]Pasien menerima obat (atau penjelasan) dan meninggalkan Puskesmas[cite: 89]. |

---

## 5. Modul Laporan (Kepala Puskesmas)
[cite_start]**Referensi PDF:** Soal B.39-42 [cite: 39-42]

### UC-05: Melihat & Menghasilkan Laporan
**Aktor:** Kepala Puskesmas
**Deskripsi:** Kepala Puskesmas memantau kinerja operasional melalui berbagai jenis laporan (Kunjungan, Penyakit, Obat).

| Komponen | Deskripsi |
| :--- | :--- |
| **Pre-condition** | Login sebagai Kepala Puskesmas. |
| **Main Flow** | 1. Aktor memilih menu "Laporan".<br>2. Sistem menampilkan pilihan jenis laporan: **Kunjungan Poli**, **10 Penyakit Terbanyak**, atau **Pemakaian Obat**.<br>3. Aktor memilih jenis laporan dan memasukkan filter periode (tanggal awal - akhir).<br>4. Sistem memproses data dari database.<br>5. Sistem menampilkan rekapitulasi data dalam bentuk tabel/grafik.<br>6. Aktor menekan tombol "Cetak" atau "Export". |
| **Post-condition** | Laporan fisik/file tercetak. |

---

## 6. Modul Admin Sistem
[cite_start]**Referensi PDF:** Soal B.43-45 [cite: 43-45]

### UC-06: Mengelola Data Pengguna
**Aktor:** Admin Sistem
**Deskripsi:** Menambah, mengubah, atau menonaktifkan akun pengguna aplikasi (Dokter, Petugas, dll).

| Komponen | Deskripsi |
| :--- | :--- |
| **Pre-condition** | Login sebagai Admin. |
| **Main Flow** | 1. Admin memilih menu "Manajemen User".<br>2. Sistem menampilkan daftar user aktif.<br>3. **Tambah User:** Admin input Username, Password, Nama Lengkap, dan **Role** (Pendaftaran/Dokter/Apoteker/dll).<br>4. **Edit User:** Admin mengubah data profil atau reset password.<br>5. **Hapus/Nonaktifkan:** Admin mengubah status user menjadi tidak aktif. |
| **Post-condition** | Data user terupdate di database `users`. |

### UC-07: Mengelola Master Data
**Aktor:** Admin Sistem
**Deskripsi:** Mengatur data referensi utama seperti daftar Poli, Daftar Obat, dan **Tarif Layanan**.

| Komponen | Deskripsi |
| :--- | :--- |
| **Pre-condition** | Login sebagai Admin. |
| **Main Flow** | 1. Admin memilih menu Master Data.<br>2. Admin memilih kategori (misal: **Data Poli**, **Data Obat**, atau **Data Tarif**).<br>3. Admin melakukan operasi CRUD (Create, Read, Update, Delete) pada data tersebut.<br>4. Sistem menyimpan perubahan. |
| **Post-condition** | Referensi data sistem terupdate (misal: harga obat berubah, nama poli berubah). |