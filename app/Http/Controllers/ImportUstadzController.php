<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportSandbox;
use App\Models\Ustadz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportUstadzController extends Controller
{
    public function index()
    {
        return view('import.ustadz.index');
    }

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Template_Import_Ustadz.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = array('NIP', 'Nama Lengkap', 'Jenis Kelamin (L/P)', 'No HP');

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');
            fputcsv($file, array('U-001', 'Ustadz Budi Santoso', 'L', '081234567890'), ';');
            fputcsv($file, array('U-002', 'Ustadzah Aminah', 'P', '089876543210'), ';');
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

            // Asumsi urutan: NIP(0), Nama(1), L/P(2), No HP(3)
            $nip = $row[0] ?? '';
            $nama = $row[1] ?? '';
            $gender = $row[2] ?? '';
            $no_hp = $row[3] ?? '';
            
            // Clean Excel escape characters for strings (e.g., '0812 or ="0812")
            $no_hp = trim(str_replace(['="', '"'], '', $no_hp));
            $no_hp = ltrim($no_hp, "'");

            $errors = [];
            
            // Validasi NIP Unik
            if (empty($nip)) {
                $errors[] = "NIP kosong.";
            } else {
                if (Ustadz::where('nip', $nip)->exists()) {
                    $errors[] = "NIP {$nip} sudah terdaftar di database.";
                }
            }

            // Validasi Nama
            if (empty($nama)) {
                $errors[] = "Nama lengkap kosong.";
            }

            // Validasi Jenis Kelamin
            if (empty($gender)) {
                $errors[] = "Jenis kelamin kosong.";
            } elseif (!in_array(strtoupper(trim($gender)), ['L', 'P'])) {
                $errors[] = "Jenis kelamin harus L atau P.";
            }

            $status = count($errors) > 0 ? 'Error' : 'Valid';

            ImportSandbox::create([
                'session_id' => $session_id,
                'model_type' => 'Ustadz',
                'row_data' => [
                    'nip' => $nip,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => $gender,
                    'no_hp' => $no_hp
                ],
                'status' => $status,
                'error_messages' => implode(' | ', $errors),
            ]);
        }
        
        fclose($handle);

        return redirect()->route('import.ustadz.preview', ['session_id' => $session_id])
                         ->with('success', 'File CSV berhasil diproses. Silakan periksa hasil validasi di bawah ini.');
    }

    public function preview($session_id)
    {
        $rows = ImportSandbox::where('session_id', $session_id)->where('model_type', 'Ustadz')->get();
        if ($rows->isEmpty()) {
            return redirect()->route('import.ustadz.index')->withErrors(['error' => 'Sesi import tidak ditemukan atau sudah kadaluarsa.']);
        }

        $total = $rows->count();
        $valid = $rows->where('status', 'Valid')->count();
        $error = $rows->where('status', 'Error')->count();

        return view('import.ustadz.preview', compact('rows', 'session_id', 'total', 'valid', 'error'));
    }

    public function commit(Request $request, $session_id)
    {
        $query = ImportSandbox::where('session_id', $session_id)->where('model_type', 'Ustadz')->where('status', 'Valid');
        $validRows = $query->get();

        if ($validRows->isEmpty()) {
            return back()->withErrors(['error' => 'Tidak ada data valid yang bisa disimpan.']);
        }

        try {
            DB::beginTransaction();

            $count_ustadz = 0;
            foreach ($validRows as $row) {
                $data = $row->row_data;
                
                // Cek lagi untuk mencegah duplikasi (misal ada NIP sama di dalam 1 file CSV)
                if (Ustadz::where('nip', $data['nip'])->exists()) {
                    continue; // Skip silently if somehow duplicate
                }

                Ustadz::create([
                    'tenant_id' => 1, // default tenant
                    'nip' => $data['nip'],
                    'nama_lengkap' => $data['nama_lengkap'],
                    'jenis_kelamin' => strtoupper(trim($data['jenis_kelamin'])) == 'P' ? 'P' : 'L',
                    'no_hp' => !empty($data['no_hp']) ? $data['no_hp'] : null,
                ]);
                
                $count_ustadz++;
            }

            // Hapus sandbox session
            ImportSandbox::where('session_id', $session_id)->delete();

            DB::commit();
            return redirect()->route('import.ustadz.index')->with('success', "Berhasil menyimpan {$count_ustadz} data ustadz ke database.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
}
