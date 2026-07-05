<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Santri;
use App\Models\KategoriTagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihans = Tagihan::with(['santri', 'kategoriTagihan'])->latest()->paginate(15);
        return view('tagihan.index', compact('tagihans'));
    }

    public function create()
    {
        $santris = Santri::where('status', 'Aktif')->get();
        $kategoris = KategoriTagihan::all();
        return view('tagihan.create', compact('santris', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kategori_tagihan_id' => 'required|exists:kategori_tagihans,id',
            'jatuh_tempo' => 'nullable|date',
            'bulan_tagihan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = KategoriTagihan::findOrFail($request->kategori_tagihan_id);
        
        $validated['nominal'] = $kategori->nominal_default;
        $validated['sisa_tagihan'] = $kategori->nominal_default;
        $validated['status'] = 'Belum Bayar';

        Tagihan::create($validated);
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dibuat.');
    }

    public function destroy(Tagihan $tagihan)
    {
        if ($tagihan->status === 'Lunas') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat dihapus.');
        }
        $tagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dihapus.');
    }

    public function print(Tagihan $tagihan)
    {
        $tagihan->load(['santri.kelas', 'kategoriTagihan']);
        return view('tagihan.print', compact('tagihan'));
    }

    public function bulkCreate()
    {
        $kelases = \App\Models\Kelas::all();
        $kategoris = KategoriTagihan::all();
        return view('tagihan.bulk', compact('kelases', 'kategoris'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required',
            'kategori_tagihan_id' => 'required|exists:kategori_tagihans,id',
            'jatuh_tempo' => 'nullable|date',
            'bulan_tagihan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = KategoriTagihan::findOrFail($request->kategori_tagihan_id);

        $query = Santri::where('status', 'Aktif');
        if ($validated['kelas_id'] !== 'all') {
            $query->where('kelas_id', $validated['kelas_id']);
        }
        $santris = $query->get();

        $count = 0;
        foreach ($santris as $santri) {
            Tagihan::create([
                'tenant_id' => $santri->tenant_id,
                'santri_id' => $santri->id,
                'kategori_tagihan_id' => $kategori->id,
                'nominal' => $kategori->nominal_default,
                'sisa_tagihan' => $kategori->nominal_default,
                'jatuh_tempo' => $validated['jatuh_tempo'],
                'bulan_tagihan' => $validated['bulan_tagihan'],
                'keterangan' => $validated['keterangan'],
                'status' => 'Belum Bayar'
            ]);
            $count++;
        }

        return redirect()->route('tagihan.index')->with('success', "Generate tagihan massal berhasil untuk {$count} santri.");
    }
}
