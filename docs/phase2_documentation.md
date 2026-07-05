# Phase 2: Keuangan & Tabungan Documentation

Dokumen ini memuat panduan arsitektur dan spesifikasi teknis untuk **Fase 2** dari pengembangan ERP Pesantren (SIPES). Fase ini sangat kritikal karena berhubungan langsung dengan perputaran uang (arus kas) pesantren, meliputi **Keuangan (Tagihan & SPP)** dan **Tabungan Santri**.

Mengingat tingginya risiko keamanan pada modul finansial, seluruh transaksi di fase ini wajib dibungkus dalam *Database Transaction* (`DB::transaction`) dan setiap mutasi harus memiliki *Audit Log* yang ketat.

---

## 1. Modul Keuangan (Tagihan & Pembayaran)
Modul ini menangani penerbitan tagihan (seperti SPP bulanan, Uang Gedung, Uang Seragam) serta pencatatan pembayarannya.

### Arsitektur Entity
- **`kategori_tagihans`**: Tabel master untuk jenis-jenis tagihan (Contoh: SPP, Uang Makan, Uang Pangkal). Memiliki pengaturan *is_rutin* (bulanan/tahunan).
- **`tarif_tagihans` (Skema Harga)**: Tabel referensi yang memetakan relasi antara `kategori_tagihan_id` dengan pengelompokan santri (berdasarkan `kelas_id` atau `tahun_masuk`). Hal ini wajib agar sistem mengetahui bahwa Santri Kelas VII mungkin memiliki SPP yang berbeda dengan Santri Kelas IX.
- **`diskon_tagihans` (Beasiswa/Potongan)**: Menyimpan preferensi diskon khusus per santri. Misal: Diskon Yatim (100%), Diskon Anak Guru (50%). Data ini menjadi pengurang otomatis saat tagihan di- *generate*.
- **`tagihans`**: Tabel yang memetakan kewajiban final santri. 
  - Field Penting: `santri_id`, `kategori_tagihan_id`, `nominal_awal`, `diskon`, `nominal_akhir`, `tanggal_jatuh_tempo`, `status` (*Belum Lunas*, *Cicilan*, *Lunas*).
- **`rekening_banks` / `kas` (Chart of Accounts)**: Menyimpan data akun kas tujuan (Contoh: Bank BSI Yayasan, Kas Tunai Bendahara). Setiap pembayaran wajib merujuk ke tabel ini agar perpindahan arus kas terekam dengan akurat.
- **`pembayarans` (atau `transaksis`)**: Mencatat rincian pembayaran.
  - Field Penting: `tagihan_id`, `rekening_bank_id`, `jumlah_dibayar`, `metode_pembayaran` (Cash/Transfer/Payment Gateway), `tanggal_bayar`, `petugas_id` (Kasir).
- **`ledgers` (Buku Besar)**: Opsional namun direkomendasikan untuk pembukuan akuntansi ganda (*Double Entry*). Mencatat setiap arus Kas Masuk (Debit) dan Kas Keluar (Kredit).

### Fitur Generate Tagihan Massal (*Bulk Generate*)
Penerbitan tagihan SPP rutin setiap tanggal 1 tidak boleh dilakukan secara manual satu per satu. Sistem wajib memiliki fitur *Bulk Generate* (via *Command* atau UI) yang di- *dispatch* menggunakan **Laravel Job/Queue**. Sistem akan membaca tabel `tarif_tagihans` dan mengurangi dengan `diskon_tagihans` untuk menghasilkan baris `tagihans` baru secara otomatis.

### Otomatisasi Pengingat Jatuh Tempo (*Auto Reminder*)
Sistem menggunakan **Laravel Scheduler (Cron Job)** yang berjalan setiap hari (misal pukul 08:00). Sistem akan melakukan _query_ tagihan yang H-3 atau H+1 dari `tanggal_jatuh_tempo` dan otomatis me- *dispatch* Job pengiriman peringatan (billing reminder) melalui integrasi *WhatsApp Gateway*.

