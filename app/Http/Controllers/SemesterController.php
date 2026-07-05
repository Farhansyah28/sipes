<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::with('tahunAjaran')->paginate(10);
        return view('semester.index', compact('semesters'));
    }

    public function create()
    {
        $tahun_ajarans = TahunAjaran::all();
        return view('semester.create', compact('tahun_ajarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nama' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        if ($validated['is_active']) {
            Semester::query()->update(['is_active' => false]);
        }

        Semester::create($validated);
        return redirect()->route('semester.index')->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester)
    {
        $tahun_ajarans = TahunAjaran::all();
        return view('semester.edit', compact('semester', 'tahun_ajarans'));
    }

    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nama' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        if ($validated['is_active']) {
            Semester::query()->where('id', '!=', $semester->id)->update(['is_active' => false]);
        }

        $semester->update($validated);
        return redirect()->route('semester.index')->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester)
    {
        $hasJadwal = \App\Models\Jadwal::where('semester_id', $semester->id)->exists();
        if ($hasJadwal) {
            return back()->withErrors(['error' => 'Gagal: Semester tidak bisa dihapus karena sudah memiliki Jadwal Pelajaran yang terkait. Penghapusan ini dapat menghilangkan seluruh riwayat akademik.']);
        }
        
        $semester->delete();
        return redirect()->route('semester.index')->with('success', 'Semester berhasil dihapus.');
    }
}
