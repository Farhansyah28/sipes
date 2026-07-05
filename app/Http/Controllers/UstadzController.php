<?php

namespace App\Http\Controllers;

use App\Models\Ustadz;
use Illuminate\Http\Request;

class UstadzController extends Controller
{
    public function index()
    {
        $ustadzs = Ustadz::paginate(10);
        return view('ustadz.index', compact('ustadzs'));
    }

    public function create()
    {
        return view('ustadz.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:50|unique:ustadzs,nip',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Ustadz::create($validated);
        return redirect()->route('ustadz.index')->with('success', 'Ustadz berhasil ditambahkan.');
    }

    public function edit(Ustadz $ustadz)
    {
        return view('ustadz.edit', compact('ustadz'));
    }

    public function update(Request $request, Ustadz $ustadz)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:50|unique:ustadzs,nip,' . $ustadz->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $ustadz->update($validated);
        return redirect()->route('ustadz.index')->with('success', 'Ustadz berhasil diperbarui.');
    }

    public function destroy(Ustadz $ustadz)
    {
        $hasJadwal = \App\Models\Jadwal::where('ustadz_id', $ustadz->id)->exists();
        if ($hasJadwal) {
            return back()->withErrors(['error' => 'Gagal: Ustadz tidak bisa dihapus karena sedang memiliki jadwal mengajar aktif.']);
        }

        $ustadz->delete();
        return redirect()->route('ustadz.index')->with('success', 'Ustadz berhasil dihapus.');
    }
}
