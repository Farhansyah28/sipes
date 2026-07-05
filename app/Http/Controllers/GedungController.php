<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;

class GedungController extends Controller
{
    public function index()
    {
        $gedungs = Gedung::paginate(10);
        return view('gedung.index', compact('gedungs'));
    }

    public function create()
    {
        return view('gedung.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:gedungs,nama',
            'jenis_kelamin' => 'required|in:L,P,Campur',
            'deskripsi' => 'nullable|string',
        ]);

        Gedung::create($validated);
        return redirect()->route('gedung.index')->with('success', 'Data gedung berhasil ditambahkan.');
    }

    public function edit(Gedung $gedung)
    {
        return view('gedung.edit', compact('gedung'));
    }

    public function update(Request $request, Gedung $gedung)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:gedungs,nama,' . $gedung->id,
            'jenis_kelamin' => 'required|in:L,P,Campur',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validated['jenis_kelamin'] !== 'Campur' && $gedung->jenis_kelamin !== $validated['jenis_kelamin']) {
            $mismatchCount = \App\Models\Santri::whereHas('kamar', function($q) use ($gedung) {
                $q->where('gedung_id', $gedung->id);
            })->where('status', 'Aktif')
              ->where('jenis_kelamin', '!=', $validated['jenis_kelamin'])
              ->count();

            if ($mismatchCount > 0) {
                return back()->withErrors(['jenis_kelamin' => 'Gagal mengubah tipe asrama. Ada ' . $mismatchCount . ' santri dengan gender yang berlawanan sedang menghuni gedung ini.']);
            }
        }

        $gedung->update($validated);
        return redirect()->route('gedung.index')->with('success', 'Data gedung berhasil diperbarui.');
    }

    public function destroy(Gedung $gedung)
    {
        $hasKamar = \App\Models\Kamar::where('gedung_id', $gedung->id)->exists();
        if ($hasKamar) {
            return back()->withErrors(['error' => 'Gagal: Gedung tidak bisa dihapus karena masih memiliki Kamar di dalamnya.']);
        }

        $gedung->delete();
        return redirect()->route('gedung.index')->with('success', 'Data gedung berhasil dihapus.');
    }
}
