<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportSandbox;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\Tabungan;
use App\Models\MutasiTabungan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportSantriController extends Controller
{
    public function index()
    {
        return view('import.santri.index');
    }

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Template_Import_Santri.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = array('NIS', 'Nama Lengkap', 'Jenis Kelamin (L/P)', 'Tanggal Lahir (YYYY-MM-DD)', 'ID Kelas', 'Saldo Tabungan Awal');

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');
            fputcsv($file, array('1001', 'Ahmad Dahlan', 'L', '2010-05-12', '1', '500000'), ';');
            fputcsv($file, array('1002', 'Siti Aisyah', 'P', '2011-08-20', '2', '0'), ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), "r");
        
        // Auto-detect delimiter (, or ;)
        $delimiter = ',';
        $firstLine = fgets($handle);
        if (strpos($firstLine, ';') !== false) {
            $delimiter = ';';
        }
        rewind($handle);

        $header = true;
        $session_id = Str::uuid()->toString();

        while ($row = fgetcsv($handle, 1000, $delimiter)) {
            if ($header) {
                $header = false;
                continue;
            }

            // Asumsi urutan: NIS(0), Nama(1), L/P(2), Tgl Lahir(3), ID Kelas(4), Saldo(5)
            $nis = $row[0] ?? '';
            $nama = $row[1] ?? '';
            $gender = $row[2] ?? '';
            $tgl_lahir = $row[3] ?? '';
            $kelas_id = $row[4] ?? '';
            
            $saldo = $row[5] ?? '0';
            if (trim($saldo) === '') {
                $saldo = '0';
            }

            $errors = [];
            
            // Validasi NIS Unik
            if (empty($nis)) {
                $errors[] = "NIS kosong.";
            } else {
                if (Santri::where('nis', $nis)->exists()) {
                    $errors[] = "NIS {$nis} sudah terdaftar di database.";
                }
            }

            // Validasi Nama
            if (empty($nama)) {
                $errors[] = "Nama lengkap kosong.";
            }

            // Validasi Kelas
            if (empty($kelas_id)) {
                $errors[] = "ID Kelas kosong.";
            } else {
                if (!Kelas::find($kelas_id)) {
                    $errors[] = "ID Kelas {$kelas_id} tidak ditemukan.";
                }
            }

            // Validasi Jenis Kelamin
            if (empty($gender)) {
                $errors[] = "Jenis kelamin kosong.";
            } elseif (!in_array(strtoupper(trim($gender)), ['L', 'P'])) {
                $errors[] = "Jenis kelamin harus L atau P.";
            }

            // Validasi Tanggal Lahir
            if (!empty($tgl_lahir)) {
                $d = \DateTime::createFromFormat('Y-m-d', trim($tgl_lahir));
                if (!$d || $d->format('Y-m-d') !== trim($tgl_lahir)) {
                    $errors[] = "Format tanggal lahir harus YYYY-MM-DD.";
                }
            }

            // Validasi Saldo
            if (!is_numeric(trim($saldo))) {
                $errors[] = "Saldo harus berupa angka tanpa titik/koma (misal: 500000).";
            }

            $status = count($errors) > 0 ? 'Error' : 'Valid';

            ImportSandbox::create([
                'session_id' => $session_id,
                'model_type' => 'Santri',
                'row_data' => [
                    'nis' => $nis,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => $gender,
                    'tanggal_lahir' => $tgl_lahir,
                    'kelas_id' => $kelas_id,
                    'saldo_awal' => $saldo
                ],
                'status' => $status,
                'error_messages' => implode(' | ', $errors),
            ]);
        }
        
        fclose($handle);

        return redirect()->route('import.santri.preview', ['session_id' => $session_id])
                         ->with('success', 'File CSV berhasil diproses. Silakan periksa hasil validasi di bawah ini.');
    }

    public function preview($session_id)
    {
        $rows = ImportSandbox::where('session_id', $session_id)->get();
        if ($rows->isEmpty()) {
            return redirect()->route('import.santri.index')->withErrors(['error' => 'Sesi import tidak ditemukan atau sudah kadaluarsa.']);
        }

        $total = $rows->count();
        $valid = $rows->where('status', 'Valid')->count();
        $error = $rows->where('status', 'Error')->count();

        return view('import.santri.preview', compact('rows', 'session_id', 'total', 'valid', 'error'));
    }

    public function commit(Request $request, $session_id)
    {
        $action = $request->input('action'); // 'all_valid'
        
        $query = ImportSandbox::where('session_id', $session_id)->where('status', 'Valid');
        $validRows = $query->get();

        if ($validRows->isEmpty()) {
            return back()->withErrors(['error' => 'Tidak ada data valid yang bisa disimpan.']);
        }

        try {
            DB::beginTransaction();

            $count_santri = 0;
            foreach ($validRows as $row) {
                $data = $row->row_data;
                
                // Cek lagi untuk mencegah duplikasi (misal ada NIS sama di dalam 1 file CSV)
                if (Santri::where('nis', $data['nis'])->exists()) {
                    continue; // Skip silently if somehow duplicate
                }

                $santri = Santri::create([
                    'tenant_id' => 1, // default tenant
                    'nis' => $data['nis'],
                    'nama_lengkap' => $data['nama_lengkap'],
                    'jenis_kelamin' => strtoupper($data['jenis_kelamin']) == 'P' ? 'P' : 'L',
                    'tanggal_lahir' => !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null,
                    'kelas_id' => $data['kelas_id'],
                    'status' => 'Aktif',
                ]);

                $saldo_awal = (float) $data['saldo_awal'];
                if ($saldo_awal > 0) {
                    $tabungan = Tabungan::create([
                        'tenant_id' => 1,
                        'santri_id' => $santri->id,
                        'saldo' => $saldo_awal,
                        'limit_harian' => 50000,
                    ]);

                    MutasiTabungan::create([
                        'tabungan_id' => $tabungan->id,
                        'tipe' => 'Setor',
                        'nominal' => $saldo_awal,
                        'tanggal' => date('Y-m-d'),
                        'keterangan' => 'Saldo Awal (Import CSV)',
                    ]);
                }
                
                $count_santri++;
            }

            // Hapus sandbox session
            ImportSandbox::where('session_id', $session_id)->delete();

            DB::commit();
            return redirect()->route('import.santri.index')->with('success', "Berhasil menyimpan {$count_santri} data santri ke database.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
}
