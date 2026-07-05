<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Santri;
use App\Models\TahunAjaran;
use App\Models\RiwayatKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $tahun_ajarans = TahunAjaran::where('is_active', true)->get();
        
        $santris = collect();
        if ($request->has('kelas_asal')) {
            $santris = Santri::where('kelas_id', $request->kelas_asal)
                             ->where('status', 'Aktif')
                             ->with('kelas')
                             ->get();
        }

        return view('akademik.kenaikan_kelas.index', compact('kelas', 'tahun_ajarans', 'santris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_tujuan' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'santri_ids' => 'required|array|min:1',
            'santri_ids.*' => 'exists:santris,id',
        ]);

        try {
            DB::beginTransaction();
            
            $now = now();
            $tenant_id = auth()->user()->tenant_id;
            $riwayat_inserts = [];
            
            // Dapatkan santri untuk merekam kelas asalnya jika perlu
            $santris = Santri::whereIn('id', $request->santri_ids)->get();

            foreach ($santris as $santri) {
                // Record history
                $riwayat_inserts[] = [
                    'tenant_id' => $tenant_id,
                    'santri_id' => $santri->id,
                    'kelas_id' => $request->kelas_tujuan,
                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                    'tanggal_mulai' => $now->toDateString(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            RiwayatKelas::insert($riwayat_inserts);

            // Bulk update santri
            Santri::whereIn('id', $request->santri_ids)->update([
                'kelas_id' => $request->kelas_tujuan
            ]);

            DB::commit();

            return redirect()->route('kenaikan_kelas.index')->with('success', count($request->santri_ids) . ' Santri berhasil dinaikkan kelasnya.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat proses kenaikan kelas: ' . $e->getMessage()]);
        }
    }
}
