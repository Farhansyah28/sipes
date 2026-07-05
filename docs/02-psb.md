# Dokumentasi Modul: Penerimaan Santri Baru (PSB)

## Deskripsi Singkat
Modul PSB menangani siklus hidup pendaftaran santri baru, mulai dari pengisian formulir pendaftaran secara mandiri oleh calon orang tua santri, pengunggahan dokumen kelengkapan, verifikasi panitia, input nilai tes seleksi, hingga penetapan status kelulusan.

## Fitur & Menu
1. **Formulir Pendaftaran Online (Publik)**
   - Dapat diakses tanpa harus login (publik).
   - Pengisian biodata calon santri (NISN, Nama, Tempat/Tanggal Lahir).
   - Pengisian data orang tua (Nama Ayah/Ibu, Nomor HP, Alamat).
   
2. **Cek Status Pendaftaran**
   - Calon santri memantau progres seleksi secara mandiri dengan memasukkan NISN dan Tanggal Lahir.
   - Status pendaftaran berubah secara real-time (Draft -> Verifikasi -> Tes -> Lulus / Tidak Lulus).

3. **Verifikasi Dokumen (Admin)**
   - Panitia PSB (Admin) mengecek dokumen persyaratan (seperti Kartu Keluarga, Akta Kelahiran).
   - Status dokumen dapat diatur menjadi `Valid` atau ditolak dengan memberikan `Catatan`.

4. **Penilaian Tes Seleksi**
   - Menginput nilai tes akademik maupun non-akademik (tes mengaji, wawancara).
   - Nilai terekam dengan referensi penguji (Ustadz/Penilai).

5. **Penentuan Kelulusan & Import Massal**
   - Fitur penetapan status akhir calon santri.
   - Apabila santri "Lulus", secara otomatis sistem dapat memindahkannya sebagai santri aktif di modul Akademik dan Keuangan.
   - Mendukung fitur *Import Excel* calon santri bagi pendaftaran kolektif.

## Tabel Database Terkait
- `santris`: Entitas inti (filter `status` = 'Draft' / 'Verifikasi' / 'Tes' / 'Lulus').
- `orang_tuas`: Relasi data wali santri dari pendaftaran.
- `dokumen_pendaftarans`: Berkas unggahan pendaftar.
- `penilaian_tes`: Nilai seleksi masuk.

## Alur Kerja (Workflow)
1. **Pendaftaran**: Pendaftar mengisi form di `/psb/pendaftaran`. Sistem membuat record di tabel `santris` (status: `Draft`) dan `orang_tuas`.
2. **Unggah Berkas**: Pendaftar melengkapi berkas, status berubah menjadi `Verifikasi`.
3. **Verifikasi Admin**: Admin PSB memeriksa `dokumen_pendaftarans`. Jika valid, status naik menjadi `Tes`.
4. **Seleksi Ujian**: Admin input nilai di tabel `penilaian_tes`.
5. **Finalisasi**: Admin mengubah status santri menjadi `Lulus` atau `Tidak Lulus`.
