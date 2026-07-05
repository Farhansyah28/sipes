# Phase 1: Core Modules Documentation

Dokumen ini berisi panduan teknis dan ringkasan arsitektur untuk **Fase 1** dari sistem ERP Pesantren (SIPES). Fase 1 merupakan fondasi utama berjalannya sistem pesantren, yang mencakup manajemen **Master Data**, **Penerimaan Santri Baru (PSB)**, **Akademik**, dan **Asrama**.

Semua modul pada fase ini harus mematuhi aturan **Multi-Tenant Architecture** (isolasi data antar pesantren/cabang jika dikembangkan lebih lanjut) yang telah didefinisikan dalam `prd.md`.

---

## 1. Modul Master Data
Modul ini merupakan inti referensi data yang akan digunakan oleh seluruh modul lainnya (Keuangan, Tabungan, Akademik, dll). 

### Tabel & Relasi Utama
- **`orang_tuas`**: Menyimpan data wali santri. Memiliki relasi *One-to-Many* ke tabel `santris`. Digunakan untuk _login_ pada Portal Orang Tua.
- **`santris`**: Model sentral dari seluruh sistem. Berisi biodata santri dan berelasi dengan tabel `kelas`, `kamars`, `tagihans`, dan `tabungans`.
- **`gurus` (atau Pengurus)**: Menyimpan data ustadz/ustadzah. Berelasi dengan `jadwals` untuk menentukan siapa yang mengajar mata pelajaran apa di kelas mana.
- **`kelas` & `kamars`**: Referensi pengelompokan santri berdasarkan ruang belajar dan ruang tidur.
- **`mata_pelajarans`**: Referensi mata pelajaran yang diajarkan.

### Standar Pengembangan
- Semua model Master Data wajib menggunakan *Trait* `Tenantable` (jika sistem *multi-tenant* diaktifkan) atau memiliki parameter `tenant_id` secara bawaan.
- **Soft Deletes**: Tabel kritikal seperti `santris` wajib menggunakan `SoftDeletes` bawaan Laravel untuk mencegah hilangnya riwayat keuangan atau akademik apabila data santri tidak sengaja terhapus.
- **Audit & Activity Log**: Mengingat Master Data adalah pondasi utama, setiap operasi *Create, Update, Delete (CUD)* wajib dicatat (log) mencakup: User pelaksana, IP Address, Timestamp, dan rekaman data Sebelum/Sesudah diubah. (Memanfaatkan `spatie/laravel-activitylog`).

---

## 2. Modul Penerimaan Santri Baru (PSB)
Modul ini mengatur alur (*funneling*) pendaftaran calon santri hingga akhirnya diterima dan berstatus "Aktif".

### Alur Status Pendaftaran (Workflow)
Field `status` pada tabel pendaftaran (atau tabel `santris` saat masih calon) harus melewati tahapan (*state machine*) berikut secara sekuensial:
1. **Draft**: Mengisi biodata awal, belum final.
2. **Verifikasi**: Dokumen (KK, Akta, Ijazah) telah diunggah dan menunggu pengecekan Admin.
3. **Tes**: Pendaftar dinyatakan valid dan berhak mengikuti ujian seleksi. Diperlukan tabel **`penilaian_tes`** untuk menampung skor/ujian tertulis maupun wawancara dari setiap calon santri.
4. **Lulus / Tidak Lulus**: Hasil keputusan tes seleksi.
5. **Daftar Ulang**: Santri lulus melakukan pembayaran awal/registrasi ulang.
6. **Aktif**: Santri resmi terdaftar dan berhak masuk ke modul Asrama & Akademik.

### File Storage untuk Dokumen
Berkas pendaftaran disimpan menggunakan mekanisme `Storage::disk('public')` pada fase awal (Shared Hosting), namun *developer* harus selalu memanggil *helper* URL `Storage::url($path)` agar kode siap dialihkan ke *S3-compatible storage* pada tahap *scaling* (Fase SaaS).

---

## 3. Modul Akademik
Mengelola seluruh kegiatan belajar mengajar (KBM) dan riwayat akademis santri.

