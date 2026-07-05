<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::with(['kelas', 'mataPelajaran'])->get();
        $jadwal_id = $request->jadwal_id;
        $jenis_nilai = $request->jenis_nilai ?? 'Tugas';
        
        $santris = collect();
        $nilais = collect();
        
        if ($jadwal_id) {
            $jadwal = Jadwal::findOrFail($jadwal_id);
            $santris = Santri::where('kelas_id', $jadwal->kelas_id)->where('status', 'Aktif')->get();
            $nilais = Nilai::where('jadwal_id', $jadwal_id)
                ->where('jenis_nilai', $jenis_nilai)
                ->get()->keyBy('santri_id');
        }

        return view('akademik.nilai.index', compact('jadwals', 'jadwal_id', 'jenis_nilai', 'santris', 'nilais'));
    }

    public function store(Request $request)
    {
        $jadwal_id = $request->jadwal_id;
        $jenis_nilai = $request->jenis_nilai;
        $nilais = $request->nilai;

        // Validasi input
        if (!$jadwal_id || !$jenis_nilai) {
            return back()->withErrors(['error' => 'Jadwal dan Jenis Nilai harus dipilih.']);
        }

        if ($nilais) {
            foreach ($nilais as $santri_id => $nilai) {
                if ($nilai !== null && $nilai !== '') {
                    // Cek jika nilai tidak masuk akal (lebih dari 100 atau minus)
                    if ($nilai > 100 || $nilai < 0) {
                        return back()->withErrors(['error' => 'Gagal: Skor nilai tidak boleh lebih dari 100 atau kurang dari 0. Silakan periksa kembali input Anda.'])->withInput();
                    }

                    Nilai::updateOrCreate(
                        ['jadwal_id' => $jadwal_id, 'jenis_nilai' => $jenis_nilai, 'santri_id' => $santri_id],
                        ['nilai' => $nilai]
                    );
                }
            }
        }
        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    public function raport(Request $request)
    {
        $santris = Santri::where('status', 'Aktif')->get();
        $semesters = Semester::all();
        $santri_id = $request->santri_id;
        $semester_id = $request->semester_id;
        
        $raport = [];
        $rekap_absensi = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
        $santri = null;

        if ($santri_id && $semester_id) {
            $santri = Santri::with(['kelas'])->findOrFail($santri_id);
            
            // Ambil semua nilai santri ini di semester yang dipilih, abaikan kelas saat ini
            $nilais = Nilai::with('jadwal')->where('santri_id', $santri_id)
                ->whereHas('jadwal', function($q) use ($semester_id) {
                    $q->where('semester_id', $semester_id);
                })
                ->get();
                
            // Ambil satu jadwal sebagai referensi untuk mengetahui kelas historis santri di semester tersebut
            $jadwalReferensi = $nilais->first()->jadwal ?? null;
            $kelas_id_historis = $jadwalReferensi ? $jadwalReferensi->kelas_id : $santri->kelas_id;

            // Ambil SEMUA jadwal (mata pelajaran) untuk kelas historis tersebut di semester ini
            $jadwals = Jadwal::with('mataPelajaran')
                ->where('semester_id', $semester_id)
                ->where('kelas_id', $kelas_id_historis)
                ->get();
                
            $jadwal_ids = $jadwals->pluck('id')->toArray();
            
            foreach ($jadwals as $jadwal) {
                $n = $nilais->where('jadwal_id', $jadwal->id);
                $tugas = $n->where('jenis_nilai', 'Tugas')->first()->nilai ?? 0;
                $uts = $n->where('jenis_nilai', 'UTS')->first()->nilai ?? 0;
                $uas = $n->where('jenis_nilai', 'UAS')->first()->nilai ?? 0;
                
                // Kalkulasi Nilai Akhir (Tugas 30%, UTS 30%, UAS 40%)
                $nilai_akhir = ($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4);
                
                // Penentuan Predikat
                $predikat = 'D';
                if ($nilai_akhir >= 90) $predikat = 'A';
                elseif ($nilai_akhir >= 80) $predikat = 'B';
                elseif ($nilai_akhir >= 70) $predikat = 'C';

                $raport[] = [
                    'mata_pelajaran' => $jadwal->mataPelajaran->nama,
                    'ustadz' => $jadwal->ustadz->nama_lengkap ?? '-',
                    'tugas' => $tugas,
                    'uts' => $uts,
                    'uas' => $uas,
                    'nilai_akhir' => round($nilai_akhir, 2),
                    'predikat' => $predikat
                ];
            }
            
            // Ambil data absensi
            $absensis = \App\Models\Absensi::where('santri_id', $santri_id)
                ->whereIn('jadwal_id', $jadwal_ids)
                ->get();
                
            foreach ($absensis as $absen) {
                if (isset($rekap_absensi[$absen->status])) {
                    $rekap_absensi[$absen->status]++;
                }
            }
        }

        return view('akademik.raport.index', compact('santris', 'semesters', 'santri_id', 'semester_id', 'santri', 'raport', 'rekap_absensi'));
    }
}
