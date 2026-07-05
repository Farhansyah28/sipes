<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKamar;
use App\Models\Santri;
use App\Models\Kamar;
use Illuminate\Http\Request;

class RiwayatKamarController extends Controller
{
    public function index()
    {
        $riwayats = RiwayatKamar::with(['santri', 'kamar.gedung'])
            ->whereNull('tanggal_keluar')
            ->orderBy('id', 'desc')
            ->paginate(15);
        $kamars = Kamar::with('gedung')->get();
        $santris = Santri::where('status', 'Aktif')->get();
        return view('riwayat_kamar.index', compact('riwayats', 'kamars', 'santris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal_masuk' => 'required|date'
        ]);

        // Check kamar capacity
        $kamar = Kamar::findOrFail($validated['kamar_id']);
        $currentOccupants = Santri::where('kamar_id', $kamar->id)->where('status', 'Aktif')->count();
        if ($currentOccupants >= $kamar->kapasitas) {
            return back()->with('error', 'Kapasitas kamar sudah penuh! (Maks: ' . $kamar->kapasitas . ' santri)');
        }

        // Check Gender Segregation
        $santri = Santri::findOrFail($validated['santri_id']);
        $gedung = $kamar->gedung;
        if ($gedung->jenis_kelamin !== 'Campur' && $gedung->jenis_kelamin !== $santri->jenis_kelamin) {
            $genderGedung = $gedung->jenis_kelamin == 'L' ? 'Putra' : 'Putri';
            $genderSantri = $santri->jenis_kelamin == 'L' ? 'Putra' : 'Putri';
            return back()->with('error', "Pelanggaran Gender: Tidak bisa menempatkan santri {$genderSantri} di asrama {$genderGedung}!");
        }

        // End current active placement if any
        $active = RiwayatKamar::where('santri_id', $validated['santri_id'])
            ->whereNull('tanggal_keluar')
            ->first();
        if ($active) {
            if (strtotime($validated['tanggal_masuk']) <= strtotime($active->tanggal_masuk)) {
                return back()->with('error', 'Tanggal masuk baru harus lebih besar dari tanggal masuk saat ini (' . $active->tanggal_masuk . ')');
            }
            $active->update(['tanggal_keluar' => $validated['tanggal_masuk']]);
        }

        RiwayatKamar::create($validated);
        
        Santri::where('id', $validated['santri_id'])->update(['kamar_id' => $validated['kamar_id']]);

        return back()->with('success', 'Santri berhasil ditempatkan di kamar baru.');
    }

    public function update(Request $request, RiwayatKamar $riwayat_kamar)
    {
        $request->validate(['tanggal_keluar' => 'required|date']);
        
        if (strtotime($request->tanggal_keluar) < strtotime($riwayat_kamar->tanggal_masuk)) {
            return back()->with('error', 'Tanggal keluar tidak boleh mendahului tanggal masuk (' . $riwayat_kamar->tanggal_masuk . ').');
        }

        $riwayat_kamar->update(['tanggal_keluar' => $request->tanggal_keluar]);
        
        Santri::where('id', $riwayat_kamar->santri_id)->update(['kamar_id' => null]);
        
        return back()->with('success', 'Santri berhasil di-checkout dari kamar.');
    }
}
