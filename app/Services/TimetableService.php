<?php

namespace App\Services;

use App\Models\PengampuPelajaran;
use App\Models\JamPelajaran;
use App\Models\KetersediaanUstadz;
use App\Models\Jadwal;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class TimetableService
{
    public function generate()
    {
        try {
            DB::beginTransaction();

            // Ambil semester aktif (asumsi ID 1 untuk testing)
            $semester = Semester::first();
            if (!$semester) {
                return ['success' => false, 'message' => 'Tidak ada semester aktif.'];
            }

            // Hapus jadwal lama untuk tenant ini (opsional: atau update)
            Jadwal::where('tenant_id', 1)->where('semester_id', $semester->id)->delete();

            $slots = JamPelajaran::where('is_istirahat', false)->orderBy('hari')->orderBy('jam_ke')->get();
            $pengampus = PengampuPelajaran::all();
            
            $ketersediaan = KetersediaanUstadz::all();
            $availMap = [];
            foreach ($ketersediaan as $k) {
                $availMap[$k->ustadz_id][$k->hari][$k->jam_ke] = true;
            }

            $schedule = []; // $schedule[$kelas_id][$hari][$jam_ke] = true
            $teacherSchedule = []; // $teacherSchedule[$ustadz_id][$hari][$jam_ke] = true

            foreach ($pengampus as $pengampu) {
                $jpAssigned = 0;
                
                // Cari slot kosong untuk setiap JP
                for ($i = 0; $i < $pengampu->beban_jp; $i++) {
                    $assigned = false;
                    
                    foreach ($slots as $slot) {
                        $hari = $slot->hari;
                        $jam = $slot->jam_ke;
                        $ustadz_id = $pengampu->ustadz_id;
                        $kelas_id = $pengampu->kelas_id;

                        // 1. Cek bentrok kelas
                        if (isset($schedule[$kelas_id][$hari][$jam])) {
                            continue;
                        }

                        // 2. Cek bentrok guru
                        if (isset($teacherSchedule[$ustadz_id][$hari][$jam])) {
                            continue;
                        }

                        // 3. Cek ketersediaan guru (jika ada batasan)
                        // Jika guru punya entri di tabel ketersediaan, dia hanya bisa di jadwal tersebut.
                        $hasConstraint = KetersediaanUstadz::where('ustadz_id', $ustadz_id)->exists();
                        if ($hasConstraint) {
                            if (!isset($availMap[$ustadz_id][$hari][$jam])) {
                                continue; // Guru tidak tersedia di jam ini
                            }
                        }

                        // Valid slot found! Assign it.
                        $schedule[$kelas_id][$hari][$jam] = true;
                        $teacherSchedule[$ustadz_id][$hari][$jam] = true;
                        $assigned = true;

                        // Simpan ke database
                        Jadwal::create([
                            'tenant_id' => 1,
                            'semester_id' => $semester->id,
                            'kelas_id' => $kelas_id,
                            'mata_pelajaran_id' => $pengampu->mata_pelajaran_id,
                            'ustadz_id' => $ustadz_id,
                            'hari' => $hari,
                            'jam_mulai' => $slot->jam_mulai,
                            'jam_selesai' => $slot->jam_selesai,
                        ]);

                        break; // Move to next JP
                    }

                    if (!$assigned) {
                        DB::rollBack();
                        
                        // Coba analisa alasan kegagalan untuk pesan error yang lebih detail
                        $namaUstadz = $pengampu->ustadz->nama_lengkap ?? 'Ustadz (ID: '.$pengampu->ustadz_id.')';
                        $namaMapel = $pengampu->mata_pelajaran->nama ?? 'Mapel (ID: '.$pengampu->mata_pelajaran_id.')';
                        $namaKelas = $pengampu->kelas->nama ?? 'Kelas (ID: '.$pengampu->kelas_id.')';
                        
                        $hasConstraint = KetersediaanUstadz::where('ustadz_id', $pengampu->ustadz_id)->exists();
                        if ($hasConstraint) {
                            $slotTersedia = KetersediaanUstadz::where('ustadz_id', $pengampu->ustadz_id)->count();
                            if ($slotTersedia < $pengampu->beban_jp) {
                                return [
                                    'success' => false, 
                                    'message' => "Gagal pada: $namaUstadz ($namaMapel - $namaKelas). Beban mengajar adalah {$pengampu->beban_jp} Jam, namun Anda hanya mengatur $slotTersedia slot pada tabel 'Pengecualian Waktu'. Silakan tambah slot ketersediaan waktu untuk ustadz ini."
                                ];
                            }
                        }

                        return [
                            'success' => false, 
                            'message' => "Gagal mencari waktu kosong untuk: $namaUstadz mengajar $namaMapel di $namaKelas. Kemungkinan terjadi bentrok ekstrim (Ustadz sedang mengajar kelas lain, atau kelas tersebut sudah penuh di waktu ustadz tersedia)."
                        ];
                    }
                }
            }

            DB::commit();
            return ['success' => true, 'message' => 'Jadwal berhasil digenerate.'];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
