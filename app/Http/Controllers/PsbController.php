<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\DokumenPendaftaran;
use App\Models\PenilaianTes;
use Illuminate\Http\Request;

class PsbController extends Controller
{
    // Public Form Pendaftaran
    public function create()
    {
        return view('psb.pendaftaran');
    }

    // Process Public Form
    public function store(Request $request)
    {
        $tenant_id = request('tenant_id') ?? \App\Models\Tenant::first()->id ?? 1;

        $validated = $request->validate([
            'nisn' => [
                'nullable',
                'string',
                \Illuminate\Validation\Rule::unique('santris', 'nisn')->where(function ($query) use ($tenant_id) {
                    return $query->where('tenant_id', $tenant_id);
                })
            ],
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'no_hp_ayah' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kk_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'akte_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $orangTua = \App\Models\OrangTua::firstOrCreate(
            [
                'tenant_id' => $tenant_id,
                'no_hp_ayah' => $validated['no_hp_ayah'],
            ],
            [
                'nama_ayah' => $validated['nama_ayah'],
                'nama_ibu' => $validated['nama_ibu'],
                'alamat' => $validated['alamat'],
            ]
        );

        $santri = Santri::create([
            'tenant_id' => $tenant_id,
            'orang_tua_id' => $orangTua->id,
            'nisn' => $validated['nisn'] ?? null,
            'nama_lengkap' => $validated['nama_lengkap'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'status' => 'Draft',
        ]);

        if ($request->hasFile('kk_file')) {
            $path = $request->file('kk_file')->store('dokumen_psb', 'local');
            DokumenPendaftaran::create([
                'tenant_id' => $tenant_id,
                'santri_id' => $santri->id,
                'jenis_dokumen' => 'Kartu Keluarga',
                'file_path' => $path,
                'status_verifikasi' => 'Pending'
            ]);
        }

        if ($request->hasFile('akte_file')) {
            $path = $request->file('akte_file')->store('dokumen_psb', 'local');
            DokumenPendaftaran::create([
                'tenant_id' => $tenant_id,
                'santri_id' => $santri->id,
                'jenis_dokumen' => 'Akte Kelahiran',
                'file_path' => $path,
                'status_verifikasi' => 'Pending'
            ]);
        }

        return redirect()->back()->with('success', 'Pendaftaran berhasil dikirim! Silakan menunggu informasi lebih lanjut.');
    }

    // Cek Status Pendaftaran
    public function cekStatusForm()
    {
        return view('psb.cek_status');
    }

    public function cekStatus(Request $request)
    {
        $tenant_id = request('tenant_id') ?? \App\Models\Tenant::first()->id ?? 1;

        $request->validate([
            'nisn' => 'required|string',
            'tanggal_lahir' => 'required|date'
        ]);

        $santri = Santri::where('tenant_id', $tenant_id)
            ->where('nisn', $request->nisn)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->first();
        
        if (!$santri) {
            return back()->with('error', 'Kombinasi NISN dan Tanggal Lahir tidak ditemukan.');
        }
        
        return back()->with('status_santri', $santri);
    }

    // Admin Panel PSB
    public function index()
    {
        $calon_santris = Santri::whereIn('status', ['Draft', 'Verifikasi', 'Tes', 'Lulus', 'Tidak Lulus', 'Daftar Ulang'])
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('psb.index', compact('calon_santris'));
    }

    public function show($id)
    {
        $psb = Santri::findOrFail($id);
        $dokumens = DokumenPendaftaran::where('santri_id', $psb->id)->get();
        $penilaians = PenilaianTes::where('santri_id', $psb->id)->get();
        return view('psb.show', compact('psb', 'dokumens', 'penilaians'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Draft,Verifikasi,Tes,Lulus,Tidak Lulus,Daftar Ulang,Aktif,Alumni,Keluar']);
        $psb = Santri::findOrFail($id);
        $psb->update(['status' => $request->status]);
        return back()->with('success', 'Status pendaftaran berhasil diperbarui menjadi ' . $request->status);
    }

    public function verifyDokumen(Request $request, DokumenPendaftaran $dokumen)
    {
        $request->validate(['status_verifikasi' => 'required|in:Pending,Valid,Ditolak']);
        $dokumen->update(['status_verifikasi' => $request->status_verifikasi]);
        return back()->with('success', 'Status dokumen diperbarui.');
    }

    public function storePenilaian(Request $request, $id)
    {
        $psb = Santri::findOrFail($id);
        $validated = $request->validate([
            'jenis_tes' => 'required',
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable'
        ]);
        $validated['santri_id'] = $psb->id;
        $validated['penilai_id'] = auth()->id();
        PenilaianTes::create($validated);
        return back()->with('success', 'Nilai tes berhasil ditambahkan.');
    }
}
