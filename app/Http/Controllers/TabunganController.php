<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use App\Models\MutasiTabungan;
use App\Models\Santri;
use App\Models\KasPesantren;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    public function index()
    {
        $tabungans = Tabungan::with('santri')->paginate(15);
        return view('tabungan.index', compact('tabungans'));
    }

    public function create()
    {
        $santris = Santri::where('status', 'Aktif')->doesntHave('tabungan')->get();
        return view('tabungan.create', compact('santris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id|unique:tabungans,santri_id',
            'saldo_awal' => 'required|numeric|min:0'
        ]);

        $tabungan = Tabungan::create([
            'santri_id' => $validated['santri_id'],
            'saldo' => $validated['saldo_awal']
        ]);

        if ($validated['saldo_awal'] > 0) {
            $mutasi = MutasiTabungan::create([
                'tabungan_id' => $tabungan->id,
                'tipe' => 'Setor',
                'nominal' => $validated['saldo_awal'],
                'tanggal' => date('Y-m-d'),
                'keterangan' => 'Setoran Awal Buka Rekening'
            ]);

            KasPesantren::create([
                'tenant_id' => 1,
                'tanggal' => date('Y-m-d'),
                'tipe' => 'Masuk',
                'kategori' => 'Setoran Tabungan',
                'nominal' => $validated['saldo_awal'],
                'keterangan' => 'Setoran Awal Tabungan Santri ID: ' . $validated['santri_id'],
                'referensi_type' => get_class($mutasi),
                'referensi_id' => $mutasi->id,
            ]);
        }

        return redirect()->route('tabungan.index')->with('success', 'Rekening Tabungan Santri berhasil dibuka.');
    }

    public function edit(Tabungan $tabungan)
    {
        $mutasis = MutasiTabungan::where('tabungan_id', $tabungan->id)->latest()->get();
        return view('tabungan.edit', compact('tabungan', 'mutasis'));
    }

    public function update(Request $request, Tabungan $tabungan)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:Setor,Tarik',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string',
        ]);

        if ($validated['tipe'] == 'Tarik' && $tabungan->saldo < $validated['nominal']) {
            return back()->withErrors(['nominal' => 'Saldo tidak mencukupi untuk penarikan ini.']);
        }

        $mutasi = MutasiTabungan::create([
            'tabungan_id' => $tabungan->id,
            'tipe' => $validated['tipe'],
            'nominal' => $validated['nominal'],
            'tanggal' => date('Y-m-d'),
            'keterangan' => $validated['keterangan']
        ]);

        KasPesantren::create([
            'tenant_id' => 1,
            'tanggal' => date('Y-m-d'),
            'tipe' => $validated['tipe'] == 'Setor' ? 'Masuk' : 'Keluar',
            'kategori' => $validated['tipe'] == 'Setor' ? 'Setoran Tabungan' : 'Tarik Tunai Tabungan',
            'nominal' => $validated['nominal'],
            'keterangan' => $validated['keterangan'] . " (Santri ID: " . $tabungan->santri_id . ")",
            'referensi_type' => get_class($mutasi),
            'referensi_id' => $mutasi->id,
        ]);

        if ($validated['tipe'] == 'Setor') {
            $tabungan->saldo += $validated['nominal'];
        } else {
            $tabungan->saldo -= $validated['nominal'];
        }
        $tabungan->save();

        return redirect()->route('tabungan.edit', $tabungan->id)->with('success', 'Mutasi tabungan berhasil dicatat.');
    }

    public function massTopup(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $kelases = \App\Models\Kelas::all();
        $tabungans = collect();
        if ($kelas_id) {
            $tabungans = Tabungan::with('santri')->whereHas('santri', function($q) use ($kelas_id) {
                $q->where('kelas_id', $kelas_id)->where('status', 'Aktif');
            })->get();
        }
        return view('tabungan.mass_topup', compact('kelases', 'kelas_id', 'tabungans'));
    }

    public function storeMassTopup(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required',
            'nominal' => 'required|array',
            'keterangan' => 'required|string',
        ]);

        $count = 0;
        foreach ($validated['nominal'] as $tabungan_id => $nominal) {
            if ($nominal > 0) {
                $tabungan = Tabungan::find($tabungan_id);
                if ($tabungan) {
                    $tabungan->saldo += $nominal;
                    $tabungan->save();
                    $mutasi = MutasiTabungan::create([
                        'tabungan_id' => $tabungan->id,
                        'tipe' => 'Setor',
                        'nominal' => $nominal,
                        'tanggal' => date('Y-m-d'),
                        'keterangan' => $validated['keterangan']
                    ]);
                    KasPesantren::create([
                        'tenant_id' => 1,
                        'tanggal' => date('Y-m-d'),
                        'tipe' => 'Masuk',
                        'kategori' => 'Setoran Tabungan',
                        'nominal' => $nominal,
                        'keterangan' => $validated['keterangan'] . " (Massal Santri ID: " . $tabungan->santri_id . ")",
                        'referensi_type' => get_class($mutasi),
                        'referensi_id' => $mutasi->id,
                    ]);
                    $count++;
                }
            }
        }
        return back()->with('success', "Top-up massal berhasil diproses untuk {$count} santri.");
    }

    public function massWithdrawal(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $kelases = \App\Models\Kelas::all();
        $tabungans = collect();
        if ($kelas_id) {
            $tabungans = Tabungan::with('santri')->whereHas('santri', function($q) use ($kelas_id) {
                $q->where('kelas_id', $kelas_id)->where('status', 'Aktif');
            })->get();
        }
        return view('tabungan.mass_withdrawal', compact('kelases', 'kelas_id', 'tabungans'));
    }

    public function storeMassWithdrawal(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required',
            'nominal' => 'required|array',
            'keterangan' => 'required|string',
        ]);

        $count = 0;
        $failed = 0;
        foreach ($validated['nominal'] as $tabungan_id => $nominal) {
            if ($nominal > 0) {
                $tabungan = Tabungan::find($tabungan_id);
                if ($tabungan && $tabungan->saldo >= $nominal) {
                    $tabungan->saldo -= $nominal;
                    $tabungan->save();
                    $mutasi = MutasiTabungan::create([
                        'tabungan_id' => $tabungan->id,
                        'tipe' => 'Tarik',
                        'nominal' => $nominal,
                        'tanggal' => date('Y-m-d'),
                        'keterangan' => $validated['keterangan']
                    ]);
                    KasPesantren::create([
                        'tenant_id' => 1,
                        'tanggal' => date('Y-m-d'),
                        'tipe' => 'Keluar',
                        'kategori' => 'Tarik Tunai Tabungan',
                        'nominal' => $nominal,
                        'keterangan' => $validated['keterangan'] . " (Massal Santri ID: " . $tabungan->santri_id . ")",
                        'referensi_type' => get_class($mutasi),
                        'referensi_id' => $mutasi->id,
                    ]);
                    $count++;
                } else {
                    $failed++;
                }
            }
        }
        
        $msg = "Penarikan massal berhasil diproses untuk {$count} santri.";
        if ($failed > 0) {
            $msg .= " Namun, {$failed} santri gagal ditarik karena saldo tidak mencukupi.";
        }
        return back()->with('success', $msg);
    }
}
