<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ustadz;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\PengampuPelajaran;
use App\Models\KetersediaanUstadz;
use App\Models\JamPelajaran;

class PenugasanUstadzController extends Controller
{
    public function index(Request $request)
    {
        $ustadzs = Ustadz::orderBy('nama_lengkap')->get();
        $ustadz_id = $request->get('ustadz_id');
        $selected_ustadz = null;
        
        $pengampus = [];
        $ketersediaans = [];
        $jam_pelajarans = JamPelajaran::orderBy('jam_ke')->get();
        
        if ($ustadz_id) {
            $selected_ustadz = Ustadz::findOrFail($ustadz_id);
            $pengampus = PengampuPelajaran::with(['mata_pelajaran', 'kelas'])
                            ->where('ustadz_id', $ustadz_id)
                            ->get();
            $ketersediaans = KetersediaanUstadz::where('ustadz_id', $ustadz_id)
                            ->get()
                            ->map(function($k) {
                                return $k->hari . '_' . $k->jam_ke;
                            })->toArray();
        }

        $mapels = MataPelajaran::orderBy('nama')->get();
        $kelases = Kelas::orderBy('nama')->get();
        $hari_aktif = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Jika jam pelajaran kosong (belum di-seed), fallback ke 1-8
        if ($jam_pelajarans->isEmpty()) {
            $jam_pelajarans = collect(range(1, 8))->map(function($jam) {
                return (object)['jam_ke' => $jam, 'waktu_mulai' => '', 'waktu_selesai' => ''];
            });
        }

        return view('ustadz.penugasan', compact(
            'ustadzs', 'selected_ustadz', 'pengampus', 'ketersediaans', 
            'mapels', 'kelases', 'jam_pelajarans', 'hari_aktif'
        ));
    }

    public function addPengampu(Request $request, $ustadz_id)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'beban_jp' => 'required|integer|min:1',
        ]);

        // Cek duplikasi
        $exists = PengampuPelajaran::where('ustadz_id', $ustadz_id)
                    ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                    ->where('kelas_id', $request->kelas_id)
                    ->exists();
        
        if ($exists) {
            return back()->withErrors(['error' => 'Ustadz sudah ditugaskan untuk Mata Pelajaran ini di Kelas tersebut.']);
        }

        PengampuPelajaran::create([
            'ustadz_id' => $ustadz_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'beban_jp' => $request->beban_jp,
        ]);

        return redirect()->route('penugasan.ustadz.index', ['ustadz_id' => $ustadz_id])->with('success', 'Berhasil menambahkan penugasan kelas & mapel.');
    }

    public function deletePengampu($id)
    {
        $p = PengampuPelajaran::findOrFail($id);
        $ustadz_id = $p->ustadz_id;
        $p->delete();

        return redirect()->route('penugasan.ustadz.index', ['ustadz_id' => $ustadz_id])->with('success', 'Penugasan berhasil dihapus.');
    }

    public function saveKetersediaan(Request $request, $ustadz_id)
    {
        // Toggle (Klik = Tidak Bisa Mengajar)
        // Jika kotak merah di klik (dicentang di backend), kita simpan ke ketersediaan_ustadzs
        
        $request->validate([
            'hari' => 'required|string|max:10',
            'jam_ke' => 'required|integer|min:1',
        ]);

        $hari = $request->input('hari');
        $jam_ke = $request->input('jam_ke');
        
        $existing = KetersediaanUstadz::where('ustadz_id', $ustadz_id)
                        ->where('hari', $hari)
                        ->where('jam_ke', $jam_ke)
                        ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'available']);
        } else {
            KetersediaanUstadz::create([
                'ustadz_id' => $ustadz_id,
                'hari' => $hari,
                'jam_ke' => $jam_ke,
            ]);
            return response()->json(['status' => 'unavailable']);
        }
    }
}
