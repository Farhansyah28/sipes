<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\Santri;
use App\Models\Ustadz;
use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use App\Models\Semester;

echo "=== MEMULAI TESTING MODUL AKADEMIK ===\n";

try {
    // 1. Tahun Ajaran & Semester
    $tahunAjaran = TahunAjaran::firstOrCreate(['nama' => '2025/2026'], ['tenant_id' => 1, 'tanggal_mulai' => date('Y-m-d'), 'tanggal_selesai' => date('Y-m-d', strtotime('+1 year'))]);
    $semester = Semester::firstOrCreate(['tahun_ajaran_id' => $tahunAjaran->id, 'nama' => 'Ganjil'], ['tenant_id' => 1, 'status' => 'Aktif']);
    echo "[OK] Master Tahun Ajaran & Semester\n";

    // 2. Mata Pelajaran
    $mapel1 = MataPelajaran::firstOrCreate(['kode' => 'PAI-01'], ['tenant_id' => 1, 'nama' => 'Pendidikan Agama Islam']);
    $mapel2 = MataPelajaran::firstOrCreate(['kode' => 'MAT-01'], ['tenant_id' => 1, 'nama' => 'Matematika Terapan']);
    echo "[OK] Master Mata Pelajaran\n";

    // 3. Kelas & Wali Kelas
    $ustadz = Ustadz::first(); // Asumsikan sudah ada dari modul kepegawaian/sebelumnya
    if (!$ustadz) {
        throw new \Exception("Data Ustadz kosong, harap isi modul Ustadz terlebih dahulu.");
    }
    
    $kelas = Kelas::firstOrCreate(['nama' => '10 IPA 1'], [
        'tenant_id' => 1, 
        'tingkat' => '10', 
        'wali_kelas_id' => $ustadz->id
    ]);
    echo "[OK] Master Kelas & Wali Kelas (Wali: " . $ustadz->nama . ")\n";

    // 4. Assign Santri ke Kelas
    $santris = Santri::where('status', 'Aktif')->limit(3)->get();
    if ($santris->count() == 0) {
        throw new \Exception("Tidak ada santri aktif untuk ditest.");
    }
    
    foreach ($santris as $santri) {
        $santri->kelas_id = $kelas->id;
        $santri->save();
    }
    echo "[OK] Asignasi Santri ke Kelas " . $kelas->nama . " (" . $santris->count() . " santri)\n";

    // 5. Jadwal Pelajaran
    $jadwal1 = Jadwal::firstOrCreate([
        'kelas_id' => $kelas->id,
        'mata_pelajaran_id' => $mapel1->id,
        'semester_id' => $semester->id
    ], [
        'tenant_id' => 1,
        'ustadz_id' => $ustadz->id,
        'hari' => 'Senin',
        'jam_mulai' => '07:00',
        'jam_selesai' => '08:30'
    ]);
    
    $jadwal2 = Jadwal::firstOrCreate([
        'kelas_id' => $kelas->id,
        'mata_pelajaran_id' => $mapel2->id,
        'semester_id' => $semester->id
    ], [
        'tenant_id' => 1,
        'ustadz_id' => $ustadz->id,
        'hari' => 'Selasa',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30'
    ]);
    echo "[OK] Pembuatan Jadwal Pelajaran\n";

    // 6. Absensi
    echo "\n--- SIMULASI ABSENSI ---\n";
    foreach ($santris as $index => $santri) {
        // Santri 1 hadir, Santri 2 Izin, Santri 3 Sakit
        $status = ['Hadir', 'Izin', 'Sakit'][$index % 3];
        $absensi = Absensi::updateOrCreate([
            'santri_id' => $santri->id,
            'jadwal_id' => $jadwal1->id,
            'tanggal' => date('Y-m-d')
        ], [
            'tenant_id' => 1,
            'status' => $status,
            'keterangan' => 'Simulasi Tes Otomatis'
        ]);
        echo "Santri: " . $santri->nama_lengkap . " | Mapel: " . $mapel1->nama . " | Status: " . $status . "\n";
    }

    // 7. Nilai Ujian
    echo "\n--- SIMULASI PENILAIAN (UJIAN AKHIR SEMESTER) ---\n";
    foreach ($santris as $santri) {
        $nilai = rand(75, 98); // Random score
        $nilaiModel = Nilai::updateOrCreate([
            'santri_id' => $santri->id,
            'jadwal_id' => $jadwal1->id,
            'jenis_nilai' => 'UAS'
        ], [
            'tenant_id' => 1,
            'nilai' => $nilai,
            'catatan' => 'Pertahankan prestasimu'
        ]);
        echo "Santri: " . $santri->nama_lengkap . " | Mapel: " . $mapel1->nama . " | UAS: " . $nilai . "\n";
    }

    echo "\n[SUKSES] Seluruh Modul Akademik Bekerja Tanpa Error.\n";

} catch (\Exception $e) {
    echo "\n[ERROR] " . $e->getMessage() . "\n";
}
