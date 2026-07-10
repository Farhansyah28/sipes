<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    try {
        $tahun_ajaran_aktif = \App\Models\TahunAjaran::where('is_active', true)->first()?->nama ?? 'Belum Diatur';
        
        $total_santri_aktif = \App\Models\Santri::where('status', 'Aktif')->count();
        $total_psb = \App\Models\Santri::whereNotIn('status', ['Aktif', 'Alumni', 'Keluar'])->count();
        $psb_menunggu = \App\Models\Santri::where('status', 'Verifikasi')->count();
        
        $kapasitas_kamar = \App\Models\Kamar::sum('kapasitas');
        $santri_asrama = \App\Models\Santri::where('status', 'Aktif')->whereNotNull('kamar_id')->count();
        $kamar_terisi = $kapasitas_kamar > 0 ? round(($santri_asrama / $kapasitas_kamar) * 100) : 0;
        
        $today = now()->format('Y-m-d');
        
        $total_absensi_hari_ini = \App\Models\Absensi::where('tanggal', $today)->count();
        $absensi_hadir = $total_absensi_hari_ini > 0 
            ? round((\App\Models\Absensi::where('tanggal', $today)->where('status', 'Hadir')->count() / $total_absensi_hari_ini) * 100) 
            : 0;
        $absensi_alpa = \App\Models\Absensi::where('tanggal', $today)->whereIn('status', ['Alpha', 'Sakit', 'Izin'])->count();
        
        $kas_bulan_ini = \App\Models\Pembayaran::whereMonth('tanggal_bayar', date('m'))->whereYear('tanggal_bayar', date('Y'))->sum('nominal_dibayar');
        $tunggakan_aktif = \App\Models\Tagihan::whereIn('status', ['Belum Bayar', 'Sebagian'])->sum('sisa_tagihan');
        $santri_menunggak = \App\Models\Tagihan::whereIn('status', ['Belum Bayar', 'Sebagian'])->distinct('santri_id')->count('santri_id');
        
        $total_tabungan = \App\Models\Tabungan::sum('saldo');
        
        $omzet_kantin = \App\Models\TransaksiPos::whereDate('tanggal_transaksi', $today)->where('status', '!=', 'Batal')->sum('total_harga');
        $transaksi_kantin = \App\Models\TransaksiPos::whereDate('tanggal_transaksi', $today)->where('status', '!=', 'Batal')->count();
        
        $ortu_login_hari_ini = \App\Models\ActivityLog::whereDate('created_at', $today)->where('log_name', 'portal_login')->count();
        $wa_queue = \Illuminate\Support\Facades\DB::table('jobs')->where('queue', 'whatsapp')->count();
        
        $alert_pembayaran = \App\Models\Pembayaran::where('status', 'Menunggu Verifikasi')->count();
        $alert_stok = \App\Models\Barang::whereRaw('stok_total <= stok_minimal')->count();
        
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Dashboard Error: ' . $e->getMessage());
        $tahun_ajaran_aktif = 'Belum Diatur';
        $total_santri_aktif = 0;
        $total_psb = 0;
        $psb_menunggu = 0;
        $kamar_terisi = 0;
        $absensi_hadir = 0;
        $absensi_alpa = 0;
        $kas_bulan_ini = 0;
        $tunggakan_aktif = 0;
        $santri_menunggak = 0;
        $total_tabungan = 0;
        $omzet_kantin = 0;
        $transaksi_kantin = 0;
        $ortu_login_hari_ini = 0;
        $wa_queue = 0;
        $alert_pembayaran = 0;
        $alert_stok = 0;
    }
    
    return view('dashboard', compact(
        'tahun_ajaran_aktif', 'total_santri_aktif', 'total_psb', 'psb_menunggu',
        'kamar_terisi', 'absensi_hadir', 'absensi_alpa', 'kas_bulan_ini',
        'tunggakan_aktif', 'santri_menunggak', 'total_tabungan', 'omzet_kantin',
        'transaksi_kantin', 'ortu_login_hari_ini', 'wa_queue', 'alert_pembayaran', 'alert_stok'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('psb/pendaftaran', [\App\Http\Controllers\PsbController::class, 'create'])->name('psb.create');
Route::post('psb/pendaftaran', [\App\Http\Controllers\PsbController::class, 'store'])->name('psb.store')->middleware('throttle:5,1');
Route::get('psb/cek-status', [\App\Http\Controllers\PsbController::class, 'cekStatusForm'])->name('psb.cek_status');
Route::post('psb/cek-status', [\App\Http\Controllers\PsbController::class, 'cekStatus'])->name('psb.cek_status.submit')->middleware('throttle:10,1');

Route::prefix('portal')->group(function () {
    Route::get('login', [\App\Http\Controllers\PortalAuthController::class, 'showLoginForm'])->name('portal.login')->middleware('guest');
    Route::post('login', [\App\Http\Controllers\PortalAuthController::class, 'login'])->name('portal.login.post')->middleware('guest');
    
    Route::middleware(['auth', 'role:Orang Tua'])->group(function () {
        Route::post('logout', [\App\Http\Controllers\PortalAuthController::class, 'logout'])->name('portal.logout');
        Route::get('dashboard', [\App\Http\Controllers\PortalController::class, 'dashboard'])->name('portal.dashboard');
        Route::get('akademik/{santri_id}', [\App\Http\Controllers\PortalController::class, 'akademik'])->name('portal.akademik');
        Route::get('keuangan/{santri_id}', [\App\Http\Controllers\PortalController::class, 'keuangan'])->name('portal.keuangan');
        Route::post('tagihan/{tagihan_id}/bayar', [\App\Http\Controllers\PortalController::class, 'uploadBuktiBayar'])->name('portal.tagihan.bayar');
        Route::get('tabungan/{santri_id}', [\App\Http\Controllers\PortalController::class, 'tabungan'])->name('portal.tabungan');
    });
});

Route::middleware('auth')->group(function () {
    // Master Data Group (Super Admin)
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::resource('tahun_ajaran', \App\Http\Controllers\TahunAjaranController::class);
        Route::resource('semester', \App\Http\Controllers\SemesterController::class);
        Route::resource('ustadz', \App\Http\Controllers\UstadzController::class)->except(['show']);
        Route::resource('kelas', \App\Http\Controllers\KelasController::class);
        Route::resource('mata_pelajaran', \App\Http\Controllers\MataPelajaranController::class);
        Route::get('activity_log', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity_log.index');
        Route::resource('users', \App\Http\Controllers\UserController::class);
    });

    // Asrama Group (Super Admin & Musyrif)
    Route::middleware(['role:Super Admin|Musyrif'])->group(function () {
        Route::resource('gedung', \App\Http\Controllers\GedungController::class);
        Route::resource('kamar', \App\Http\Controllers\KamarController::class);
        Route::resource('riwayat_kamar', \App\Http\Controllers\RiwayatKamarController::class);
    });

    // Akademik Group (Super Admin & Ustadz)
    Route::middleware(['role:Super Admin|Ustadz'])->group(function () {
        Route::resource('jadwal', \App\Http\Controllers\JadwalController::class);
        Route::resource('absensi', \App\Http\Controllers\AbsensiController::class)->only(['index', 'store']);
        Route::get('nilai/raport', [\App\Http\Controllers\NilaiController::class, 'raport'])->name('nilai.raport');
        Route::resource('nilai', \App\Http\Controllers\NilaiController::class)->only(['index', 'store']);
        
        // Kenaikan Kelas
        Route::get('kenaikan_kelas', [\App\Http\Controllers\KenaikanKelasController::class, 'index'])->name('kenaikan_kelas.index');
        Route::post('kenaikan_kelas', [\App\Http\Controllers\KenaikanKelasController::class, 'store'])->name('kenaikan_kelas.store');

        // Auto Scheduler
        Route::get('auto_schedule', [\App\Http\Controllers\AutoScheduleController::class, 'index'])->name('auto_schedule.index');
        Route::post('auto_schedule/pengampu', [\App\Http\Controllers\AutoScheduleController::class, 'storePengampu'])->name('auto_schedule.store_pengampu');
        Route::delete('auto_schedule/pengampu/{id}', [\App\Http\Controllers\AutoScheduleController::class, 'destroyPengampu'])->name('auto_schedule.destroy_pengampu');
        Route::post('auto_schedule/ketersediaan', [\App\Http\Controllers\AutoScheduleController::class, 'storeKetersediaan'])->name('auto_schedule.store_ketersediaan');
        Route::delete('auto_schedule/ketersediaan/{id}', [\App\Http\Controllers\AutoScheduleController::class, 'destroyKetersediaan'])->name('auto_schedule.destroy_ketersediaan');
        Route::post('auto_schedule/process', [\App\Http\Controllers\AutoScheduleController::class, 'process'])->name('auto_schedule.process');
    });

    // PSB Group (Super Admin & Admin PSB)
    Route::middleware(['role:Super Admin|Admin PSB'])->group(function () {
        Route::resource('orang_tua', \App\Http\Controllers\OrangTuaController::class);
        // Import Data Santri
        Route::get('import/santri', [\App\Http\Controllers\ImportSantriController::class, 'index'])->name('import.santri.index');
        Route::get('import/santri/template', [\App\Http\Controllers\ImportSantriController::class, 'downloadTemplate'])->name('import.santri.template');
        Route::post('import/santri', [\App\Http\Controllers\ImportSantriController::class, 'upload'])->name('import.santri.upload');
        Route::get('import/santri/preview/{session_id}', [\App\Http\Controllers\ImportSantriController::class, 'preview'])->name('import.santri.preview');
        Route::post('import/santri/commit/{session_id}', [\App\Http\Controllers\ImportSantriController::class, 'commit'])->name('import.santri.commit');

        // Import Data Ustadz
        Route::get('import/ustadz', [\App\Http\Controllers\ImportUstadzController::class, 'index'])->name('import.ustadz.index');
        Route::get('import/ustadz/template', [\App\Http\Controllers\ImportUstadzController::class, 'downloadTemplate'])->name('import.ustadz.template');
        Route::post('import/ustadz', [\App\Http\Controllers\ImportUstadzController::class, 'upload'])->name('import.ustadz.upload');
        Route::get('import/ustadz/preview/{session_id}', [\App\Http\Controllers\ImportUstadzController::class, 'preview'])->name('import.ustadz.preview');
        Route::post('import/ustadz/commit/{session_id}', [\App\Http\Controllers\ImportUstadzController::class, 'commit'])->name('import.ustadz.commit');

        // Penugasan Akademik Ustadz
        Route::get('ustadz/penugasan', [\App\Http\Controllers\PenugasanUstadzController::class, 'index'])->name('penugasan.ustadz.index');
        Route::post('ustadz/penugasan/{ustadz_id}/pengampu', [\App\Http\Controllers\PenugasanUstadzController::class, 'addPengampu'])->name('penugasan.ustadz.add_pengampu');
        Route::delete('ustadz/penugasan/pengampu/{id}', [\App\Http\Controllers\PenugasanUstadzController::class, 'deletePengampu'])->name('penugasan.ustadz.delete_pengampu');
        Route::post('penugasan-ustadz/{ustadz_id}/ketersediaan', [\App\Http\Controllers\PenugasanUstadzController::class, 'saveKetersediaan'])->name('penugasan.ustadz.ketersediaan');

        Route::resource('santri', \App\Http\Controllers\SantriController::class);
        
        Route::get('psb', [\App\Http\Controllers\PsbController::class, 'index'])->name('psb.index');
        // Admin actions for PSB
        Route::get('psb/{id}', [\App\Http\Controllers\PsbController::class, 'show'])->name('psb.show');
        Route::put('psb/{id}/status', [\App\Http\Controllers\PsbController::class, 'updateStatus'])->name('psb.update_status');
        Route::put('psb/dokumen/{dokumen}/verify', [\App\Http\Controllers\PsbController::class, 'verifyDokumen'])->name('psb.verify_dokumen');
        Route::post('psb/{id}/penilaian', [\App\Http\Controllers\PsbController::class, 'storePenilaian'])->name('psb.store_penilaian');
    });

    // Keuangan Group (Super Admin & Bagian Keuangan)
    Route::middleware(['role:Super Admin|Bagian Keuangan'])->group(function () {
        Route::get('ledger', [\App\Http\Controllers\LedgerController::class, 'index'])->name('ledger.index');
        Route::post('ledger', [\App\Http\Controllers\LedgerController::class, 'store'])->name('ledger.store');
        Route::get('tabungan/mass-topup', [\App\Http\Controllers\TabunganController::class, 'massTopup'])->name('tabungan.mass_topup');
        Route::post('tabungan/mass-topup', [\App\Http\Controllers\TabunganController::class, 'storeMassTopup'])->name('tabungan.store_mass_topup');
        Route::get('tabungan/mass-withdrawal', [\App\Http\Controllers\TabunganController::class, 'massWithdrawal'])->name('tabungan.mass_withdrawal');
        Route::post('tabungan/mass-withdrawal', [\App\Http\Controllers\TabunganController::class, 'storeMassWithdrawal'])->name('tabungan.store_mass_withdrawal');
        Route::resource('kategori_tagihan', \App\Http\Controllers\KategoriTagihanController::class);
        Route::get('tagihan/bulk', [\App\Http\Controllers\TagihanController::class, 'bulkCreate'])->name('tagihan.bulk_create');
        Route::post('tagihan/bulk', [\App\Http\Controllers\TagihanController::class, 'bulkStore'])->name('tagihan.bulk_store');
        Route::get('tagihan/{tagihan}/print', [\App\Http\Controllers\TagihanController::class, 'print'])->name('tagihan.print');
        Route::resource('tagihan', \App\Http\Controllers\TagihanController::class);
        Route::post('pembayaran/{pembayaran}/verify', [\App\Http\Controllers\PembayaranController::class, 'verify'])->name('pembayaran.verify');
        Route::resource('pembayaran', \App\Http\Controllers\PembayaranController::class);
        Route::resource('tabungan', \App\Http\Controllers\TabunganController::class);
    });

    // Kantin POS Group (Super Admin & Kasir Kantin)
    Route::middleware(['role:Super Admin|Kasir Kantin'])->group(function () {
        Route::resource('kategori_barang', \App\Http\Controllers\KategoriBarangController::class);
        Route::resource('supplier', \App\Http\Controllers\SupplierController::class);
        Route::resource('barang', \App\Http\Controllers\BarangController::class);
        Route::resource('batch_stok', \App\Http\Controllers\BatchStokController::class);
        Route::resource('transaksi_pos', \App\Http\Controllers\TransaksiPosController::class);
        Route::get('transaksi_pos/{id}/print', [\App\Http\Controllers\TransaksiPosController::class, 'print'])->name('transaksi_pos.print');
        Route::post('transaksi_pos/{id}/void', [\App\Http\Controllers\TransaksiPosController::class, 'voidTransaksi'])->name('transaksi_pos.void');
        Route::get('laporan_kantin', [\App\Http\Controllers\LaporanKantinController::class, 'index'])->name('laporan_kantin.index');
        Route::get('laporan_kantin/print_shift', [\App\Http\Controllers\LaporanKantinController::class, 'printShift'])->name('laporan_kantin.print_shift');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
