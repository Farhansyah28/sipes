<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPos;
use App\Models\DetailTransaksiPos;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class LaporanKantinController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('m');
        $thisYear = now()->format('Y');

        // Omzet (Penjualan)
        $penjualan_hari_ini = TransaksiPos::whereDate('tanggal_transaksi', $today)->where('status', '!=', 'Batal')->sum('total_harga');
        $penjualan_bulan_ini = TransaksiPos::whereMonth('tanggal_transaksi', $thisMonth)->whereYear('tanggal_transaksi', $thisYear)->where('status', '!=', 'Batal')->sum('total_harga');

        // Laba/Rugi (Total Penjualan - Total HPP)
        $detail_hari_ini = DetailTransaksiPos::whereHas('transaksiPos', function ($q) use ($today) {
            $q->whereDate('tanggal_transaksi', $today)->where('status', '!=', 'Batal');
        })->get();
        $laba_hari_ini = $detail_hari_ini->sum('subtotal') - $detail_hari_ini->sum('harga_beli_pokok');

        $detail_bulan_ini = DetailTransaksiPos::whereHas('transaksiPos', function ($q) use ($thisMonth, $thisYear) {
            $q->whereMonth('tanggal_transaksi', $thisMonth)->whereYear('tanggal_transaksi', $thisYear)->where('status', '!=', 'Batal');
        })->get();
        $laba_bulan_ini = $detail_bulan_ini->sum('subtotal') - $detail_bulan_ini->sum('harga_beli_pokok');

        // Stok Menipis
        $stok_menipis = Barang::whereColumn('stok_total', '<=', 'stok_minimal')->with('kategoriBarang')->get();

        // Barang Terlaris (Top 5)
        $barang_terlaris = DetailTransaksiPos::select('barang_id', DB::raw('SUM(qty) as total_qty'))
                            ->whereHas('transaksiPos', function ($q) {
                                $q->where('status', '!=', 'Batal');
                            })
                            ->groupBy('barang_id')
                            ->orderBy('total_qty', 'desc')
                            ->take(5)
                            ->with('barang')
                            ->get();

        return view('laporan_kantin.index', compact(
            'penjualan_hari_ini', 'penjualan_bulan_ini',
            'laba_hari_ini', 'laba_bulan_ini',
            'stok_menipis', 'barang_terlaris'
        ));
    }

    public function printShift()
    {
        $today = now()->format('Y-m-d');
        $kasir_id = \Illuminate\Support\Facades\Auth::id() ?? 1; // Fallback jika tidak ada auth
        $kasir = \App\Models\User::find($kasir_id);

        $transaksis = TransaksiPos::whereDate('tanggal_transaksi', $today)
                        ->where('kasir_id', $kasir_id)
                        ->where('status', '!=', 'Batal')
                        ->get();

        $tunai = $transaksis->where('metode_pembayaran', 'Tunai')->sum('total_harga');
        $tabungan = $transaksis->where('metode_pembayaran', 'Tabungan')->sum('total_harga');
        $total_omzet = $tunai + $tabungan;
        
        $jumlah_transaksi = $transaksis->count();

        return view('laporan_kantin.print_shift', compact(
            'today', 'kasir', 'tunai', 'tabungan', 'total_omzet', 'jumlah_transaksi'
        ));
    }
}
