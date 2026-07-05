<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KasPesantren;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\TransaksiPos;
use App\Models\DetailTransaksiPos;
use App\Models\Barang;
use App\Models\BatchStok;

echo "--- MENGHAPUS DATA KAS PESANTREN SEBELUMNYA ---\n";
KasPesantren::truncate();

try {
    // 1. Uji Coba Manual Kas
    echo "\n[1] Mencatat Kas Manual (Pengeluaran)...\n";
    KasPesantren::create([
        'tenant_id' => 1,
        'tanggal' => date('Y-m-d'),
        'tipe' => 'Keluar',
        'kategori' => 'Operasional',
        'nominal' => 150000,
        'keterangan' => 'Beli Sapu dan Alat Pel',
    ]);
    
    // 2. Uji Coba Restock Kantin
    echo "[2] Mencatat Restock Kantin (Kas Keluar)...\n";
    $barang = Barang::first();
    if($barang) {
        $batch = BatchStok::create([
            'tenant_id' => 1,
            'barang_id' => $barang->id,
            'qty_awal' => 10,
            'qty_sisa' => 10,
            'harga_beli_satuan' => 2000,
            'tanggal_masuk' => date('Y-m-d')
        ]);
        
        $barang->stok_total += 10;
        $barang->save();
        
        // Simulasikan trigger Controller
        $total_belanja = 10 * 2000;
        KasPesantren::create([
            'tenant_id' => 1,
            'tanggal' => date('Y-m-d'),
            'tipe' => 'Keluar',
            'kategori' => 'Restock Kantin',
            'nominal' => $total_belanja,
            'keterangan' => "Kulakan/Restock " . $barang->nama . " sejumlah 10",
            'referensi_type' => get_class($batch),
            'referensi_id' => $batch->id,
        ]);
    }
    
    // 3. Uji Coba Pembayaran SPP (Kas Masuk)
    echo "[3] Mencatat Pembayaran SPP (Kas Masuk)...\n";
    $tagihan = Tagihan::where('status', '!=', 'Lunas')->first();
    if($tagihan) {
        $pembayaran = App\Models\Pembayaran::create([
            'tenant_id' => 1,
            'tagihan_id' => $tagihan->id,
            'nominal_dibayar' => 50000,
            'tanggal_bayar' => date('Y-m-d'),
            'metode_pembayaran' => 'Potong Tabungan',
            'status' => 'Valid'
        ]);
        
        $santri = $tagihan->santri;
        if ($santri && $santri->tabungan) {
            $santri->tabungan->saldo -= 50000;
            $santri->tabungan->save();
            App\Models\MutasiTabungan::create([
                'tenant_id' => 1,
                'tabungan_id' => $santri->tabungan->id,
                'tipe' => 'Tarik',
                'nominal' => 50000,
                'tanggal' => date('Y-m-d'),
                'keterangan' => 'Bayar SPP Pakai Tabungan'
            ]);
        }
        
        // Tidak ada record ke KasPesantren karena potong tabungan
    }
    
    // 4. Uji Coba Transaksi Kantin (Kas Masuk)
    echo "[4] Mencatat Penjualan Kantin (Kas Masuk)...\n";
    $transaksi = TransaksiPos::create([
        'tenant_id' => 1,
        'santri_id' => null,
        'kasir_id' => 1,
        'tanggal_transaksi' => date('Y-m-d'),
        'total_harga' => 15000,
        'metode_pembayaran' => 'Tunai',
        'status' => 'Sukses'
    ]);
    
    KasPesantren::create([
        'tenant_id' => 1,
        'tanggal' => date('Y-m-d'),
        'tipe' => 'Masuk',
        'kategori' => 'Kantin POS',
        'nominal' => 15000,
        'keterangan' => "Penjualan Kantin #" . $transaksi->id,
        'referensi_type' => get_class($transaksi),
        'referensi_id' => $transaksi->id,
    ]);
    
    // 5. Uji Coba Setor Tabungan (Top-Up)
    echo "[5] Mencatat Top-Up Tabungan Santri (Kas Masuk)...\n";
    $tabungan = App\Models\Tabungan::first();
    if($tabungan) {
        $mutasi = App\Models\MutasiTabungan::create([
            'tenant_id' => 1,
            'tabungan_id' => $tabungan->id,
            'tipe' => 'Setor',
            'nominal' => 100000,
            'tanggal' => date('Y-m-d'),
            'keterangan' => 'Top Up Uji Coba'
        ]);
        KasPesantren::create([
            'tenant_id' => 1,
            'tanggal' => date('Y-m-d'),
            'tipe' => 'Masuk',
            'kategori' => 'Setoran Tabungan',
            'nominal' => 100000,
            'keterangan' => 'Top Up Uji Coba (Santri ID: '.$tabungan->santri_id.')',
            'referensi_type' => get_class($mutasi),
            'referensi_id' => $mutasi->id,
        ]);
    }
    
    // 6. Uji Coba Jajan Pakai Tabungan (Seharusnya Kas Tidak Bertambah)
    echo "[6] Jajan Kantin Pakai Tabungan (TIDAK ADA KAS MASUK BARU)...\n";
    $transaksiTabungan = TransaksiPos::create([
        'tenant_id' => 1,
        'santri_id' => $tabungan ? $tabungan->santri_id : 1,
        'kasir_id' => 1,
        'tanggal_transaksi' => date('Y-m-d'),
        'total_harga' => 20000,
        'metode_pembayaran' => 'Tabungan',
        'status' => 'Sukses'
    ]);
    // Sesuai sistem yang kita bangun, ini TIDAK memanggil KasPesantren::create()

    // 7. Uji Coba Reversal (Void Transaksi Kantin Tunai)
    echo "[7] Mencatat Pembatalan Kantin Tunai (Reversal Kas)...\n";
    // Void the transaction
    $transaksi->status = 'Batal';
    $transaksi->save();
    
    KasPesantren::where('referensi_type', get_class($transaksi))->where('referensi_id', $transaksi->id)->delete();
    echo "    - Transaksi Kantin dibatalkan, Kas dihapus.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n--- HASIL BUKU KAS PESANTREN ---\n";
$kasList = KasPesantren::all();
$masuk = 0;
$keluar = 0;

foreach($kasList as $k) {
    echo sprintf("[%s] %s | %s | Rp %s | %s\n", 
        $k->tanggal, 
        str_pad($k->tipe, 6), 
        str_pad($k->kategori, 15), 
        number_format($k->nominal, 0, ',', '.'),
        $k->keterangan
    );
    if($k->tipe == 'Masuk') $masuk += $k->nominal;
    else $keluar += $k->nominal;
}

echo "---------------------------------\n";
echo "Total Kas Masuk : Rp " . number_format($masuk, 0, ',', '.') . "\n";
echo "Total Kas Keluar: Rp " . number_format($keluar, 0, ',', '.') . "\n";
echo "SALDO AKHIR     : Rp " . number_format($masuk - $keluar, 0, ',', '.') . "\n";
