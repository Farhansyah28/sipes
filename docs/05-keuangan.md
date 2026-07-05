# Dokumentasi Modul: Keuangan (Billing & Ledger)

## Deskripsi Singkat
Modul Keuangan merupakan pusat sentral pembukuan kas dan tagihan (billing) pesantren. Modul ini mengotomatiskan proses penagihan bulanan/tahunan (SPP, Uang Makan, Uang Pangkal), melacak tunggakan, serta pembukuan jurnal (Ledger) untuk memonitor arus kas masuk (pemasukan) dan keluar (pengeluaran).

## Fitur & Menu
1. **Kategori Tagihan (Billing Templates)**
   - Mendefinisikan jenis tagihan beserta nominal defaultnya (Misal: SPP Rp500.000, Uang Pangkal Rp2.000.000).
   - Pengaturan Tipe Siklus penagihan (Bulanan, Semesteran, Sekali Bayar).

2. **Manajemen Tagihan (Invoicing)**
   - Generate tagihan secara individu atau *Bulk Create* (massal ke banyak santri sekaligus).
   - Melacak status tagihan secara akurat: `Lunas`, `Belum Bayar`, atau `Sebagian` (Cicilan).
   - Fitur cetak invoice (Print Invoice).

3. **Verifikasi Pembayaran**
   - Mengelola setoran dari santri/wali santri (via Kasir atau Portal Wali Santri).
   - Mendukung pembayaran fleksibel (Bisa bayar dari Cash atau memotong saldo Tabungan santri).
   - Verifikasi bukti transfer. Setelah diverifikasi, saldo `sisa_tagihan` otomatis berkurang.

4. **Buku Besar (Ledger / Kas Pesantren)**
   - Pembukuan *double-entry* sederhana untuk memisahkan Kas Masuk dan Kas Keluar.
   - Pemasukan dari pembayaran SPP otomatis tercatat di Ledger.
   - Admin dapat mencatat pengeluaran operasional (seperti bayar listrik, gaji ustadz, dll).
   - Laporan laba/rugi (Kas Masuk vs Kas Keluar).

## Tabel Database Terkait
- `kategori_tagihans`: Referensi jenis dan nominal kewajiban.
- `tagihans`: Invoice spesifik milik santri.
- `pembayarans`: Transaksi pembayaran (cash, transfer, potong tabungan).
- `kas_pesantrens`: Jurnal rekonsiliasi arus kas.

## Alur Kerja (Workflow)
1. **Setup Awal**: Bendahara membuat master "Kategori Tagihan".
2. **Billing Generation**: Pada tanggal 1 setiap bulan, bendahara menggunakan fitur "Tagihan Bulk" untuk membuat invoice SPP ke ratusan santri aktif dengan sekali klik.
3. **Pembayaran**: Orang tua membayar melalui portal (upload bukti) atau bayar tunai di tata usaha. 
4. **Verifikasi & Rekonsiliasi**: Admin/Kasir memverifikasi pembayaran. Sistem secara instan mencetak status Lunas dan melempar pencatatan tersebut ke buku besar (`kas_pesantrens`) sebagai pendapatan.
