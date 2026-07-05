<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $tahun_ajaran_aktif = \App\Models\TahunAjaran::where('is_active', true)->first()?->nama ?? 'Belum Diatur';
    echo "TA: $tahun_ajaran_aktif\n";
    
    $total_santri_aktif = \App\Models\Santri::where('status', 'Aktif')->count();
    $total_psb = \App\Models\Santri::whereNotIn('status', ['Aktif', 'Alumni', 'Keluar'])->count();
    $psb_menunggu = \App\Models\Santri::where('status', 'Verifikasi')->count();
    echo "Santri: $total_santri_aktif, PSB: $total_psb, Tunggu: $psb_menunggu\n";
    
    $kapasitas_kamar = \App\Models\Kamar::sum('kapasitas');
    $santri_asrama = \App\Models\Santri::where('status', 'Aktif')->whereNotNull('kamar_id')->count();
    $kamar_terisi = $kapasitas_kamar > 0 ? round(($santri_asrama / $kapasitas_kamar) * 100) : 0;
    echo "Kamar terisi: $kamar_terisi%\n";
    
    $today = now()->format('Y-m-d');
    
    $total_absensi_hari_ini = \App\Models\Absensi::where('tanggal', $today)->count();
    $absensi_hadir = $total_absensi_hari_ini > 0 
        ? round((\App\Models\Absensi::where('tanggal', $today)->where('status', 'Hadir')->count() / $total_absensi_hari_ini) * 100) 
        : 0;
    $absensi_alpa = \App\Models\Absensi::where('tanggal', $today)->whereIn('status', ['Alpha', 'Sakit', 'Izin'])->count();
    echo "Absensi hadir: $absensi_hadir%, Alpa: $absensi_alpa\n";
    
    $kas_bulan_ini = \App\Models\Pembayaran::whereMonth('tanggal_bayar', date('m'))->whereYear('tanggal_bayar', date('Y'))->sum('nominal_dibayar');
    $tunggakan_aktif = \App\Models\Tagihan::whereIn('status', ['Belum Bayar', 'Sebagian'])->sum('sisa_tagihan');
    $santri_menunggak = \App\Models\Tagihan::whereIn('status', ['Belum Bayar', 'Sebagian'])->distinct('santri_id')->count('santri_id');
    echo "Kas: $kas_bulan_ini, Tunggakan: $tunggakan_aktif dari $santri_menunggak santri\n";
    
    $total_tabungan = \App\Models\Tabungan::sum('saldo');
    echo "Tabungan: $total_tabungan\n";
    
    $omzet_kantin = \App\Models\TransaksiPos::whereDate('tanggal_transaksi', $today)->sum('total_harga');
    $transaksi_kantin = \App\Models\TransaksiPos::whereDate('tanggal_transaksi', $today)->count();
    echo "Omzet: $omzet_kantin, Transaksi Kantin: $transaksi_kantin\n";
    
    $ortu_login_hari_ini = \App\Models\ActivityLog::whereDate('created_at', $today)->where('log_name', 'portal_login')->count();
    $wa_queue = \Illuminate\Support\Facades\DB::table('jobs')->where('queue', 'whatsapp')->count();
    echo "Ortu login: $ortu_login_hari_ini, WA queue: $wa_queue\n";
    
    $alert_pembayaran = \App\Models\Pembayaran::where('status', 'Menunggu Verifikasi')->count();
    $alert_stok = \App\Models\Barang::whereRaw('stok_total <= stok_minimal')->count();
    echo "Alert Pembayaran: $alert_pembayaran, Alert Stok: $alert_stok\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine();
}
