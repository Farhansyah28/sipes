# Dokumentasi Modul: Tabungan Santri

## Deskripsi Singkat
Modul Tabungan berfungsi seperti "Mini-Banking" internal pesantren. Modul ini mendigitalisasi uang jajan atau simpanan santri, sehingga orang tua bisa menyetorkan uang jajan tanpa harus menitipkan uang tunai dalam jumlah besar kepada santri, meminimalisir risiko kehilangan (pencurian) di asrama.

## Fitur & Menu
1. **Rekening Tabungan Santri**
   - Setiap santri aktif otomatis memiliki profil buku tabungan.
   - Pemantauan real-time sisa saldo santri yang bisa digunakan untuk bertransaksi di Kantin POS atau membayar Tagihan SPP.

2. **Setoran (Deposit) & Penarikan (Withdrawal)**
   - Mencatat aktivitas `Setor` tunai/transfer uang jajan dari orang tua.
   - Mencatat aktivitas `Tarik` tunai saat santri butuh uang cash.

3. **Mass Topup & Mass Withdrawal**
   - Fitur unggulan untuk mempercepat input uang masuk dan keluar secara kolektif (massal).
   - Digunakan biasanya saat jadwal pembagian uang jajan pekanan asrama (Uang jajan dibagikan ke 50 anak sekaligus dalam satu form).

4. **Mutasi Tabungan (Ledger Tabungan)**
   - Menampilkan histori (buku tabungan digital) setiap transaksi masuk, keluar, pembayaran kantin, dan pembayaran tagihan dari satu akun santri.
   - Transparansi 100% untuk wali santri yang memantau via portal.

## Tabel Database Terkait
- `tabungans`: Berisi sisa `saldo` agregat (current balance) dari santri_id.
- `mutasi_tabungans`: Historis rinci tiap perubahan saldo (tipe: 'Setor', 'Tarik', 'Kantin', dll), tanggal, dan nominal.
- Terhubung secara tidak langsung (relasi transaksi) dengan `pembayarans` (jika bayar SPP potong tabungan) dan `transaksi_pos` (jajan di kantin).

## Alur Kerja (Workflow)
1. **Penerimaan Uang Jajan**: Orang tua transfer bulanan Rp500.000. Admin/Kasir melakukan `Setor` di profil tabungan anak. Saldo anak bertambah.
2. **Cashless Jajan**: Saat anak jajan di Kantin Pesantren, kasir kantin memotong pembayaran dari `Tabungan`. Sistem otomatis menambahkan record `Tarik` pada mutasi tabungan dan saldo anak berkurang Rp10.000.
3. **Pencairan (Withdrawal)**: Jika anak ingin memegang uang tunai untuk ongkos pulang, anak datang ke admin keuangan dan meminta tarik tunai. Admin melakukan `Tarik` tabungan, memberikan cash fisik kepada anak.
