<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\KasPesantren;
use App\Models\MutasiTabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['tagihan.santri', 'tagihan.kategoriTagihan'])->latest()->paginate(15);
        return view('pembayaran.index', compact('pembayarans'));
    }

    public function create(Request $request)
    {
        $tagihan_id = $request->query('tagihan_id');
        $tagihans = Tagihan::with(['santri', 'kategoriTagihan'])
            ->whereIn('status', ['Belum Bayar', 'Sebagian'])
            ->get();
            
        return view('pembayaran.create', compact('tagihans', 'tagihan_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tagihan_id' => 'required|exists:tagihans,id',
            'nominal_dibayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $tagihan = Tagihan::findOrFail($request->tagihan_id);
        
        if ($validated['nominal_dibayar'] > $tagihan->sisa_tagihan) {
            return back()->withErrors(['nominal_dibayar' => 'Nominal bayar melebihi sisa tagihan.'])->withInput();
        }

        // VALIDASI DAN EKSEKUSI POTONG TABUNGAN
        $santri = $tagihan->santri;
        if ($validated['metode_pembayaran'] == 'Potong Tabungan') {
            if (!$santri || !$santri->tabungan || $santri->tabungan->saldo < $validated['nominal_dibayar']) {
                return back()->withErrors(['nominal_dibayar' => 'Saldo Tabungan Santri tidak mencukupi untuk memotong tagihan.'])->withInput();
            }

            // Potong saldo
            $santri->tabungan->saldo -= $validated['nominal_dibayar'];
            $santri->tabungan->save();

            // Catat Mutasi
            MutasiTabungan::create([
                'tabungan_id' => $santri->tabungan->id,
                'tipe' => 'Tarik',
                'nominal' => $validated['nominal_dibayar'],
                'tanggal' => $validated['tanggal_bayar'],
                'keterangan' => 'Pembayaran Tagihan ' . ($tagihan->kategoriTagihan->nama_kategori ?? '')
            ]);
        }

        $validated['status'] = 'Valid';
        $pembayaran = Pembayaran::create($validated);

        // Catat ke Buku Kas Pesantren (HANYA JIKA BUKAN POTONG TABUNGAN)
        if ($validated['metode_pembayaran'] != 'Potong Tabungan') {
            KasPesantren::create([
                'tenant_id' => 1,
                'tanggal' => $validated['tanggal_bayar'],
                'tipe' => 'Masuk',
                'kategori' => 'SPP',
                'nominal' => $validated['nominal_dibayar'],
                'keterangan' => 'Pembayaran Tagihan ' . ($tagihan->kategoriTagihan->nama_kategori ?? '') . ' (Oleh Kasir)',
                'referensi_type' => get_class($pembayaran),
                'referensi_id' => $pembayaran->id,
            ]);
        }

        // Update tagihan status
        $tagihan->sisa_tagihan -= $validated['nominal_dibayar'];
        if ($tagihan->sisa_tagihan <= 0) {
            $tagihan->sisa_tagihan = 0;
            $tagihan->status = 'Lunas';
        } else {
            $tagihan->status = 'Sebagian';
        }
        $tagihan->save();

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        // Revert tagihan state
        $tagihan = $pembayaran->tagihan;
        $tagihan->sisa_tagihan += $pembayaran->nominal_dibayar;
        
        if ($tagihan->sisa_tagihan == $tagihan->nominal) {
            $tagihan->status = 'Belum Bayar';
        } else {
            $tagihan->status = 'Sebagian';
        }
        $tagihan->save();

        if ($pembayaran->metode_pembayaran == 'Potong Tabungan') {
            // Kembalikan saldo tabungan
            $santri = $tagihan->santri;
            if ($santri && $santri->tabungan) {
                $santri->tabungan->saldo += $pembayaran->nominal_dibayar;
                $santri->tabungan->save();
                
                MutasiTabungan::create([
                    'tabungan_id' => $santri->tabungan->id,
                    'tipe' => 'Setor',
                    'nominal' => $pembayaran->nominal_dibayar,
                    'tanggal' => date('Y-m-d'),
                    'keterangan' => 'Refund Pembatalan Tagihan ' . ($tagihan->kategoriTagihan->nama_kategori ?? '')
                ]);
            }
        } else {
            // Hapus riwayat di Buku Kas Pesantren
            KasPesantren::where('referensi_type', get_class($pembayaran))->where('referensi_id', $pembayaran->id)->delete();
        }

        $pembayaran->delete();
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dibatalkan dan Tagihan serta Saldo Kas telah disesuaikan.');
    }

    public function verify(Request $request, Pembayaran $pembayaran)
    {
        if ($pembayaran->status !== 'Menunggu Verifikasi') {
            return back()->withErrors(['error' => 'Pembayaran ini tidak dalam status Menunggu Verifikasi.']);
        }

        try {
            DB::beginTransaction();

            $tagihan = $pembayaran->tagihan;

            // 1. Ubah status pembayaran
            $pembayaran->status = 'Valid';
            $pembayaran->save();

            // Catat ke Buku Kas Pesantren
            KasPesantren::create([
                'tenant_id' => 1,
                'tanggal' => $pembayaran->tanggal_bayar,
                'tipe' => 'Masuk',
                'kategori' => 'SPP',
                'nominal' => $pembayaran->nominal_dibayar,
                'keterangan' => 'Verifikasi Pembayaran via Portal: ' . $tagihan->kategoriTagihan->nama_kategori,
                'referensi_type' => get_class($pembayaran),
                'referensi_id' => $pembayaran->id,
            ]);

            // 2. Kurangi sisa tagihan
            $tagihan->sisa_tagihan -= $pembayaran->nominal_dibayar;
            
            if ($tagihan->sisa_tagihan <= 0) {
                $tagihan->sisa_tagihan = 0;
                $tagihan->status = 'Lunas';
            } else {
                $tagihan->status = 'Sebagian';
            }
            $tagihan->save();

            DB::commit();
            return redirect()->route('pembayaran.index')->with('success', 'Pembayaran via Portal berhasil diverifikasi! Saldo Tagihan telah disesuaikan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memverifikasi: ' . $e->getMessage()]);
        }
    }
}