### Alur Verifikasi Pembayaran Manual (*Approval Flow*)
Sebelum *Payment Gateway* terpasang secara utuh, pembayaran via Transfer Bank Manual akan mendominasi.
- Orang tua mengunggah "Bukti Transfer" di Portal.
- Sistem mencatat baris di tabel `pembayarans` dengan *status*: **`Menunggu Verifikasi`**.
- Admin Keuangan mengecek mutasi rekening bank aslinya. Jika dana sudah masuk, Kasir menekan tombol "Validasi", status berubah menjadi **`Sukses/Lunas`**.

### Penerbitan Invoice & Kuitansi (PDF)
- **Nomor Invoice Unik**: Setiap pembayaran sukses men- *generate* referensi unik (Contoh: `INV-SPP-20260626-0001`).
- **Cetak Bukti Bayar**: Tersedia fitur untuk men- *download* atau mencetak *receipt* (kuitansi) PDF sebagai bukti sah.

---

## 2. Modul Laporan Keuangan (Reporting)
Modul ini bertugas merekapitulasi data dari arus kas masuk dan keluar. Laporan yang wajib ada:
1. **Laporan Arus Kas (Harian & Bulanan)**: Dikelompokkan berdasarkan `rekening_banks` (Misal: Total masuk ke BSI vs Kas Tunai).
2. **Laporan Tunggakan Santri**: Menampilkan daftar santri yang belum melunasi tagihan, yang terintegrasi dengan modul Penagihan WA Otomatis.
3. **Laporan Rekapitulasi per Kategori/Kelas**: Laporan agregasi (misal: Total SPP Kelas VII bulan ini) yang dapat diekspor ke Excel (CSV) maupun PDF.

---

## 3. Modul Tabungan (Uang Saku)
Modul ini bertindak layaknya bank internal pesantren. Wali santri menyetorkan uang saku, dan santri dapat menarik atau membelanjakannya di area pesantren.

### Arsitektur Entity
- **`tabungans`**: Menyimpan saldo terkini (`saldo`) dan pengaturan **`limit_harian`** (batas maksimal penarikan harian per santri agar uang tidak cepat habis) untuk tiap `santri_id`.
- **`mutasi_tabungans`**: Sejarah (*ledger* khusus) keluar masuknya uang. Wajib bersifat *Append-Only* (penambahan log saja).
  - Field Penting: `santri_id`, `jenis` (*Kredit/Debit*), `nominal`, `keterangan` (Setoran / Jajan / Tarik Tunai), `petugas_id`.

### Integritas Data & Validasi Ekstra
1. **Pencegahan Saldo Minus & Validasi Limit Harian**: Pada level *Controller*, harus ada validasi ketat: `if ($tabungan->saldo < $request->tarik) throw new Exception;` dan `if ($akumulasiTarikHariIni + $request->tarik > $tabungan->limit_harian) throw new Exception;`.
2. **Race Condition Protection**: Saat penarikan/jajan dilakukan bersaman dari beberapa titik, fungsi potong saldo wajib menggunakan *Pessimistic Locking* (`lockForUpdate()`) agar saldo tidak bocor (minus) akibat bentrok *request*.

### Fitur Operasional Lanjutan
- **Mass Deposit**: Mengimpor (Excel/CSV) atau menginput setoran tabungan ratusan santri secara cepat.
- **Mass Withdrawal**: Pencairan sisa tabungan saat kelulusan massal.

---

## Standar Keamanan Finansial (SOP Developer)
1. **Zero Deletion Policy**: Entitas `pembayarans` dan `mutasi_tabungans` **DILARANG KERAS** menggunakan fitur *Delete/Destroy* biasa. Jika terjadi kesalahan input oleh kasir, gunakan metode pembatalan (*Void* atau penambahan baris mutasi koreksi / *Reversal*) agar riwayat akuntansi tetap seimbang.
2. **Strict Audit Trail**: Menggunakan `spatie/laravel-activitylog`, setiap penambahan atau pembatalan wajib mencatat siapa kasir yang bertugas dan IP address-nya.

*Dokumen ini merupakan standar panduan teknis (Blueprint) untuk fase pengembangan Modul Finansial (Fase 2) pada Sistem Informasi Pesantren (SIPES).*
