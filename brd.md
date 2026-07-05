# BRD - Sistem Informasi ERP Pesantren (SIPES)

## 1. Executive Summary

SIPES (Sistem Informasi ERP Pesantren) adalah platform terintegrasi yang dirancang untuk mengelola seluruh proses operasional pesantren dalam satu sistem terpusat.

Sistem mencakup:

* Penerimaan Santri Baru (PSB)
* Akademik
* Asrama
* Keuangan dan SPP
* Tabungan Santri
* Kantin/POS
* Portal Orang Tua
* Manajemen Ustadz/Ustadzah
* Multi-Tenant SaaS Ready

Target awal sistem adalah pesantren dengan kapasitas 200-1000 santri dan dapat berkembang menjadi platform yang digunakan oleh banyak pesantren.

---

# 2. Business Goals

## Tujuan Utama

* Digitalisasi proses administrasi pesantren.
* Mengurangi pencatatan manual.
* Memusatkan seluruh data santri dalam satu sistem.
* Mempermudah monitoring akademik dan keuangan.
* Menyediakan akses informasi bagi wali santri.
* Menjadi produk SaaS ERP Pesantren yang dapat dijual ke pesantren lain.

---

# 3. Stakeholder

## Internal

* Pimpinan Pesantren
* Admin PSB
* Bagian Keuangan
* Ustadz/Ustadzah
* Musyrif
* Kasir Kantin
* Super Admin

## Eksternal

* Santri
* Orang Tua/Wali Santri

---

# 4. Scope Sistem

## In Scope

### Master Data

* Santri
* Orang Tua
* Ustadz/Ustadzah
* Mata Pelajaran
* Kelas
* Tahun Ajaran
* Semester
* Gedung
* Kamar

### PSB

* Pendaftaran online
* Pendaftaran offline
* Verifikasi berkas
* Penilaian tes
* Daftar ulang

### Akademik

* Jadwal
* Absensi
* Nilai
* Raport
* Wali kelas
* Guru pengganti

### Asrama

* Gedung
* Kamar
* Penempatan santri
* Riwayat perpindahan

### Keuangan

* Tagihan
* Invoice
* Pembayaran
* SPP
* Laundry
* Seragam
* Buku
* Kas kelas
* Tagihan custom
* Verifikasi pembayaran portal (Admin Kasir)
* **Buku Kas Sentral (General Ledger): Pusat pencatatan seluruh mutasi uang tunai secara *real-time* dari seluruh modul untuk mencegah _double counting_ (Pemisahan uang tunai vs liabilitas/tabungan).**

### Tabungan

* Setor
* Tarik
* Mutasi
* Setoran massal
* Penarikan massal

### Kantin

* Barang
* Stok
* FIFO
* Penjualan
* Pembelian
* Retur
* Void transaksi (Pembatalan)
* Stock opname

### Portal Orang Tua

* Nilai
* Raport
* Tagihan
* Pembayaran
* Tabungan
* Jadwal
* Data kamar

---

# 5. Out of Scope V1

* Payroll
* Face Recognition
* E-learning
* CBT Online
* Mobile App Native
* Integrasi Payment Gateway Aktif
* Integrasi WhatsApp Aktif

Namun struktur database dan arsitektur harus siap untuk implementasi di masa depan.

---

# 6. Success Metrics

* 100% data santri tersimpan digital.
* Pengurangan proses administrasi manual minimal 80%.
* Pembuatan raport < 1 menit.
* Pencarian data santri < 3 detik.
* Akurasi transaksi keuangan > 99%.

---

# 7. Business Rules

* Satu santri hanya memiliki satu status aktif pada satu waktu.
* Santri dapat berpindah kamar dengan riwayat tersimpan.
* Guru dapat mengajar lebih dari satu mata pelajaran.
* Guru dapat menjadi wali kelas.
* Semua transaksi keuangan wajib memiliki audit trail.
* Semua data alumni harus tetap tersimpan.
* Stok kantin menggunakan metode FIFO.
* Hak akses menggunakan RBAC (Role Based Access Control).
