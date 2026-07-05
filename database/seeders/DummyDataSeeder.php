<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Gedung;
use App\Models\Kamar;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\Ustadz;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\OrangTua;
use App\Models\Santri;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $tenantId = 1;

        for ($i = 1; $i <= 3; $i++) {
            $gedung = Gedung::create([
                'tenant_id' => $tenantId,
                'nama' => 'Gedung Asrama ' . $faker->colorName(),
            ]);

            for ($j = 1; $j <= 4; $j++) {
                Kamar::create([
                    'tenant_id' => $tenantId,
                    'gedung_id' => $gedung->id,
                    'nama' => 'Kamar ' . $gedung->id . '0' . $j,
                    'kapasitas' => rand(4, 10),
                ]);
            }
        }

        $ta = TahunAjaran::create(['tenant_id' => $tenantId, 'nama' => '2025/2026', 'is_active' => true]);
        Semester::create(['tenant_id' => $tenantId, 'tahun_ajaran_id' => $ta->id, 'nama' => 'Ganjil', 'is_active' => true]);
        Semester::create(['tenant_id' => $tenantId, 'tahun_ajaran_id' => $ta->id, 'nama' => 'Genap', 'is_active' => false]);

        $ustadzIds = [];
        for ($i = 0; $i < 10; $i++) {
            $u = Ustadz::create([
                'tenant_id' => $tenantId,
                'nip' => '101' . $faker->unique()->numerify('#####'),
                'nama_lengkap' => 'Ust. ' . $faker->name('male'),
                'jenis_kelamin' => 'L',
                'no_hp' => $faker->phoneNumber(),
            ]);
            $ustadzIds[] = $u->id;
        }

        $kelasIds = [];
        $tingkats = ['VII', 'VIII', 'IX'];
        foreach ($tingkats as $t) {
            foreach (['A', 'B'] as $k) {
                $kls = Kelas::create([
                    'tenant_id' => $tenantId,
                    'nama' => $t . ' ' . $k,
                    'tingkat' => $t,
                    'wali_kelas_id' => $faker->randomElement($ustadzIds),
                ]);
                $kelasIds[] = $kls->id;
            }
        }

        $mapels = ['Aqidah', 'Fiqih', 'Bahasa Arab', 'Matematika', 'B. Inggris'];
        foreach ($mapels as $m) {
            MataPelajaran::create([
                'tenant_id' => $tenantId,
                'kode' => strtoupper(substr($m, 0, 3)),
                'nama' => $m,
            ]);
        }

        for ($i = 0; $i < 20; $i++) {
            $ot = OrangTua::create([
                'tenant_id' => $tenantId,
                'nama_ayah' => $faker->name('male'),
                'nama_ibu' => $faker->name('female'),
                'no_hp_ayah' => $faker->phoneNumber(),
                'alamat' => $faker->address(),
            ]);

            $numAnak = rand(1, 2);
            for ($k = 0; $k < $numAnak; $k++) {
                $jk = $faker->randomElement(['L', 'P']);
                Santri::create([
                    'tenant_id' => $tenantId,
                    'orang_tua_id' => $ot->id,
                    'kelas_id' => $faker->randomElement($kelasIds),
                    // random from all kamar directly avoiding pluck empty early
                    'kamar_id' => Kamar::inRandomOrder()->first()->id,
                    'nis' => '25' . $faker->unique()->numerify('#####'),
                    'nama_lengkap' => $faker->firstName($jk == 'L' ? 'male' : 'female') . ' ' . $ot->nama_ayah,
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $faker->date('Y-m-d', '-12 years'),
                    'status' => 'Aktif',
                ]);
            }
        }
    }
}
