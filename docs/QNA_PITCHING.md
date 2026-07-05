# Q&A Pitching (Tanya Jawab Calon Klien)

Dokumen ini berisi daftar pertanyaan yang paling sering ditanyakan oleh Pengurus Pesantren/Yayasan saat Anda mempresentasikan (pitching) aplikasi ERP ini, beserta cara menjawabnya yang meyakinkan.

---

## Kategori: Akademik & Santri

**Q1: "Bagaimana sistem ini menangani pergantian Tahun Ajaran Baru? Apakah repot input ulang?"**
**Jawaban (Pitch):** "Sangat mudah, Pak/Bu. Cukup buat Tahun Ajaran baru di Master Data dan set menjadi 'Aktif'. Kemudian masuk ke menu 'Kenaikan Kelas'. Sistem akan menampilkan santri kelas 7, tinggal centang semua, klik naik ke kelas 8. Hanya butuh 2 klik untuk memindahkan ratusan santri. Data historis (riwayat kelas sebelumnya) akan otomatis tersimpan permanen."

**Q2: "Bagaimana kalau di pertengahan jalan ada santri yang berhenti (boyong) atau dikeluarkan?"**
**Jawaban (Pitch):** "Cukup ubah status santri tersebut di profilnya menjadi 'Keluar' atau 'Alumni'. Otomatis santri tersebut tidak akan lagi ditagih SPP di bulan berikutnya, dan tidak akan muncul di absensi kelas. Kalau dia masih punya sisa saldo Tabungan, bendahara tinggal melakukan 'Tarik Tunai' untuk dikembalikan ke orang tua."

**Q3: "Bisa nggak kalau satu ustadz ngajar banyak mata pelajaran atau di banyak kelas? Bentrok nggak jadwalnya?"**
**Jawaban (Pitch):** "Bisa banget! Fitur *Auto Scheduler* kita pakai algoritma cerdas. Sistem akan mengecek jam ketersediaan ustadz. Mustahil jadwalnya bentrok (satu ustadz di 2 kelas pada jam yang sama) karena komputer yang menghitung kombinasinya."

---

## Kategori: Keuangan & Tabungan

**Q4: "Tagihan kita bervariasi. Anak yatim SPP-nya gratis atau diskon, anak reguler bayar full. Sistem ini kaku nggak?"**
**Jawaban (Pitch):** "Sistem kami fleksibel. Bendahara bisa membuat *Kategori Tagihan* khusus, atau setelah tagihan massal (*bulk*) dibuat, bendahara bisa mengedit nominal invoice untuk santri tertentu (misal: diskon anak yatim) sebelum dibayar oleh orang tua."

**Q5: "Bagaimana kalau wali santri ada yang gaptek, nggak punya HP pintar buat buka Portal Wali Santri?"**
**Jawaban (Pitch):** "Tidak masalah. Pendekatan kita *hybrid*. Santri tetap bisa bayar uang tunai ke loket tata usaha, lalu kasir yang akan mencatatnya di sistem. Bukti pembayaran (invoice) juga bisa dicetak di atas kertas dan diberikan langsung ke santri."

**Q6: "Apakah uang tabungan santri aman? Nanti dikira dipakai pesantren."**
**Jawaban (Pitch):** "Sangat transparan. Setiap kali santri jajan di kantin atau orang tua kirim uang, tercatat di 'Mutasi Tabungan' seperti buku tabungan bank. Orang tua bisa memantau aliran uang anaknya Rp 100 perak pun dari portal kapan saja."

---

## Kategori: Kantin & Operasional

**Q7: "Kasir kantin kita kadang lupa harga modal, jadi jualnya kemurahan. Gimana solusinya?"**
**Jawaban (Pitch):** "Aplikasi menggunakan sistem *Batch Stok (FIFO)*. Saat belanja grosir/kulakan, admin input harga beli pokoknya. Kasir saat jualan hanya akan melihat Harga Jual yang sudah di-lock. Di belakang layar, sistem otomatis menghitung Laba Kotor harian untuk pesantren."

**Q8: "Gimana kalau santri iseng ngaku-ngaku saldo tabungannya masih banyak pas jajan di kantin?"**
**Jawaban (Pitch):** "Kasir akan menggunakan pembayaran 'Tabungan' di sistem POS. Sistem otomatis menolak transaksi (error) jika saldo santri tidak cukup. Tidak ada celah untuk kasbon tanpa izin."

---

## Kategori: Teknis & Keamanan

**Q9: "Apakah data pesantren saya nanti tercampur dengan data pesantren lain yang pakai aplikasi ini?"**
**Jawaban (Pitch):** "100% Tidak. Kami menggunakan arsitektur *Multi-Tenant*. Secara teknis, database dan ruang penyimpanan Pesantren A dan Pesantren B dipisah secara total (sekat beton). Data Bapak/Ibu terisolasi dan eksklusif."

**Q10: "Kalau internet mati, apakah aplikasinya masih bisa dipakai?"**
**Jawaban (Pitch):** *(Sesuaikan dengan model deployment Anda)* "Karena ini *Cloud ERP*, tentu butuh internet. Namun, aplikasi di-desain sangat ringan sehingga pakai koneksi *tethering* HP 4G yang standar pun aplikasi tetap berjalan mulus dan cepat."
