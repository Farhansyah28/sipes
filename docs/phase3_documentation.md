# Phase 3: Kantin & POS (Point of Sales) Documentation

Dokumen ini memuat panduan arsitektur dan spesifikasi teknis untuk **Fase 3** dari pengembangan ERP Pesantren (SIPES), yaitu modul **Kantin dan Manajemen Inventori (POS)**.

Modul ini tidak berdiri sendiri, melainkan **terintegrasi langsung secara erat** dengan Modul Tabungan (Fase 2). Konsep utamanya adalah menciptakan ekosistem *cashless* (tanpa uang tunai) di lingkungan pesantren, di mana santri berbelanja menggunakan Kartu Santri/NIS yang memotong saldo tabungan mereka.

---

## 1. Master Data Kantin & Inventori
Sebelum Kantin dapat beroperasi, sistem memerlukan pengelompokan master data barang dan rantai pasok (Supplier).

### Arsitektur Entity
- **`suppliers`**: Pemasok barang ke kantin (Misal: Agen Grosir A, Pabrik Roti B).
- **`kategori_barangs`**: Pengelompokan barang (Makanan Ringan, Minuman, Alat Tulis, Peralatan Mandi).
- **`barangs`**: Master entitas produk yang dijual. 
  - Field Penting: `kategori_id`, `barcode/sku` (penting untuk *scanner* POS), `nama_barang`, `harga_jual`, `stok_minimal` (sebagai *alert* untuk _restock_).
  
> [!NOTE]
> Pada tabel `barangs`, field `harga_beli` tidak dijadikan patokan baku untuk menghitung Laba/Rugi, melainkan sistem menggunakan arsitektur **FIFO (First In First Out)** yang akan dijelaskan di bawah.

---

## 2. FIFO Inventory Architecture (Manajemen Stok)
Sebuah barang bisa dibeli dari *Supplier* beberapa kali dengan harga modal yang fluktuatif. Oleh karena itu, kita tidak bisa memukul rata harga beli saat menghitung keuntungan harian kantin.

### Entity & Logika FIFO
Sistem di Fase 3 ini menuntut pengelolaan stok berbasis *Batch* (tumpukan kedatangan).
- **`pembelian_barangs` (Purchases)**: Header nota belanja ke supplier.
- **`stok_batches` (Inventory Batches)**: Detail barang masuk yang akan menjadi tumpukan stok.
  - Field Penting: `barang_id`, `pembelian_id`, `tanggal_masuk`, `harga_beli_satuan` (modal sesungguhnya), `qty_awal`, **`qty_sisa`** (Stok yang masih tersedia di batch ini).

**Logika Pengurangan Stok (FIFO):**
Saat ada santri membeli 3 buah Buku Tulis, sistem akan mencari tumpukan `stok_batches` Buku Tulis dengan `tanggal_masuk` paling lama (terawal) yang `qty_sisa > 0`. Jika batch pertama hanya sisa 1, sistem akan mengurangi 1 dari batch pertama, lalu mengambil 2 sisanya dari batch kedua.
Mekanisme ini memastikan perhitungan modal (HPP) sangat akurat sesuai barang riil yang keluar.

---

## 3. Modul POS (Point of Sales / Kasir)
Area operasi kasir kantin. Dibangun dengan fokus kecepatan transaksi karena kasir harus melayani antrean santri pada jam istirahat.

### Arsitektur Entity
- **`transaksi_kantins` (Header)**: Menyimpan nota induk.
  - Field Penting: `santri_id` (jika menggunakan tabungan), `total_belanja`, `status`, `tanggal`, `petugas_id`.
- **`transaksi_kantin_details` (Line Items)**: Rincian barang di nota tersebut.
  - Field Penting: `transaksi_kantin_id`, `barang_id`, `qty`, `harga_jual`, **`harga_beli_pokok`** (Didapat secara dinamis dari proses FIFO di atas, fungsinya untuk rekap laba/rugi absolut).

