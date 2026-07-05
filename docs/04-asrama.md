# Dokumentasi Modul: Asrama

## Deskripsi Singkat
Modul Asrama adalah pusat pengelolaan akomodasi bagi santri mukim (boarding). Modul ini membantu pesantren memonitor ketersediaan ranjang/kapasitas asrama, perpindahan santri antar kamar, serta manajemen bangunan fisik asrama. Modul ini umumnya dikelola oleh Musyrif/Musyrifah (Pembina Asrama).

## Fitur & Menu
1. **Manajemen Gedung**
   - Pendataan blok bangunan (Gedung Putra / Gedung Putri).
   - Menjamin segregasi gender di level bangunan sehingga tidak ada insiden salah kamar lintas gender.

2. **Manajemen Kamar & Kapasitas**
   - Mendefinisikan kamar di dalam gedung.
   - Pengaturan batas maksimal penghuni per kamar (`kapasitas`).
   - Indikator visual tingkat keterisian kamar di Dashboard (misal: Kamar Abu Bakar terisi 5/10).

3. **Plotting Kamar Santri**
   - Menempatkan santri ke kamar tertentu.
   - Sistem secara otomatis akan mencegah penempatan santri jika kapasitas kamar sudah penuh atau beda gender.

4. **Riwayat Perpindahan (History)**
   - Mendata rekam jejak santri selama tinggal di asrama (Pindah dari Kamar A ke Kamar B dengan tanggal rotasi yang akurat).
   - Membantu pembina untuk melakukan evaluasi perilaku dan sirkulasi pergaulan santri.

## Tabel Database Terkait
- `gedungs`: Referensi bangunan asrama (Atribut: nama, jenis_kelamin).
- `kamars`: Sub-bagian dari gedung (Atribut: gedung_id, kapasitas).
- `santris`: Entitas penghuni (Terdapat relasi `kamar_id`).
- `riwayat_kamars`: Log perpindahan tempat tinggal santri.

## Alur Kerja (Workflow)
1. **Setup Fasilitas**: Admin membuat Gedung, kemudian menambahkan Kamar beserta detail kapasitas ranjang.
2. **Penempatan Santri Baru**: Pada saat awal tahun ajaran atau kelulusan PSB, santri yang bersatus aktif akan "di-plot" ke kamar yang masih memiliki slot kosong.
3. **Rotasi Kamar**: Jika ada kebijakan perputaran/rotasi kamar semesteran, admin asrama memindahkan santri. Sistem mencatat waktu pindah di tabel `riwayat_kamars` untuk dokumentasi dan pelacakan historis.
