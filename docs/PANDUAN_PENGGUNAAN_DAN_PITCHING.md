# Panduan Penggunaan & Pitching Guide (ERP Pesantren)

Dokumen ini dirancang sebagai **Buku Panduan (User Manual)** sekaligus **Contekan Pitching** untuk mempermudah Anda menjelaskan nilai jual (Selling Point) aplikasi ini kepada calon klien (Yayasan/Pesantren). 

---

## 1. Modul Data Induk (Master Data)
**Nilai Jual (Pitching Point):** *"Pesantren tidak perlu lagi repot sinkronisasi data antar divisi. Satu kali input di Data Induk, datanya otomatis mengalir ke Akademik, Keuangan, dan Asrama. Keamanan terjamin karena akses tiap staf dikunci sesuai jabatannya (Role-Based Access Control)."*

### Cara Penggunaan:
1. **Membuat Tahun Ajaran Baru:** 
   - Masuk ke menu `Master Data` -> `Tahun Ajaran`.
   - Klik `Tambah Data`, masukkan nama tahun ajaran (misal: 2026/2027).
   - Set sebagai **Aktif**. Sistem otomatis menonaktifkan tahun ajaran sebelumnya.
2. **Manajemen Hak Akses (User):**
   - Masuk ke `Tambah Pengguna`.
   - Isi Username, Password, dan centang **Role** (misal: Kasir Kantin).
   - Akun tersebut HANYA bisa melihat menu Kantin, tidak bisa mengintip Keuangan.

---

## 2. Modul PSB (Penerimaan Santri Baru)
**Nilai Jual (Pitching Point):** *"100% Paperless! Orang tua bisa daftar dari rumah pakai HP. Pesantren nggak perlu lagi tumpukan map kertas. Proses seleksi transparan dan kalau lulus, santri langsung masuk sistem tanpa perlu input ulang dari nol!"*

### Cara Penggunaan:
1. **Pendaftaran Publik (Orang Tua):**
   - Arahkan calon pendaftar ke halaman depan `/psb/pendaftaran`.
   - Mereka mengisi form biodata, NISN, dan nomor kontak. Sistem membuatkan *draft*.
2. **Cek Status (Orang Tua):**
   - Di halaman `/psb/cek-status`, orang tua bisa masuk pakai NISN dan Tanggal Lahir untuk memantau apakah dokumen mereka sudah divalidasi atau belum.
3. **Verifikasi & Luluskan (Admin):**
   - Panitia PSB login, masuk menu `Data Pendaftar`.
   - Klik `Lihat/Verifikasi` pada salah satu nama.
   - Jika berkas lengkap, ubah status ke **Tes**.
   - Input nilai tes ngaji/wawancara di form `Penilaian`.
   - Terakhir, ubah status menjadi **Lulus**. (Sistem otomatis memindahkan santri ini jadi santri aktif yang siap ditagih SPP-nya).

---

## 3. Modul Akademik
**Nilai Jual (Pitching Point):** *"Selamat tinggal pusing nyusun jadwal manual! Aplikasi ini punya 'Auto Scheduler' cerdas yang otomatis menyusun ratusan jam pelajaran tanpa bentrok, menyesuaikan waktu kosong ustadz. Kenaikan kelas cukup 1 kali klik!"*

### Cara Penggunaan:
1. **Auto Scheduler:**
   - Masuk ke `Akademik` -> `Penugasan Ustadz`. Tentukan Ustadz A mengajar Fiqih di Kelas VII-A sebanyak 4 Jam.
   - Tentukan `Ketersediaan` (misal Ustadz A cuma bisa hari Senin).
   - Masuk ke `Auto Schedule`, klik **Proses Penjadwalan**. Sistem akan mengacak algoritma dan mencetak jadwal otomatis.
2. **Absensi & Nilai:**
   - Guru masuk ke kelas, klik menu `Absensi`, centang santri yang Hadir/Sakit/Izin.
   - Di akhir semester, guru masuk menu `Nilai`, ketik nilai akhir, dan sistem otomatis merekapnya menjadi Raport.
