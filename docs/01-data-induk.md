# Dokumentasi Modul: Data Induk (Master Data)

## Deskripsi Singkat
Modul Data Induk berfungsi sebagai fondasi dari seluruh sistem ERP Pesantren. Modul ini mengelola entitas dasar yang akan digunakan oleh modul-modul lain seperti Data Pengguna, Tahun Ajaran, Kelas, Ustadz, dan Mata Pelajaran. Tanpa data induk yang terkonfigurasi dengan baik, modul lain tidak akan dapat berjalan secara maksimal.

## Fitur & Menu
1. **Manajemen Pengguna & Role (Akses Control)**
   - Mengatur daftar akun pengguna yang bisa masuk ke sistem.
   - Hak Akses (Role) berbasis Spatie Permission (Super Admin, Admin PSB, Ustadz, Keuangan, Kasir Kantin, Orang Tua, Musyrif).
   - Pengamanan hapus paksa untuk pengguna berstatus `Super Admin`.

2. **Manajemen Tahun Ajaran & Semester**
   - Mendata tahun ajaran aktif dan riwayat tahun ajaran sebelumnya.
   - Mengontrol status `is_active` (hanya boleh ada 1 tahun ajaran / semester aktif pada satu waktu).

3. **Manajemen Data Ustadz (Guru)**
   - Menyimpan biodata lengkap Ustadz (NIP, Nama Lengkap, Jenis Kelamin, Nomor HP).
   - Terintegrasi dengan akun pengguna (Login ustadz).
   - Mendukung fitur *Import Excel* untuk pendaftaran massal.

4. **Manajemen Mata Pelajaran**
   - Mendata kurikulum pesantren beserta kode pelajaran.

5. **Manajemen Kelas**
   - Menetapkan tingkat (contoh: VII, VIII, IX) dan nama kelas.
   - Menentukan Wali Kelas dari daftar Ustadz yang aktif.

## Tabel Database Terkait
- `users`: Menyimpan data akun login dan kredensial.
- `roles`, `permissions`, `model_has_roles`: Manajemen akses berbasis RBAC.
- `tahun_ajarans`, `semesters`: Manajemen siklus akademik.
- `ustadzs`: Biodata guru.
- `mata_pelajarans`: Katalog pelajaran.
- `kelas`: Daftar kelas dan wali kelas.

## Alur Kerja (Workflow)
1. **Setup Awal**: Administrator membuat Tahun Ajaran dan Semester baru, kemudian mengaktifkannya.
2. **Pendaftaran Akun**: Sistem secara otomatis membuat akun login (User) ketika data Ustadz ditambahkan (baik manual maupun via Excel import).
3. **Penyusunan Master Data**: Mata pelajaran dan kelas didaftarkan sebagai persiapan untuk modul Akademik dan Penjadwalan.
