# Phase 4: Portal Orang Tua Documentation

Dokumen ini memuat panduan teknis dan spesifikasi operasional untuk **Fase 4** dari pengembangan ERP Pesantren (SIPES), yaitu **Portal Orang Tua**.

Modul ini adalah *Customer Facing Application* pertama yang akan diakses langsung oleh wali santri dari luar lingkungan pesantren. Fokus utamanya adalah transparansi informasi (Akademik, Finansial, Asrama) secara *real-time*.

---

## 1. Arsitektur Akses & Multi-Santri
Satu orang tua (wali) mungkin menyekolahkan lebih dari satu anak di pesantren yang sama. Oleh karena itu, arsitektur portal tidak boleh bersifat 1-to-1 dengan data santri.

- **Autentikasi (Login)**: Memanfaatkan tabel `orang_tuas` sebagai *User*. *Username* yang paling disarankan adalah **Nomor HP / WhatsApp** atau *Email* aktif milik orang tua, dengan fitur *Reset Password* terintegrasi ke modul WhatsApp Gateway (Fase 5).
- **Session Switcher**: Saat orang tua masuk (login), *dashboard* harus menyediakan *Dropdown/Switcher* "Pilih Anak" untuk berpindah profil jika ia memiliki 2 atau 3 `santri_id` yang saling berelasi (`orang_tua_id` yang sama). Seluruh menu di bawahnya akan menyesuaikan datanya dengan `santri_id` yang sedang aktif dipilih.

---

## 2. Fitur Transparansi Finansial (Fase 2 Read-Only)
Modul ini langsung mengambil *query* dari transaksi yang dikelola Kasir pada Fase 2.

### A. Tagihan & Pembayaran
Orang tua dapat meninjau kewajiban finansial tanpa perlu bertanya ke Tata Usaha.
- **Daftar Tagihan Belum Lunas**: Menampilkan SPP, uang gedung, dsb yang melewati batas jatuh tempo dengan tulisan berwarna merah.
- **Riwayat Pembayaran**: Memungkinkan wali santri mengunduh Kuitansi (PDF) dari pembayaran yang sudah berstatus `Lunas`.
- **Upload Bukti Bayar**: Karena Fase 4 ini dibangun sebelum Fase 5 (Otomatisasi Midtrans) beroperasi penuh, maka harus disediakan *form upload* bagi wali santri untuk mengunggah gambar resi transfer manual. Aksi ini mengubah status pembayaran menjadi `Menunggu Verifikasi`.

### B. Pemantauan Tabungan & Uang Saku
- **Cek Saldo Terkini**: Langsung *query* dari tabel `tabungans`.
- **Riwayat Mutasi**: Orang tua bisa melacak jajan anaknya secara presisi (Misal: "Debit Rp 10.000 - Kantin A - Tanggal 15/06/2026 Pukul 10:00"). Hal ini memastikan akuntabilitas pesantren atas dana amanah dari wali santri.

---

## 3. Fitur Transparansi Akademik (Fase 1 Read-Only)
Wali santri tidak perlu menunggu akhir semester untuk melihat perkembangan buah hatinya.

### A. Nilai Harian & Jadwal
- **Jadwal Pelajaran**: Menampilkan jadwal aktif dari kelas sang anak (Relasi: `santri -> kelas -> jadwal -> mata_pelajaran & guru`).
- **Nilai Harian**: Menampilkan rekapitulasi ujian harian/kuis.
- **Raport Digital**: Dapat mengunduh *PDF Raport* yang telah di- *generate* dan difinalisasi oleh ustadz pada akhir semester.

### B. Kehadiran (Absensi)
- Menampilkan grafik kehadiran harian (Hadir, Sakit, Izin, Alpa).
- **Alert System**: Di masa depan, jika anak mendapat "Alpa" di jam pertama, sistem bisa langsung *trigger* notifikasi ke layar orang tua.

---

## 4. Fitur Informasi Asrama
Memberikan rasa tenang bagi orang tua yang terpisah jauh dari anaknya.
- **Informasi Kamar & Gedung**: Menampilkan anak berada di Gedung apa, Kamar berapa.
- **Kontak Musyrif (Pembina)**: Sangat krusial. Sistem menampilkan Nama dan tombol WhatsApp/Telepon ke Musyrif kamar yang bersangkutan agar orang tua tahu siapa wali asrama yang bertanggung jawab secara langsung.

---

## Standar Performa & Tampilan (SOP Developer)
1. **Mobile-First Design**: Portal ini hampir 100% akan diakses orang tua melalui _smartphone_. Tim _frontend_ wajib menyusun tampilan menggunakan standar **Responsive UI** (memanfaatkan TailwindCSS `md:`, `lg:` *breakpoints*) agar desain menyerupai aplikasi *native* saat dibuka di HP.
2. **Penerapan Aturan Warna (60-30-10)**: Karena pengguna kita sangat menyukai estetika gradien warna hijau, desain UI portal orang tua harus tetap taat pada pedoman warna `60% Slate-50`, `30% Emerald-500`, `10% Amber-500` seperti yang telah dibangun di _Portal Login_.
3. **Query Optimization**: Karena data akan diakses oleh ratusan wali santri secara bersamaan (misalnya saat hari pembagian raport), _developer_ wajib menggunakan teknik **Eager Loading** (contoh: `Santri::with('tagihan', 'kelas')`) untuk menghindari N+1 _Query Problem_ yang bisa melumpuhkan database.

*Dokumen ini merupakan standar panduan teknis (Blueprint) untuk fase pengembangan Portal Eksternal (Fase 4) pada Sistem Informasi Pesantren (SIPES).*
