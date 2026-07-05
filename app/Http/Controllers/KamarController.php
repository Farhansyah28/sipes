<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::with('gedung')->paginate(10);
        return view('kamar.index', compact('kamars'));
    }

    public function create()
    {
        $gedungs = Gedung::all();
        return view('kamar.create', compact('gedungs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gedung_id' => 'required|exists:gedungs,id',
            'nama' => [
                'required', 'string', 'max:255',
                Rule::unique('kamars')->where(function ($query) use ($request) {
                    return $query->where('gedung_id', $request->gedung_id);
                })
            ],
            'kapasitas' => 'required|integer|min:1',
        ]);

        Kamar::create($validated);
        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil ditambahkan.');
    }

    public function edit(Kamar $kamar)
    {
        $gedungs = Gedung::all();
        return view('kamar.edit', compact('kamar', 'gedungs'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'gedung_id' => 'required|exists:gedungs,id',
            'nama' => [
                'required', 'string', 'max:255',
                Rule::unique('kamars')->where(function ($query) use ($request) {
                    return $query->where('gedung_id', $request->gedung_id);
                })->ignore($kamar->id)
            ],
            'kapasitas' => 'required|integer|min:1',
        ]);

        $currentOccupants = \App\Models\Santri::where('kamar_id', $kamar->id)->where('status', 'Aktif')->count();
        if ($validated['kapasitas'] < $currentOccupants) {
            return back()->withErrors(['kapasitas' => 'Kapasitas tidak boleh lebih kecil dari jumlah penghuni aktif saat ini (' . $currentOccupants . ' santri).']);
        }

        $kamar->update($validated);
        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        $hasRiwayat = \App\Models\RiwayatKamar::where('kamar_id', $kamar->id)->exists();
        $hasSantri = \App\Models\Santri::where('kamar_id', $kamar->id)->exists();
        
        if ($hasRiwayat || $hasSantri) {
            return back()->withErrors(['error' => 'Gagal: Kamar tidak bisa dihapus karena masih dihuni Santri atau memiliki Riwayat Kamar. Penghapusan akan menghilangkan riwayat asrama santri.']);
        }

        $kamar->delete();
        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil dihapus.');
    }
}
