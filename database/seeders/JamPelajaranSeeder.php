<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JamPelajaran;
use Carbon\Carbon;

class JamPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        
        foreach ($haris as $hari) {
            $mulai = Carbon::createFromTime(7, 0, 0); // Mulai jam 07:00
            
            for ($i = 1; $i <= 8; $i++) {
                $selesai = (clone $mulai)->addMinutes(45); // 1 JP = 45 Menit
                
                JamPelajaran::create([
                    'tenant_id' => 1,
                    'hari' => $hari,
                    'jam_ke' => $i,
                    'jam_mulai' => $mulai->format('H:i:s'),
                    'jam_selesai' => $selesai->format('H:i:s'),
                    'is_istirahat' => false
                ]);
                
                $mulai = $selesai;
                
                // Tambah jam istirahat
                if ($i == 4) { // Setelah jam ke 4 (10:00)
                    $mulai = (clone $mulai)->addMinutes(30);
                }
            }
        }
    }
}
