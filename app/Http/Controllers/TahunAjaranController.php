<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahun_ajarans = TahunAjaran::paginate(10);
        return view('tahun_ajaran.index', compact('tahun_ajarans'));
    }

    public function create()
    {
        return view('tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        if ($validated['is_active']) {
            TahunAjaran::query()->update(['is_active' => false]);
        }

        TahunAjaran::create($validated);
        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahun_ajaran)
    {
        return view('tahun_ajaran.edit', compact('tahun_ajaran'));
    }

    public function update(Request $request, TahunAjaran $tahun_ajaran)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        if ($validated['is_active']) {
            TahunAjaran::query()->where('id', '!=', $tahun_ajaran->id)->update(['is_active' => false]);
        }

        $tahun_ajaran->update($validated);
        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahun_ajaran)
    {
        $hasSemester = \App\Models\Semester::where('tahun_ajaran_id', $tahun_ajaran->id)->exists();
        if ($hasSemester) {
            return back()->withErrors(['error' => 'Gagal: Tahun Ajaran tidak bisa dihapus karena sudah memiliki data Semester di dalamnya. Hapus data Semester terlebih dahulu (jika diizinkan).']);
        }
        
        $tahun_ajaran->delete();
        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
