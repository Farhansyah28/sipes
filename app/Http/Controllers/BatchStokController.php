<?php

namespace App\Http\Controllers;

use App\Models\BatchStok;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\KasPesantren;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchStokController extends Controller
{
    public function index()
    {
        $batches = BatchStok::with(['barang', 'supplier'])->latest('tanggal_masuk')->paginate(15);
        return view('batch_stok.index', compact('batches'));
    }

    public function create()
    {
        $barangs = Barang::all();
        $suppliers = Supplier::all();
        return view('batch_stok.create', compact('barangs', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'qty_awal' => 'required|integer|min:1',
            'harga_beli_satuan' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated) {
            $validated['qty_sisa'] = $validated['qty_awal'];
            $batch = BatchStok::create($validated);

            $barang = Barang::find($validated['barang_id']);
            $barang->stok_total += $validated['qty_awal'];
            $barang->save();

            // Catat pengeluaran di Kas Pesantren
            $total_belanja = $validated['qty_awal'] * $validated['harga_beli_satuan'];
            if ($total_belanja > 0) {
                KasPesantren::create([
                    'tenant_id' => 1,
                    'tanggal' => $validated['tanggal_masuk'],
                    'tipe' => 'Keluar',
                    'kategori' => 'Restock Kantin',
                    'nominal' => $total_belanja,
                    'keterangan' => "Kulakan/Restock " . $barang->nama . " sejumlah " . $validated['qty_awal'],
                    'referensi_type' => get_class($batch),
                    'referensi_id' => $batch->id,
                ]);
            }
        });

        return redirect()->route('batch_stok.index')->with('success', 'Restock barang berhasil dicatat.');
    }
}
