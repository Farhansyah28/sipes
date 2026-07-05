<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Tabungan;
use App\Models\Absensi;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    private function getOrangTua()
    {
        return Auth::user()->orangTua;
    }

    private function validateAnak($santri_id)
    {
        $orangTua = $this->getOrangTua();
        $santri = Santri::findOrFail($santri_id);
        
        if ($santri->orang_tua_id !== $orangTua->id) {
            abort(403, 'Anda tidak memiliki akses ke data santri ini.');
        }

        return $santri;
    }

    public function dashboard()
    {
        $orangTua = $this->getOrangTua();
        if (!$orangTua) {
            return redirect()->route('portal.login')->withErrors(['error' => 'Data profil orang tua tidak lengkap.']);
        }

        $anak = $orangTua->santris()->with('kamar')->get();

        // Ringkasan tagihan
        $total_tunggakan = Tagihan::whereIn('santri_id', $anak->pluck('id'))
            ->whereIn('status', ['Belum Bayar', 'Sebagian'])
            ->sum('sisa_tagihan');

        return view('portal.dashboard', compact('orangTua', 'anak', 'total_tunggakan'));
    }

    public function akademik($santri_id)
    {
        $santri = $this->validateAnak($santri_id);
        $absensis = Absensi::where('santri_id', $santri_id)->latest('tanggal')->take(30)->get();
        $nilais = Nilai::where('santri_id', $santri_id)->with(['jadwal.mataPelajaran', 'jadwal.semester'])->latest()->get();

        return view('portal.akademik', compact('santri', 'absensis', 'nilais'));
    }

    public function keuangan($santri_id)
    {
        $santri = $this->validateAnak($santri_id);
        $tagihans = Tagihan::where('santri_id', $santri_id)->with('kategoriTagihan')->latest()->get();

        return view('portal.keuangan', compact('santri', 'tagihans'));
    }

    public function uploadBuktiBayar(Request $request, $tagihan_id)
    {
        $tagihan = Tagihan::findOrFail($tagihan_id);
        
        $request->validate([
            'nominal' => 'required|numeric|min:1000|max:' . $tagihan->sisa_tagihan,
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nominal.max' => 'Nominal pembayaran tidak boleh melebihi sisa tagihan (Rp ' . number_format($tagihan->sisa_tagihan, 0, ',', '.') . ').'
        ]);

        $this->validateAnak($tagihan->santri_id);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/bukti_pembayaran', $filename);

            Pembayaran::create([
                'tenant_id' => $tagihan->tenant_id,
                'tagihan_id' => $tagihan->id,
                'nominal_dibayar' => $request->nominal,
                'tanggal_bayar' => now()->format('Y-m-d'),
                'metode_pembayaran' => 'Transfer',
                'keterangan' => 'Pembayaran via Portal Wali Santri',
                'status' => 'Menunggu Verifikasi',
                'bukti_pembayaran' => $path
            ]);

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi.');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal mengunggah bukti pembayaran.']);
    }

    public function tabungan($santri_id)
    {
        $santri = $this->validateAnak($santri_id);
        $tabungan = Tabungan::where('santri_id', $santri_id)->first();
        $mutasis = $tabungan ? $tabungan->mutasiTabungans()->latest()->take(20)->get() : collect();

        // Ambil transaksi pos (Kantin) untuk santri ini
        $transaksi_pos = \App\Models\TransaksiPos::where('santri_id', $santri_id)
                            ->with('details.barang')
                            ->latest()
                            ->take(20)
                            ->get();

        return view('portal.tabungan', compact('santri', 'tabungan', 'mutasis', 'transaksi_pos'));
    }
}
