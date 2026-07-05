<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JamPelajaran;
use App\Models\PengampuPelajaran;
use App\Models\KetersediaanUstadz;
use App\Models\Ustadz;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Services\TimetableService;

class AutoScheduleController extends Controller
{
    public function index()
    {
        $pengampus = PengampuPelajaran::with(['ustadz', 'mata_pelajaran', 'kelas'])->get();
        $ketersediaans = KetersediaanUstadz::with('ustadz')->get();
        $ustadzs = Ustadz::all();
        $mapels = MataPelajaran::all();
        $kelas = Kelas::all();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $jamList = JamPelajaran::select('jam_ke')->distinct()->orderBy('jam_ke')->get();

        return view('auto_schedule.index', compact('pengampus', 'ketersediaans', 'ustadzs', 'mapels', 'kelas', 'hariList', 'jamList'));
    }

    public function storePengampu(Request $request)
    {
        $validated = $request->validate([
            'ustadz_id' => 'required|exists:ustadzs,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'beban_jp' => 'required|integer|min:1'
        ]);
        
        $validated['tenant_id'] = 1;
        PengampuPelajaran::create($validated);
        
        return redirect()->route('auto_schedule.index')->with('success', 'Beban ajar ditambahkan.');
    }

    public function destroyPengampu($id)
    {
        PengampuPelajaran::findOrFail($id)->delete();
        return redirect()->route('auto_schedule.index')->with('success', 'Beban ajar dihapus.');
    }

    public function storeKetersediaan(Request $request)
    {
        $validated = $request->validate([
            'ustadz_id' => 'required|exists:ustadzs,id',
            'hari' => 'required|string',
            'jam_ke' => 'required|integer'
        ]);
        
        $validated['tenant_id'] = 1;
        KetersediaanUstadz::firstOrCreate($validated);
        
        return redirect()->route('auto_schedule.index')->with('success', 'Ketersediaan waktu ditambahkan.');
    }

    public function destroyKetersediaan($id)
    {
        KetersediaanUstadz::findOrFail($id)->delete();
        return redirect()->route('auto_schedule.index')->with('success', 'Ketersediaan waktu dihapus.');
    }

    public function process(Request $request, TimetableService $service)
    {
        $result = $service->generate();
        
        if ($result['success']) {
            return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil digenerate otomatis!');
        } else {
            return back()->with('error', 'Gagal membuat jadwal: ' . $result['message']);
        }
    }
}
