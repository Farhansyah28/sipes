<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Santri;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::with(['kelas', 'mataPelajaran'])->get();
        $jadwal_id = $request->jadwal_id;
        $tanggal = $request->tanggal ?? date('Y-m-d');
        
        $santris = collect();
        $absensi = collect();
        
        if ($jadwal_id) {
            $jadwal = Jadwal::findOrFail($jadwal_id);
            $santris = Santri::where('kelas_id', $jadwal->kelas_id)->where('status', 'Aktif')->get();
            $absensi = Absensi::where('jadwal_id', $jadwal_id)
                ->where('tanggal', $tanggal)
                ->get()->keyBy('santri_id');
        }

        return view('akademik.absensi.index', compact('jadwals', 'jadwal_id', 'tanggal', 'santris', 'absensi'));
    }

    public function store(Request $request)
    {
        $jadwal_id = $request->jadwal_id;
        $tanggal = $request->tanggal;
        $statuses = $request->status;

        if (!$jadwal_id || !$tanggal) {
            return back()->withErrors(['error' => 'Gagal: Jadwal Pelajaran dan Tanggal harus dipilih.']);
        }

        if ($statuses) {
            foreach ($statuses as $santri_id => $status) {
                Absensi::updateOrCreate(
                    ['jadwal_id' => $jadwal_id, 'tanggal' => $tanggal, 'santri_id' => $santri_id],
                    ['status' => $status]
                );
            }
        }
        return back()->with('success', 'Absensi berhasil disimpan.');
    }
}
