# Dokumentasi Modul: Kantin (Point of Sales)

## Deskripsi Singkat
Modul Kantin adalah sistem *Point of Sales* (Kasir) sederhana dan terintegrasi untuk mengelola penjualan harian, perputaran inventaris barang, serta pencatatan stok di minimarket/kantin pesantren. Keunggulan utamanya adalah kemampuan mendukung transaksi *Cashless* (memotong saldo tabungan santri).

## Fitur & Menu
1. **Manajemen Master Barang & Supplier**
   - Katalog produk jualan (Nama Barang, Kategori, Harga Jual).
   - Pengaturan parameter `Stok Minimal`. Jika stok barang mendekati atau di bawah batas ini, sistem akan memberikan *Alert Stok Menipis* di Dashboard.
   - Database Supplier (Pemasok grosir).

2. **Pembelian & Batch Stok (Inventory)**
   - Karena harga grosir bisa berubah-ubah, sistem menggunakan konsep `Batch Stok`.
   - Admin memasukkan barang masuk (Kulakan) berdasarkan Supplier, Kuantitas, Tanggal Kadaluarsa, dan Harga Beli Satuan (Harga Pokok Penjualan/HPP).
   - Lacak mutasi barang dari hulu ke hilir dengan sistem FIFO (First-In First-Out) tersirat.

3. **Kasir POS (Point of Sales)**
   - Antarmuka khusus untuk Kasir Kantin melayani pembelian.
   - Multi-Metode Pembayaran: **Tunai** atau **Tabungan** (Cashless).
   - Fitur Cetak Struk (Receipt) printer thermal.
   - Fitur `Void` untuk membatalkan transaksi yang salah input (stok barang kembali, saldo uang dikembalikan).

4. **Laporan Kantin**
   - Rekap omzet harian/bulanan.
   - Menghitung Gross Margin (Laba Kotor = Total Harga Jual dikurangi HPP *Harga Beli Pokok* dari batch stok).
   - Laporan Shift (Print Shift) sebagai bentuk pertanggungjawaban kasir di penghujung hari atau pergantian shift.

## Tabel Database Terkait
- `barangs`: Master katalog produk (agregasi `stok_total` dari batch).
- `kategori_barangs`, `suppliers`: Pelengkap master data.
- `batch_stoks`: Detail barang masuk (Harga Beli Pokok, Kuantitas, Expired Date).
- `transaksi_pos`: Transaksi penjualan kasir (nota header).
- `detail_transaksi_pos`: Rincian item barang yang dibeli per transaksi (menghubungkan ke `batch_stoks` atau `barangs` untuk kalkulasi HPP).

## Alur Kerja (Workflow)
1. **Kulakan (Stok Masuk)**: Barang dikirim supplier -> Admin input di `Batch Stok` -> `stok_total` Barang bertambah.
2. **Transaksi Kasir**:
   - Santri belanja sabun dan snack.
   - Kasir scan barcode / cari barang di antarmuka POS.
   - Pilih pembayaran "Tabungan". Sistem mengecek saldo tabungan santri. Jika cukup, proses `Checkout`.
3. **Efek Berantai**:
   - Stok barang berkurang.
   - `transaksi_pos` mencatat pendapatan.
   - Saldo tabungan santri berkurang (mutasi tercatat).
4. **Tutup Shift**: Di akhir jam kerja, kasir mencetak Laporan Shift Kantin, memverifikasi uang di laci kasir cocok dengan transaksi tunai.
