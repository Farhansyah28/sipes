<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mata_pelajarans = MataPelajaran::paginate(10);
        return view('mata_pelajaran.index', compact('mata_pelajarans'));
    }

    public function create()
    {
        return view('mata_pelajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:mata_pelajarans,kode',
            'nama' => 'required|string|max:255',
        ]);

        MataPelajaran::create($validated);
        return redirect()->route('mata_pelajaran.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mata_pelajaran)
    {
        return view('mata_pelajaran.edit', compact('mata_pelajaran'));
    }

    public function update(Request $request, MataPelajaran $mata_pelajaran)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:mata_pelajarans,kode,' . $mata_pelajaran->id,
            'nama' => 'required|string|max:255',
        ]);

        $mata_pelajaran->update($validated);
        return redirect()->route('mata_pelajaran.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mata_pelajaran)
    {
        $hasJadwal = \App\Models\Jadwal::where('mata_pelajaran_id', $mata_pelajaran->id)->exists();
        if ($hasJadwal) {
            return back()->withErrors(['error' => 'Gagal: Mata Pelajaran tidak bisa dihapus karena sedang dipakai di Jadwal Pelajaran. Hapus jadwal terlebih dahulu.']);
        }
        
        $mata_pelajaran->delete();
        return redirect()->route('mata_pelajaran.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