### Integrasi dengan Tabungan (Fase 2)
1. Santri men- *scan* *Barcode/RFID* atau menyebutkan NIS.
2. Aplikasi POS menembak _endpoint_ `Tabungan::where('santri_id', $id)->first()`.
3. Validasi: Jika `total_belanja` melebihi `saldo` tabungan atau melebihi `limit_harian` (seperti dibahas di Fase 2), transaksi **harus ditolak (Error)**.
4. Jika sukses, maka `DB::transaction` akan:
   - Menyimpan *invoice* POS.
   - Memotong persediaan barang (FIFO).
   - Menambahkan *log* pada tabel `mutasi_tabungans` dengan jenis `Debit` dan Keterangan "Jajan Kantin INV-123".

> [!WARNING]
> Sangat penting menggunakan metode **Pessimistic Locking (`lockForUpdate()`)** saat mengurangi `stok_batches` maupun saldo Tabungan untuk menghindari saldo minus atau stok minus jika terjadi transaksi *double-click* atau *concurrent requests*.

### 3.1 Integrasi dengan Buku Kas Sentral (General Ledger)
Untuk menghindari *double counting* pencatatan omzet tunai dan omzet non-tunai (tabungan), sistem memberlakukan regulasi keuangan ketat:
1. **Pembayaran Tunai**: Mencatat **Kas Masuk** pada tabel `kas_pesantrens` dengan kategori "Kantin POS".
2. **Pembayaran Tabungan**: **TIDAK** mencatat di `kas_pesantrens`. Hal ini karena uang fisik telah masuk ke yayasan pada saat Santri melakukan Setor Tabungan (Top-Up), sehingga pencatatan omzet kantin hanya berupa perpindahan nilai liabilitas ke pendapatan secara akuntansi.

### 3.2 Pembatalan Transaksi (Void Kasir)
Sistem dilengkapi dengan fitur pembatalan (*void*) yang aman dan akuntabel.
Jika terjadi kesalahan kasir:
1. Kasir membatalkan nota dengan memberikan alasan (`alasan_batal`).
2. Transaksi ditandai sebagai `Batal`.
3. **Stock Reversal**: Barang dikembalikan ke master barang (`stok_total`). Sistem akan membentuk `stok_batches` baru (sebagai tumpukan pengembalian) dengan HPP rata-rata dari nota tersebut.
4. **Refund Saldo**: Saldo tabungan santri otomatis dikembalikan utuh (jika metode Tabungan).
5. **Reversal Buku Kas**: Data penerimaan kas di `kas_pesantrens` dihapus secara *real-time* (jika metode Tunai).
6. **Dashboard & Laporan**: Semua transaksi `Batal` diabaikan dalam perhitungan omzet, laba harian, dan barang terlaris.

---

## 4. Modul Pelaporan Kantin (Reporting)
Kantin pesantren harus bisa diaudit oleh Yayasan terkait omzet dan keuntungannya.

### Laporan Wajib
1. **Laporan Penjualan (Shift / Harian / Bulanan)**: Total omzet (uang masuk).
2. **Laporan Laba/Rugi (*Profit & Loss*)**: Menghitung *Total Harga Jual* dikurangi *Total Harga Beli Pokok (HPP dari FIFO)* dari setiap barang yang terjual.
3. **Laporan Stok Menipis (*Low Stock Alert*)**: Fitur peringatan dini yang menampilkan daftar barang di mana total `qty_sisa` gabungannya sudah sama atau di bawah `stok_minimal`.
4. **Laporan Barang Terlaris (*Best Seller*)**: Analisis data bagi pengurus kantin untuk memperbanyak kulakan barang yang paling laku.

*Dokumen ini merupakan standar panduan teknis (Blueprint) untuk fase pengembangan Modul POS & Kantin (Fase 3) pada Sistem Informasi Pesantren (SIPES).*
