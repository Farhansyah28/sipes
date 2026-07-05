<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPos;
use App\Models\DetailTransaksiPos;
use App\Models\Barang;
use App\Models\BatchStok;
use App\Models\Santri;
use App\Models\MutasiTabungan;
use App\Models\KasPesantren;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiPosController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiPos::with(['kasir', 'santri'])->latest()->paginate(15);
        return view('transaksi_pos.index', compact('transaksis'));
    }

    public function create()
    {
        // Load all items that have stock > 0
        $barangs = Barang::where('stok_total', '>', 0)->get();
        $santris = Santri::where('status', 'Aktif')->has('tabungan')->with('tabungan')->get();
        return view('transaksi_pos.create', compact('barangs', 'santris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'nullable|exists:santris,id',
            'metode_pembayaran' => 'required|in:Tunai,Tabungan',
            'cart' => 'required|json'
        ]);

        $cart = json_decode($validated['cart'], true);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Keranjang belanja kosong.']);
        }

        try {
            DB::beginTransaction();

            $total_harga = 0;
            foreach ($cart as $item) {
                $total_harga += $item['subtotal'];
            }

            // Validasi potong tabungan
            $santri = null;
            if ($validated['metode_pembayaran'] === 'Tabungan') {
                if (!$validated['santri_id']) {
                    throw new \Exception("Santri harus dipilih jika menggunakan metode potong tabungan.");
                }
                $santri = Santri::with('tabungan')->find($validated['santri_id']);
                if (!$santri->tabungan || $santri->tabungan->saldo < $total_harga) {
                    throw new \Exception("Saldo tabungan tidak mencukupi. Sisa saldo: Rp " . number_format($santri->tabungan->saldo ?? 0, 0, ',', '.'));
                }
            }

            // Create Transaksi
            $transaksi = TransaksiPos::create([
                'santri_id' => $validated['santri_id'],
                'kasir_id' => Auth::id() ?? 1,
                'tanggal_transaksi' => date('Y-m-d'),
                'total_harga' => $total_harga,
                'metode_pembayaran' => $validated['metode_pembayaran'],
            ]);

            // Process cart & FIFO
            foreach ($cart as $item) {
                $barang = Barang::findOrFail($item['id']);
                $qty_dibeli = $item['qty'];

                if ($barang->stok_total < $qty_dibeli) {
                    throw new \Exception("Stok {$barang->nama} tidak mencukupi. Sisa stok: {$barang->stok_total}");
                }

                // FIFO Algorithm
                $batches = BatchStok::where('barang_id', $barang->id)
                            ->where('qty_sisa', '>', 0)
                            ->orderBy('tanggal_masuk', 'asc')
                            ->orderBy('id', 'asc')
                            ->lockForUpdate()
                            ->get();

                $sisa_kebutuhan = $qty_dibeli;
                $total_hpp = 0;

                foreach ($batches as $batch) {
                    if ($sisa_kebutuhan <= 0) break;

                    if ($batch->qty_sisa >= $sisa_kebutuhan) {
                        $total_hpp += $sisa_kebutuhan * $batch->harga_beli_satuan;
                        $batch->qty_sisa -= $sisa_kebutuhan;
                        $batch->save();
                        $sisa_kebutuhan = 0;
                    } else {
                        $total_hpp += $batch->qty_sisa * $batch->harga_beli_satuan;
                        $sisa_kebutuhan -= $batch->qty_sisa;
                        $batch->qty_sisa = 0;
                        $batch->save();
                    }
                }

                DetailTransaksiPos::create([
                    'transaksi_pos_id' => $transaksi->id,
                    'barang_id' => $barang->id,
                    'qty' => $qty_dibeli,
                    'harga_satuan' => $item['harga_jual'],
                    'harga_beli_pokok' => $total_hpp,
                    'subtotal' => $item['subtotal'],
                ]);

                // Update Total Stok Barang
                $barang->stok_total -= $qty_dibeli;
                $barang->save();
            }

            // Potong Tabungan jika diperlukan
            if ($validated['metode_pembayaran'] === 'Tabungan') {
                $santri->tabungan->saldo -= $total_harga;
                $santri->tabungan->save();

                MutasiTabungan::create([
                    'tabungan_id' => $santri->tabungan->id,
                    'tipe' => 'Tarik',
                    'nominal' => $total_harga,
                    'tanggal' => date('Y-m-d'),
                    'keterangan' => "Jajan Kantin (Nota #" . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) . ")"
                ]);
            }

            if ($validated['metode_pembayaran'] === 'Tunai') {
                // Catat ke Buku Kas Pesantren HANYA JIKA UANG FISIK (TUNAI) DITERIMA
                KasPesantren::create([
                    'tenant_id' => 1,
                    'tanggal' => $transaksi->tanggal_transaksi,
                    'tipe' => 'Masuk',
                    'kategori' => 'Kantin POS',
                    'nominal' => $total_harga,
                    'keterangan' => "Penjualan Kantin #" . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) . " (Tunai)",
                    'referensi_type' => get_class($transaksi),
                    'referensi_id' => $transaksi->id,
                ]);
            }

            DB::commit();
            return redirect()->route('transaksi_pos.create')->with('success', 'Transaksi berhasil disimpan!')->with('print_id', $transaksi->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function print($id)
    {
        $transaksi = TransaksiPos::with(['details.barang', 'kasir', 'santri'])->findOrFail($id);
        return view('transaksi_pos.print', compact('transaksi'));
    }

    public function voidTransaksi(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $transaksi = TransaksiPos::with(['details.barang', 'santri.tabungan'])->findOrFail($id);

            if ($transaksi->status === 'Batal') {
                throw new \Exception("Transaksi ini sudah dibatalkan sebelumnya.");
            }

            // Update status transaksi
            $transaksi->status = 'Batal';
            $transaksi->alasan_batal = $request->alasan;
            $transaksi->save();

            // 1. Pengembalian Stok (Stock Reversal)
            foreach ($transaksi->details as $detail) {
                $barang = $detail->barang;
                
                // Kembalikan ke stok_total Master Barang
                $barang->stok_total += $detail->qty;
                $barang->save();

                // Buat BatchStok baru sebagai Refund (HPP rata-rata)
                $hpp_satuan = $detail->qty > 0 ? floor($detail->harga_beli_pokok / $detail->qty) : 0;
                
                BatchStok::create([
                    'barang_id' => $barang->id,
                    'qty_awal' => $detail->qty,
                    'qty_sisa' => $detail->qty,
                    'harga_beli_satuan' => $hpp_satuan,
                    'tanggal_masuk' => date('Y-m-d'),
                    'tanggal_kadaluarsa' => null, // Tidak diketahui, biarkan null
                ]);
            }

            // 2. Refund Saldo Tabungan (Jika Metode Tabungan)
            if ($transaksi->metode_pembayaran === 'Tabungan') {
                $santri = $transaksi->santri;
                if ($santri && $santri->tabungan) {
                    $santri->tabungan->saldo += $transaksi->total_harga;
                    $santri->tabungan->save();

                    MutasiTabungan::create([
                        'tabungan_id' => $santri->tabungan->id,
                        'tipe' => 'Setor',
                        'nominal' => $transaksi->total_harga,
                        'tanggal' => date('Y-m-d'),
                        'keterangan' => "Refund Pembatalan Transaksi Kantin #" . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT)
                    ]);
                }
            } // INI YANG KURANG

            if ($transaksi->metode_pembayaran === 'Tunai') {
                // Hapus atau Reversal Kas Pesantren HANYA JIKA SEBELUMNYA TUNAI
                KasPesantren::where('referensi_type', get_class($transaksi))->where('referensi_id', $transaksi->id)->delete();
            }

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dibatalkan dan uang/stok telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi: ' . $e->getMessage()]);
        }
    }
}
