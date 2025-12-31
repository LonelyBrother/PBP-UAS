# 🚀 Sistem Pemilihan Motor Berbasis Web

![Laravel](https://img.shields.io/badge/Laravel-11-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange?logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green)

Aplikasi sistem pendukung keputusan untuk memilih motor terbaik
menggunakan metode TOPSIS.


Aplikasi ini menyediakan fitur pengelolaan data motor, pembeli, pelunasan, serta proses perhitungan dan perangkingan motor secara objektif dan terstruktur.

---

## 📌 Tujuan Aplikasi
- Membantu pengguna menentukan motor terbaik berdasarkan kriteria tertentu
- Menerapkan metode **SPK (Sistem Pendukung Keputusan)** secara nyata
- Menyediakan sistem manajemen data motor, pembeli, dan pelunasan
- Menyajikan hasil perhitungan TOPSIS secara transparan dan mudah dipahami

---

## 🛠️ Teknologi yang Digunakan

| Teknologi | Keterangan |
|---------|-----------|
| **Laravel 11** | Framework backend PHP |
| **PHP 8+** | Bahasa pemrograman utama |
| **MySQL** | Database relasional |
| **AdminLTE** | Template dashboard |
| **Bootstrap 5** | UI & layout |
| **Font Awesome** | Ikon |
| **SweetAlert2** | Dialog konfirmasi |
| **DomPDF** | Export PDF |
| **Maatwebsite Excel** | Import & Export Excel |

---

## 👤 Role Pengguna

### 🔑 Admin
- Login ke sistem
- Mengelola data motor (CRUD)
- Mengelola data pembeli
- Mengelola data pelunasan
- Mengimpor & mengekspor data motor
- Mencetak data motor berdasarkan filter tanggal
- Mengelola bobot TOPSIS
- Melihat dan mencetak hasil perangkingan

### 👥 User
- Login ke sistem
- Melihat data motor
- Melihat hasil ranking TOPSIS
- Melihat detail motor

---

## 📂 Fitur Utama

### 1️⃣ Autentikasi
- Login & logout
- Proteksi halaman menggunakan middleware `auth`

### 2️⃣ Manajemen Data Motor
- CRUD motor per kategori:
  - Listrik
  - Matic
  - Sport
- Upload gambar motor
- Tanggal import otomatis (datetime)
- Pagination & pencarian

### 3️⃣ Manajemen Pembeli
- Input data pembeli
- Relasi wilayah (provinsi, kabupaten, kecamatan)
- Hak akses admin

### 4️⃣ Manajemen Pelunasan
- Pencatatan pelunasan motor
- Status: `Lunas / Belum Lunas`
- Satu pelunasan dapat memuat banyak motor
- Relasi ke pembeli

### 5️⃣ Sistem Pendukung Keputusan (TOPSIS)
- Input bobot kriteria
- Perhitungan TOPSIS otomatis
- Ranking per kategori motor
- Tampilan ranking modern (dark theme)
- Detail nilai tiap kriteria

### 6️⃣ Export & Cetak
- Export Excel data motor
- Export PDF
- Cetak data motor berdasarkan:
  - Kategori
  - Rentang tanggal import
  - Pencarian

---

## 🧮 Kriteria Penilaian Motor

| Kriteria | Jenis |
|--------|------|
| Harga | Cost |
| Kenyamanan | Benefit |
| Perawatan | Benefit |

---

## 🧾 Struktur Tabel Utama

### 📌 Tabel `motor`
- `id`
- `nama_motor`
- `brand`
- `kategori`
- `harga`
- `kenyamanan`
- `perawatan`
- `gambar`
- `tanggal_import`
- `created_at`
- `updated_at`

### 📌 Tabel `pembeli`
- `id`
- `nama`
- `alamat`
- `telepon`
- `provinsi_id`
- `provinsi_nama`
- `kabupaten_id`
- `kabupaten_nama`
- `kecamatan_id`
- `kecamatan_nama`
- `created_at`
- `updated_at`

### 📌 Tabel `pelunasan`
- `id`
- `no_pelunasan`
- `pembeli_id`
- `status`
- `tanggal_pelunasan`
- `daftar_motor` (JSON)
- `created_at`
- `updated_at`

---

## 🔁 Alur Aplikasi

### Alur Admin
1. Login
2. Kelola data motor
3. Kelola data pembeli
4. Input pelunasan
5. Atur bobot TOPSIS
6. Lihat ranking motor
7. Export / cetak data

### Alur User
1. Login
2. Lihat data motor
3. Lihat hasil ranking TOPSIS
4. Lihat detail motor

---

## ⚙️ Instalasi & Setup

```bash
git clone https://github.com/username/nama-repo.git
cd nama-repo
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

## 📜 License
This project is licensed under the MIT License.
See the LICENSE file for details.
