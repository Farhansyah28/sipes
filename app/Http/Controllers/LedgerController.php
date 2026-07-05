<?php

namespace App\Http\Controllers;

use App\Models\KasPesantren;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');
        
        $kas_list = KasPesantren::with('referensi')
            ->where('tanggal', 'like', $bulan . '%')
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();
            
        $totalMasuk = $kas_list->where('tipe', 'Masuk')->sum('nominal');
        $totalKeluar = $kas_list->where('tipe', 'Keluar')->sum('nominal');
        $saldoAkhir = $totalMasuk - $totalKeluar;

        return view('ledger.index', compact('kas_list', 'bulan', 'totalMasuk', 'totalKeluar', 'saldoAkhir'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:Masuk,Keluar',
            'kategori' => 'required|string',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $validated['tenant_id'] = 1;
        KasPesantren::create($validated);

        return redirect()->route('ledger.index', ['bulan' => substr($validated['tanggal'], 0, 7)])
                         ->with('success', 'Transaksi Kas manual berhasil dicatat.');
    }
}
