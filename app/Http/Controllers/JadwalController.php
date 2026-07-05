<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Ustadz;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['semester', 'kelas', 'mataPelajaran', 'ustadz'])->get();
        $semesters = Semester::all();
        $kelases = Kelas::all();
        $mapels = MataPelajaran::all();
        $ustadzs = Ustadz::all();
        
        // Ambil referensi Jam Pelajaran unik berdasarkan jam_mulai dan jam_selesai untuk header baris grid
        $jamList = \App\Models\JamPelajaran::select('jam_mulai', 'jam_selesai', 'is_istirahat')
            ->distinct()
            ->orderBy('jam_mulai')
            ->get();
            
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('akademik.jadwal.index', compact('jadwals', 'semesters', 'kelases', 'mapels', 'ustadzs', 'jamList', 'hariList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester_id' => 'required',
            'kelas_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'ustadz_id' => 'required',
            'hari' => 'required',
            'waktu' => 'required'
        ]);

        $waktuArr = explode('-', $validated['waktu']);
        $jamMulaiBaru = trim($waktuArr[0]);
        $jamSelesaiBaru = trim($waktuArr[1]);

        // VALIDASI 1: Cek apakah Kelas sudah memiliki jadwal di waktu ini
        $bentrokKelas = Jadwal::where('semester_id', $validated['semester_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('hari', $validated['hari'])
            ->where(function($query) use ($jamMulaiBaru, $jamSelesaiBaru) {
                $query->where(function($q) use ($jamMulaiBaru, $jamSelesaiBaru) {
                    $q->where('jam_mulai', '<', $jamSelesaiBaru)
                      ->where('jam_selesai', '>', $jamMulaiBaru);
                });
            })->first();

        if ($bentrokKelas) {
            return back()->withErrors(['error' => 'Gagal: Kelas ini sudah memiliki mata pelajaran lain ('.$bentrokKelas->mataPelajaran->nama.') di waktu yang bersinggungan.']);
        }

        // VALIDASI 2: Cek apakah Ustadz sudah mengajar di kelas lain di waktu ini
        $bentrokUstadz = Jadwal::where('semester_id', $validated['semester_id'])
            ->where('ustadz_id', $validated['ustadz_id'])
            ->where('hari', $validated['hari'])
            ->where(function($query) use ($jamMulaiBaru, $jamSelesaiBaru) {
                $query->where(function($q) use ($jamMulaiBaru, $jamSelesaiBaru) {
                    $q->where('jam_mulai', '<', $jamSelesaiBaru)
                      ->where('jam_selesai', '>', $jamMulaiBaru);
                });
            })->first();

        if ($bentrokUstadz) {
            return back()->withErrors(['error' => 'Gagal: Ustadz tersebut sudah memiliki jadwal mengajar di kelas '.$bentrokUstadz->kelas->nama.' pada waktu yang bersinggungan.']);
        }

        Jadwal::create([
            'tenant_id' => 1,
            'semester_id' => $validated['semester_id'],
            'kelas_id' => $validated['kelas_id'],
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
            'ustadz_id' => $validated['ustadz_id'],
            'hari' => $validated['hari'],
            'jam_mulai' => $jamMulaiBaru,
            'jam_selesai' => $jamSelesaiBaru,
        ]);

        return back()->with('success', 'Jadwal ditambahkan.');
    }
    
    public function destroy(Jadwal $jadwal)
    {
        // Cegah penghapusan jika sudah ada nilai atau absensi
        $hasNilai = \App\Models\Nilai::where('jadwal_id', $jadwal->id)->exists();
        $hasAbsensi = \App\Models\Absensi::where('jadwal_id', $jadwal->id)->exists();

        if ($hasNilai || $hasAbsensi) {
            return back()->withErrors(['error' => 'Gagal: Jadwal tidak bisa dihapus karena sudah memiliki riwayat Nilai atau Absensi santri. Penghapusan ini akan menghilangkan data akademik.']);
        }

        $jadwal->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }
}
