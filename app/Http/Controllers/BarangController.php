<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategoriBarang')->latest()->paginate(15);
        $kategoris = KategoriBarang::all();
        return view('barang.index', compact('barangs', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_barang_id' => 'required|exists:kategori_barangs,id',
            'kode_barang' => 'required|string|unique:barangs,kode_barang',
            'nama' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0'
        ]);
        Barang::create($validated);
        return back()->with('success', 'Master Barang berhasil ditambahkan.');
    }

    public function destroy(Barang $barang)
    {
        $hasTransaksi = \App\Models\DetailTransaksiPos::where('barang_id', $barang->id)->exists();
        $hasBatch = \App\Models\BatchStok::where('barang_id', $barang->id)->exists();

        if ($hasTransaksi || $hasBatch) {
            return back()->withErrors(['error' => 'Gagal: Barang tidak bisa dihapus karena memiliki riwayat stok (Batch) atau Transaksi Penjualan. Penghapusan ini akan merusak integritas data laporan keuangan POS.']);
        }

        $barang->delete();
        return back()->with('success', 'Master Barang berhasil dihapus.');
    }
}
