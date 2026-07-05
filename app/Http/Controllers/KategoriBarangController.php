<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBarang::latest()->paginate(15);
        return view('kategori_barang.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['nama' => 'required|string|max:255']);
        KategoriBarang::create($validated);
        return back()->with('success', 'Kategori Barang berhasil ditambahkan.');
    }

    public function destroy(KategoriBarang $kategori_barang)
    {
        $kategori_barang->delete();
        return back()->with('success', 'Kategori Barang berhasil dihapus.');
    }
}
