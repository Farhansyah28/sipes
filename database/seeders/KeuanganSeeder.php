<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\KategoriTagihan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Tabungan;
use App\Models\MutasiTabungan;
use App\Models\Santri;
use Faker\Factory as Faker;

class KeuanganSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $tenantId = 1;

        // 1. Kategori Tagihan
        $katSpp = KategoriTagihan::create(['tenant_id' => $tenantId, 'nama' => 'SPP Bulanan', 'tipe_siklus' => 'Bulanan', 'nominal_default' => 500000]);
        $katPangkal = KategoriTagihan::create(['tenant_id' => $tenantId, 'nama' => 'Uang Pangkal', 'tipe_siklus' => 'Sekali Bayar', 'nominal_default' => 2000000]);
        $katSeragam = KategoriTagihan::create(['tenant_id' => $tenantId, 'nama' => 'Uang Seragam', 'tipe_siklus' => 'Sekali Bayar', 'nominal_default' => 800000]);

        $kategoris = [$katSpp, $katPangkal, $katSeragam];
        $santris = Santri::where('tenant_id', $tenantId)->get();

        if ($santris->isEmpty()) return;

        // 2. Tagihan & Pembayaran
        foreach ($santris->random(10) as $santri) {
            $kat = $faker->randomElement($kategoris);
            
            $tagihan = Tagihan::create([
                'tenant_id' => $tenantId,
                'santri_id' => $santri->id,
                'kategori_tagihan_id' => $kat->id,
                'nominal' => $kat->nominal_default,
                'sisa_tagihan' => $kat->nominal_default,
                'status' => 'Belum Bayar',
                'jatuh_tempo' => date('Y-m-d', strtotime('+7 days')),
                'bulan_tagihan' => $kat->tipe_siklus == 'Bulanan' ? date('Y-m') : null,
                'keterangan' => 'Tagihan otomatis by Seeder'
            ]);

            // Simulasi Pembayaran
            if (rand(0, 1)) {
                $dibayar = rand(100000, $kat->nominal_default);
                $statusPembayaran = rand(1, 100) <= 80 ? 'Valid' : 'Menunggu Verifikasi';
                Pembayaran::create([
                    'tenant_id' => $tenantId,
                    'tagihan_id' => $tagihan->id,
                    'nominal_dibayar' => $dibayar,
                    'tanggal_bayar' => date('Y-m-d'),
                    'metode_pembayaran' => 'Tunai',
                    'keterangan' => 'Pembayaran via Kasir',
                    'status' => $statusPembayaran
                ]);

                $tagihan->sisa_tagihan -= $dibayar;
                $tagihan->status = $tagihan->sisa_tagihan <= 0 ? 'Lunas' : 'Sebagian';
                $tagihan->save();
            }
        }

        // 3. Tabungan & Mutasi
        foreach ($santris->random(15) as $santri) {
            $tabungan = Tabungan::create([
                'tenant_id' => $tenantId,
                'santri_id' => $santri->id,
                'saldo' => 0
            ]);

            // Setor awal
            $setorAwal = rand(10, 50) * 10000; // 100k - 500k
            MutasiTabungan::create([
                'tenant_id' => $tenantId,
                'tabungan_id' => $tabungan->id,
                'tipe' => 'Setor',
                'nominal' => $setorAwal,
                'tanggal' => date('Y-m-d', strtotime('-1 month')),
                'keterangan' => 'Setoran Awal Buka Rekening'
            ]);
            $tabungan->saldo += $setorAwal;

            // Mutasi acak (Tarik jajan)
            $tarik = rand(1, 5) * 10000;
            MutasiTabungan::create([
                'tenant_id' => $tenantId,
                'tabungan_id' => $tabungan->id,
                'tipe' => 'Tarik',
                'nominal' => $tarik,
                'tanggal' => date('Y-m-d', strtotime('-1 week')),
                'keterangan' => 'Uang Jajan Kantin'
            ]);
            $tabungan->saldo -= $tarik;

            $tabungan->save();
        }
    }
}
