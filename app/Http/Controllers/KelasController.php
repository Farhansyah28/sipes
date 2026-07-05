<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Ustadz;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $ustadzs = Ustadz::all();
        return view('kelas.create', compact('ustadzs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama',
            'tingkat' => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:ustadzs,id|unique:kelas,wali_kelas_id',
        ], [
            'wali_kelas_id.unique' => 'Ustadz tersebut sudah menjadi Wali Kelas di kelas lain.'
        ]);

        Kelas::create($validated);
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $kela = Kelas::findOrFail($id);
        $ustadzs = Ustadz::all();
        return view('kelas.edit', compact('kela', 'ustadzs'));
    }

    public function update(Request $request, string $id)
    {
        $kela = Kelas::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama,' . $kela->id,
            'tingkat' => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:ustadzs,id|unique:kelas,wali_kelas_id,' . $kela->id,
        ], [
            'wali_kelas_id.unique' => 'Ustadz tersebut sudah menjadi Wali Kelas di kelas lain.'
        ]);


        $kela->update($validated);
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kela = Kelas::findOrFail($id);
        
        $hasJadwal = \App\Models\Jadwal::where('kelas_id', $kela->id)->exists();
        $hasSantri = \App\Models\Santri::where('kelas_id', $kela->id)->exists();
        
        if ($hasJadwal || $hasSantri) {
            return back()->withErrors(['error' => 'Gagal: Kelas tidak bisa dihapus karena masih memiliki Jadwal Pelajaran atau Santri yang terdaftar di dalamnya.']);
        }

        $kela->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
