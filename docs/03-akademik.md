# Dokumentasi Modul: Akademik

## Deskripsi Singkat
Modul Akademik berfokus pada kegiatan belajar mengajar santri yang telah berstatus `Aktif`. Modul ini mengelola penjadwalan pelajaran, pencatatan absensi kehadiran, penginputan nilai/raport, hingga proses otomatisasi kenaikan kelas di akhir tahun ajaran.

## Fitur & Menu
1. **Penugasan Ustadz & Pengampu Pelajaran**
   - Mengatur ustadz mana yang mengajar mata pelajaran apa, di kelas mana, beserta beban Jam Pelajaran (JP).
   - Mendata `ketersediaan_ustadzs` (waktu kosong guru) untuk keperluan penyusunan jadwal.

2. **Auto Scheduler (Mesin Penjadwalan Cerdas)**
   - Fitur unggulan algoritma penyusun jadwal otomatis.
   - Memastikan tidak ada bentrok (*clash*) ustadz mengajar di dua kelas pada jam yang sama, mempertimbangkan ketersediaan jam mengajar ustadz.

3. **Manajemen Jadwal Pelajaran (Manual)**
   - Antarmuka untuk menyesuaikan, mengubah, atau membuat jadwal pelajaran secara manual yang terikat dengan `Jam Pelajaran` dan `Kelas`.

4. **Pencatatan Absensi**
   - Guru / admin mencatat kehadiran santri (Hadir, Sakit, Izin, Alpha) berdasarkan jadwal kelas per hari.

5. **Penilaian & Raport**
   - Penginputan nilai tugas, UTS, UAS per mata pelajaran.
   - Fitur kompilasi nilai akhir menjadi tampilan Raport (PDF/Print).

6. **Kenaikan Kelas (Bulk Promotion)**
   - Mengelola kenaikan tingkat kelas santri di akhir tahun ajaran.
   - Memindahkan seluruh santri secara massal (bulk) ke kelas tingkat selanjutnya, sekaligus menyimpan jejak kelas lama di tabel `riwayat_kelas`.

## Tabel Database Terkait
- `pengampu_pelajarans`: Relasi guru dengan mata pelajaran dan kelas.
- `ketersediaan_ustadzs`: Data jam kosong guru.
- `jam_pelajarans`: Referensi jam ke-1 sampai ke-n.
- `jadwals`: Jadwal tetap pelajaran (hasil auto-schedule atau manual).
- `absensis`: Log presensi santri.
- `nilais`: Rekapitulasi nilai pelajaran santri.
- `riwayat_kamars` / `riwayat_kelas` (jika dipisah): History akademik santri.

## Alur Kerja (Workflow)
1. **Pesiapan Beban Mengajar**: Admin menentukan beban mengajar di Penugasan Ustadz.
2. **Penjadwalan**: Admin mengeksekusi *Auto Scheduler* untuk menghasilkan tabel `jadwals`.
3. **KBM Berjalan**: Setiap hari Ustadz mengisi `absensis` berdasarkan jadwal. Saat musim ujian, Ustadz mengisi `nilais`.
4. **Akhir Tahun**: Admin mengeksekusi Kenaikan Kelas, sistem menyimpan kelas lama dan mengubah atribut `kelas_id` santri ke kelas yang baru.