3. **Kenaikan Kelas:**
   - Masuk menu `Kenaikan Kelas`. Pilih kelas asal (VII) dan kelas tujuan (VIII). Centang semua anak, klik `Proses`. 

---

## 4. Modul Asrama
**Nilai Jual (Pitching Point):** *"Kapasitas ranjang terpantau real-time. Pesantren tahu persis anak tidur di kamar mana, gedung apa. Kalau ada santri nakal dan dipindah kamar, history rotasinya terekam permanen!"*

### Cara Penggunaan:
1. **Manajemen Kamar:**
   - Bikin `Gedung` dulu (Pisahkan Putra & Putri).
   - Bikin `Kamar` di dalam gedung itu, set kapasitasnya (misal: 10 ranjang).
2. **Plotting Penghuni:**
   - Saat ada santri masuk, masuk ke menu `Kamar`, klik `Plotting`.
   - Pilih nama santrinya. Kalau kapasitas sisa 0, sistem otomatis nolak.
   - Jika bulan depan dipindah ke kamar lain, sistem akan mencatat tanggal pindahnya di menu `Riwayat Kamar`.

---

## 5. Modul Keuangan & Tabungan
**Nilai Jual (Pitching Point):** *"Revolusi Cashless! Orang tua transfer uang jajan ke tabungan santri, santri jajan di kantin tinggal potong saldo tanpa pegang duit kertas (aman dari kehilangan/pencurian). Buat tagihan SPP bulanan ke ratusan anak cukup 1 klik, sistem tagih otomatis!"*

### Cara Penggunaan:
1. **Generate Tagihan Massal (Bulk Billing):**
   - Awal bulan, bendahara masuk ke `Tagihan Bulk`.
   - Pilih `Kategori` (SPP), klik *Generate*. Sistem langsung bikin 500 invoice ke masing-masing santri.
2. **Tabungan Santri:**
   - Orang tua kirim Rp500.000 buat jajan. Bendahara klik menu `Tabungan` -> `Setor` ke akun anak tersebut.
   - Saldo anak jadi Rp500.000 dan bisa dipantau orang tua dari *Portal Orang Tua*.
3. **Verifikasi Bayar SPP:**
   - Santri mau bayar SPP. Kasir bisa milih: mau bayar pakai Cash tunai, ATAU klik **Potong dari Tabungan**. 
   - Semua uang masuk akan terekam otomatis di `Ledger` (Buku Besar) jadi laporan Laba/Rugi pesantren.

---

## 6. Modul Kantin (Point of Sales)
**Nilai Jual (Pitching Point):** *"Tutup celah kecurangan kasir! Kantin di-upgrade jadi minimarket modern dengan sistem FIFO, cetak struk thermal, dan notifikasi stok menipis. Paling epik: Santri jajan nggak usah pakai uang, kasir tinggal potong saldo tabungan digitalnya!"*

### Cara Penggunaan:
1. **Stok Masuk (Kulakan):**
   - Masuk ke `Batch Stok`. Input beli Indomie 1 Dus dari Supplier A (Harga beli pokok 2.500/pcs).
   - Sistem akan ngitung Laba Kotor dari harga pokok ini vs harga jual.
2. **Transaksi Kasir (Jualan):**
   - Kasir masuk ke menu `Kantin POS`.
   - Pilih barang yang dibeli santri. Total: Rp10.000.
   - Di metode pembayaran, pilih **Tabungan**, masukkan nama/ID santri.
   - Tekan bayar. Sistem akan memotong Rp10.000 dari saldo tabungan anak itu dan cetak struk.
3. **Tutup Shift:**
   - Jam 5 sore, kantin tutup. Kasir cetak `Laporan Shift` untuk setor uang kas tunai ke Bendahara Utama. Laporan sudah dipisah mana uang yang cash, mana uang digital (tabungan).
