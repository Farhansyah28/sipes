<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\OrangTua;
use App\Models\Kelas;
use App\Models\Kamar;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index()
    {
        $santris = Santri::with(['orangTua', 'kelas', 'kamar'])->paginate(10);
        return view('santri.index', compact('santris'));
    }

    public function create()
    {
        $orang_tuas = OrangTua::all();
        $kelas = Kelas::all();
        $kamars = Kamar::all();
        return view('santri.create', compact('orang_tuas', 'kelas', 'kamars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'nullable|string|max:50|unique:santris,nis',
            'nisn' => 'nullable|string|max:50|unique:santris,nisn',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'status' => 'required|string',
            'orang_tua_id' => 'nullable|exists:orang_tuas,id',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        Santri::create($validated);
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil ditambahkan.');
    }

    public function edit(Santri $santri)
    {
        $orang_tuas = OrangTua::all();
        $kelas = Kelas::all();
        $kamars = Kamar::all();
        return view('santri.edit', compact('santri', 'orang_tuas', 'kelas', 'kamars'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nis' => 'nullable|string|max:50|unique:santris,nis,' . $santri->id,
            'nisn' => 'nullable|string|max:50|unique:santris,nisn,' . $santri->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'status' => 'required|string',
            'orang_tua_id' => 'nullable|exists:orang_tuas,id',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        if ($validated['jenis_kelamin'] !== $santri->jenis_kelamin && $santri->kamar_id) {
            $gedung = \App\Models\Kamar::find($santri->kamar_id)->gedung;
            if ($gedung && $gedung->jenis_kelamin !== 'Campur' && $gedung->jenis_kelamin !== $validated['jenis_kelamin']) {
                $genderGedung = $gedung->jenis_kelamin == 'L' ? 'Putra' : 'Putri';
                return back()->withErrors(['jenis_kelamin' => "Gagal mengubah gender. Santri ini sedang menempati Gedung Asrama {$genderGedung}. Silakan checkout santri dari asrama terlebih dahulu sebelum mengubah gender."]);
            }
        }

        if ($validated['status'] !== 'Aktif' && $santri->status === 'Aktif' && $santri->kamar_id) {
            // Auto checkout from kamar
            $activeRiwayat = \App\Models\RiwayatKamar::where('santri_id', $santri->id)
                ->whereNull('tanggal_keluar')
                ->first();
            if ($activeRiwayat) {
                $activeRiwayat->update(['tanggal_keluar' => now()]);
            }
            $validated['kamar_id'] = null;
        }

        $santri->update($validated);
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        $hasTagihan = \App\Models\Tagihan::where('santri_id', $santri->id)->exists();
        $hasTabungan = \App\Models\Tabungan::where('santri_id', $santri->id)->where('saldo', '>', 0)->exists();
        $hasNilai = \App\Models\Nilai::where('santri_id', $santri->id)->exists();
        
        if ($hasTagihan || $hasTabungan || $hasNilai) {
            return back()->withErrors(['error' => 'Gagal: Data Santri ini tidak boleh dihapus karena memiliki riwayat Tagihan, Saldo Tabungan, atau Nilai Akademik. Silakan ubah Status santri menjadi "Keluar" atau "Alumni" untuk menonaktifkannya.']);
        }

        $santri->delete();
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil dihapus.');
    }
}
