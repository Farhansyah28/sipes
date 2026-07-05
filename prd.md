# PRD - SIPES (Sistem Informasi ERP Pesantren)

## 1. Product Vision

Membangun platform ERP Pesantren modern berbasis web yang mampu mengelola seluruh proses akademik, administrasi, keuangan, asrama, dan operasional pesantren dalam satu sistem terintegrasi.

---

# 2. Technology Stack

## Backend

* Laravel 12 (Recommended)
* PHP 8.3+
* MySQL 8

## Frontend

* Blade
* TailwindCSS
* AlpineJS

## Authentication

* Laravel Breeze

## Permission

* Spatie Laravel Permission

## Storage Layer

### Phase 1

Shared Hosting Compatible

Driver:

* Local Storage
* Public Storage

### Phase 2

S3 Compatible Storage

Contoh:

* MinIO
* Cloudflare R2
* AWS S3

Storage driver dapat diganti melalui environment variable tanpa perubahan kode.

---

# 3. Deployment Strategy

## Development

Localhost

## Production Awal

Shared Hosting

Target:

* cPanel Hosting
* DirectAdmin Hosting

## Production Growth

VPS

## SaaS Scale

Multi VPS / Cloud Infrastructure

---

# 4. Multi-Tenant Architecture

Model:

Shared Database Multi Tenant

Semua tabel memiliki:

tenant_id

Contoh:

santris
kelas
tagihan
transaksi
kamar

Keuntungan:

* Cocok untuk shared hosting
* Murah
* Mudah maintenance

---

# 5. User Roles

## Super Admin

Full Access

## Admin PSB

Kelola calon santri

## Bagian Keuangan

Tagihan
Pembayaran
Tabungan

## Ustadz/Ustadzah

Absensi
Nilai
Raport

## Musyrif

Kamar
Penempatan santri

## Kasir Kantin

POS
Stok

## Orang Tua

View only

---

# 6. Module Specification

## Modul PSB

### Features

* Form pendaftaran
* Upload dokumen
* Penilaian tes
* Verifikasi
* Kelulusan
* Daftar ulang

### Status

Draft
Verifikasi
Tes
Lulus
Tidak Lulus
Daftar Ulang
Aktif

---

## Modul Akademik

### Features

* Tahun ajaran
* Semester
* Kelas
* Jadwal
* Guru
* Absensi
* Nilai
* Raport

### Raport PDF

Generate otomatis.

---

## Modul Asrama

### Features

* Master Gedung
* Master Kamar
* Penempatan
* Riwayat Pindah

---

## Modul Keuangan

### Features

* Tagihan
* Invoice
* Pembayaran
* Verifikasi Bukti Transfer Portal
* Ledger (Buku Kas Sentral / General Ledger)
* Pencatatan Kas Manual
* Pencegahan Double Counting antara Kas Tunai dan Tabungan
* Laporan

### Database Schema (Tambahan Keuangan)
**Tabel `kas_pesantrens`**
- `tenant_id`
- `tanggal` (date)
- `tipe` (Masuk, Keluar)
- `kategori` (SPP, Kantin POS, Operasional, dsb)
- `nominal` (integer)
- `keterangan` (text)
- `referensi_type` (polymorphic relation)
- `referensi_id` (polymorphic relation)

### Future Ready

* Midtrans
* Xendit
* Tripay

---

## Modul Tabungan

### Features

* Setoran
* Penarikan
* Mutasi
* Mass Deposit
* Mass Withdrawal

---

## Modul Kantin

### Features

* Master Barang
* Kategori
* Supplier
* FIFO Inventory
* POS
* Void Transaksi (Pembatalan)
* Laporan

---

## Modul Portal Orang Tua

### Features

* Dashboard
* Nilai
* Raport
* Pembayaran
* Tabungan
* Jadwal
* Informasi Kamar

---

# 7. Non Functional Requirements

## Security

* Password Hashing
* CSRF Protection
* Audit Log
* Activity Log

## Performance

* Response < 3 detik
* Pagination seluruh tabel

## Availability

* Backup Harian

## Scalability

* Multi Tenant Ready
* S3 Ready
* Queue Ready

---

# 8. Audit Log

Semua aktivitas penting dicatat:

* Login
* Logout
* Tambah Data
* Edit Data
* Hapus Data
* Pembayaran
* Penarikan Tabungan

Data yang dicatat:

* User
* IP Address
* Timestamp
* Aktivitas

---

# 9. Roadmap

Phase 1

* Master Data
* PSB
* Akademik
* Asrama

Phase 2

* Keuangan
* SPP
* Tabungan

Phase 3

* Kantin POS FIFO

Phase 4

* Portal Orang Tua

Phase 5

* Payment Gateway
* WhatsApp Gateway

Phase 6

* SaaS Multi Tenant Production
