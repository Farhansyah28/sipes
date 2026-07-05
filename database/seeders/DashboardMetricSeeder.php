<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\TransaksiPos;
use App\Models\DetailTransaksiPos;
use App\Models\Barang;
use App\Models\ActivityLog;
use App\Models\Jadwal;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DashboardMetricSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $tenantId = 1;
        $today = now()->format('Y-m-d');
        
        $santris = Santri::where('tenant_id', $tenantId)->where('status', 'Aktif')->get();
        if ($santris->isEmpty()) return;

        // 1. Create Dummy Jadwal if not exist
        $jadwal = Jadwal::firstOrCreate([
            'tenant_id' => $tenantId,
            'semester_id' => \App\Models\Semester::where('is_active', true)->first()->id ?? 1,
            'kelas_id' => $santris->first()->kelas_id,
            'mata_pelajaran_id' => \App\Models\MataPelajaran::first()->id ?? 1,
            'ustadz_id' => \App\Models\Ustadz::first()->id ?? 1,
            'hari' => 'Senin',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '08:30:00'
        ]);

        // 2. Seed Absensi Hari Ini
        foreach ($santris as $santri) {
            $rand = rand(1, 100);
            if ($rand <= 90) {
                $status = 'Hadir';
            } elseif ($rand <= 95) {
                $status = 'Izin';
            } elseif ($rand <= 98) {
                $status = 'Sakit';
            } else {
                $status = 'Alpha';
            }

            Absensi::create([
                'tenant_id' => $tenantId,
                'santri_id' => $santri->id,
                'jadwal_id' => $jadwal->id,
                'tanggal' => $today,
                'status' => $status,
                'keterangan' => $status != 'Hadir' ? 'Diisi oleh sistem otomatis' : null
            ]);
        }

        // 3. Seed Transaksi POS Kantin Hari Ini
        $kasir = User::role('Kasir Kantin')->first() ?? User::first();
        $barangs = Barang::where('tenant_id', $tenantId)->get();
        
        if ($barangs->isNotEmpty()) {
            $numTransaksi = rand(50, 100);
            for ($i = 0; $i < $numTransaksi; $i++) {
                $santriPos = rand(0, 1) ? $santris->random()->id : null;
                $transaksi = TransaksiPos::create([
                    'tenant_id' => $tenantId,
                    'santri_id' => $santriPos,
                    'kasir_id' => $kasir->id,
                    'tanggal_transaksi' => $today,
                    'metode_pembayaran' => $santriPos ? 'Tabungan' : 'Tunai',
                    'total_harga' => 0 // dihitung nanti
                ]);

                $totalHarga = 0;
                $numItems = rand(1, 3);
                for ($j = 0; $j < $numItems; $j++) {
                    $item = $barangs->random();
                    $qty = rand(1, 3);
                    $subtotal = $item->harga_jual * $qty;
                    
                    DetailTransaksiPos::create([
                        'tenant_id' => $tenantId,
                        'transaksi_pos_id' => $transaksi->id,
                        'barang_id' => $item->id,
                        'qty' => $qty,
                        'harga_satuan' => $item->harga_jual,
                        'subtotal' => $subtotal
                    ]);
                    $totalHarga += $subtotal;
                }
                
                $transaksi->total_harga = $totalHarga;
                $transaksi->save();
            }
        }

        // 4. Seed Activity Log untuk Traffic Portal
        $numLogins = rand(20, 150);
        for ($i = 0; $i < $numLogins; $i++) {
            ActivityLog::create([
                'log_name' => 'portal_login',
                'description' => 'Orang tua berhasil login ke portal',
                'subject_type' => 'App\Models\OrangTua',
                'subject_id' => rand(1, 20),
                'causer_type' => 'App\Models\OrangTua',
                'causer_id' => rand(1, 20),
                'properties' => '{"ip": "192.168.1.'.rand(1,255).'"}',
                'tenant_id' => $tenantId,
                'created_at' => now()->subMinutes(rand(1, 1400)),
                'updated_at' => now()
            ]);
        }
    }
}
