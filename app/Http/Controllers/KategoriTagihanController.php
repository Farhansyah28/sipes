<?php

namespace App\Http\Controllers;

use App\Models\KategoriTagihan;
use Illuminate\Http\Request;

class KategoriTagihanController extends Controller
{
    public function index()
    {
        $kategori_tagihans = KategoriTagihan::paginate(10);
        return view('kategori_tagihan.index', compact('kategori_tagihans'));
    }

    public function create()
    {
        return view('kategori_tagihan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe_siklus' => 'required|string',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        KategoriTagihan::create($validated);
        return redirect()->route('kategori_tagihan.index')->with('success', 'Kategori Tagihan berhasil ditambahkan.');
    }

    public function edit(KategoriTagihan $kategori_tagihan)
    {
        return view('kategori_tagihan.edit', compact('kategori_tagihan'));
    }

    public function update(Request $request, KategoriTagihan $kategori_tagihan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe_siklus' => 'required|string',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        $kategori_tagihan->update($validated);
        return redirect()->route('kategori_tagihan.index')->with('success', 'Kategori Tagihan berhasil diperbarui.');
    }

    public function destroy(KategoriTagihan $kategori_tagihan)
    {
        $hasTagihan = \App\Models\Tagihan::where('kategori_tagihan_id', $kategori_tagihan->id)->exists();
        if ($hasTagihan) {
            return back()->withErrors(['error' => 'Gagal: Kategori Tagihan tidak bisa dihapus karena sudah dipakai pada data Tagihan santri. Penghapusan ini akan merusak laporan keuangan.']);
        }

        $kategori_tagihan->delete();
        return redirect()->route('kategori_tagihan.index')->with('success', 'Kategori Tagihan berhasil dihapus.');
    }
}