### Arsitektur Entity
- **Tahun Ajaran & Semester**: Sebagai filter wajib (_global scope_ atau parameter rutin) di seluruh query Absensi, Nilai, dan Jadwal. Tujuannya agar sistem tidak memuat data tahun-tahun sebelumnya secara campur aduk.
- **Jadwal (`jadwals`)**: Tabel persimpangan (*pivot/junction*) yang menghubungkan `kelas`, `mata_pelajarans`, referensi `hari/jam`, dan penugasan `guru` pengampu.
- **Absensi (`absensis`)**: Mencatat kehadiran harian per mata pelajaran atau per hari. Status yang didukung: *Hadir*, *Sakit*, *Izin*, *Alpa*. Data ini akan ditampilkan _real-time_ di Portal Orang Tua.
- **Nilai (`nilais`)**: Relasi `santri_id`, `mata_pelajaran_id`, `semester`, dan skor akhir santri.

### Fitur Penjadwalan Cerdas (*Smart AI Auto-Schedule*)
Sistem pesantren memiliki fitur penyusunan jadwal secara heuristik/otomatis untuk mencegah bentrok jadwal ustadz maupun ruang kelas.
- **`jam_pelajarans`**: Master referensi slot waktu mengajar harian (Misal: Senin Jam 1, Senin Jam 2) termasuk penanda khusus untuk waktu `is_istirahat`.
- **`pengampu_pelajarans`**: Menyimpan pemetaan beban mengajar mingguan (Berapa Jam Pelajaran (JP) seorang Ustadz mengajar suatu Mapel di Kelas tertentu).
- **`ketersediaan_ustadzs`**: Tabel pengecualian constraint waktu. Digunakan jika ada Ustadz yang hanya bisa datang pada hari/jam tertentu.
- **Algoritma *Greedy***: *Service* (`TimetableService`) berjalan memindai slot kosong dan mencocokkan beban ajar melawan ketersediaan ustadz. Jadwal akan di-*rollback* (digagalkan) beserta laporan pesan *error* detail jika algoritma mendeteksi *deadlock* (waktu ustadz kurang dari beban jam, atau bentrok ekstrim).
- **Antarmuka (Dashboard Jadwal)**: Menampilkan hasil akhir menggunakan **Matriks Tabel Grid 2 Dimensi** interaktif dengan integrasi *Alpine.js*. Terdapat fitur tabulasi untuk melihat "*Jadwal Per-Kelas*" dan "*Jadwal Per-Ustadz*" guna memudahkan monitoring Waka Kurikulum.

### Laporan Raport (PDF Generation)
Pembuatan PDF Raport direncanakan berjalan dinamis. Mengingat *render* PDF memakan banyak memori (RAM), fungsi cetak massal (*batch print*) sangat disarankan untuk dipindahkan ke dalam Laravel Queue (*Background Jobs*).

---

## 4. Modul Asrama
Mengelola penempatan ruang istirahat dan perpindahan santri antar gedung/kamar.

### Arsitektur & Logika Perpindahan
- **Master Gedung & Kamar**: Hierarki tempat tinggal. Gedung -> Kamar. Setiap kamar wajib memiliki *field* `kapasitas_maksimal`.
- **Validasi Penempatan**: Sistem wajib memblokir penempatan santri ke suatu kamar apabila total penghuni (Count dari tabel santri dengan `kamar_id` tersebut) telah sama dengan atau melebihi `kapasitas_maksimal`.
- **Penempatan**: Sebuah kolom `kamar_id` di tabel `santris` untuk memperlihatkan letak kamar saat ini.
- **Riwayat Pindah (`riwayat_kamars`)**: **WAJIB** diimplementasikan. Setiap kali Musyrif/Admin mengubah `kamar_id` pada profil santri, sistem harus mencatat rekam jejak (tanggal pindah, dari kamar apa, ke kamar apa, alasan pemindahan). Hal ini penting untuk audit dan mitigasi insiden asrama.

---

## Kesimpulan Keamanan & Akses Data (Authorization)
- Seluruh fitur Modul Fase 1 harus dilindungi menggunakan *Role & Permission* (via `spatie/laravel-permission`). 
- **Admin PSB** hanya bisa mengakses dan mengubah tahapan pada modul PSB.
- **Ustadz/Ustadzah** hanya bisa mengisi Nilai & Absensi sesuai *Jadwal* kelas yang diampunya.
- **Musyrif** (Pembina Asrama) berwenang memindahkan data kamar santri dan melihat kapasitas kamar.

*Dokumen ini merupakan standar panduan (Blueprint) resmi untuk fase pengembangan Modul Inti (Fase 1) pada Sistem Informasi Pesantren (SIPES).*
