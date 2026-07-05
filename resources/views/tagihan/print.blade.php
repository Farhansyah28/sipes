<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Tagihan #{{ $tagihan->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 14px; background: white; }
            .print-border { border: 1px solid #000; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 p-8 font-sans">
    <div class="max-w-3xl mx-auto bg-white p-10 shadow-lg print:shadow-none print:p-0 print:border-none border border-gray-200">
        
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-indigo-600 pb-6 mb-8">
            <div>
                <h1 class="text-4xl font-black text-indigo-800 tracking-tighter uppercase">INVOICE TAGIHAN</h1>
                <p class="text-sm text-gray-500 font-bold mt-1">Sistem Informasi Pesantren (SIPES)</p>
            </div>
            <div class="text-right">
                <button onclick="window.print()" class="no-print bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow mb-2 text-sm">🖨️ Cetak PDF</button>
                <p class="text-sm font-semibold text-gray-600">Nomor Invoice: <span class="text-gray-900 font-bold">INV-{{ str_pad($tagihan->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                <p class="text-sm font-semibold text-gray-600">Tanggal Terbit: <span class="text-gray-900 font-bold">{{ $tagihan->created_at->format('d M Y') }}</span></p>
            </div>
        </div>

        <!-- Info Penerima & Penagih -->
        <div class="flex justify-between mb-10">
            <div class="w-1/2 pr-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 border-b pb-1">Ditagihkan Kepada:</h3>
                <h2 class="text-lg font-black text-gray-800">{{ $tagihan->santri->nama_lengkap }}</h2>
                <p class="text-sm text-gray-600 font-medium">NIS/NISN: {{ $tagihan->santri->nis ?? '-' }}</p>
                <p class="text-sm text-gray-600 font-medium">Kelas: {{ $tagihan->santri->kelas->nama ?? 'Belum ada kelas' }}</p>
            </div>
            <div class="w-1/2 pl-4 text-right">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 border-b pb-1">Jatuh Tempo:</h3>
                @if($tagihan->jatuh_tempo)
                    <p class="text-lg font-black text-red-600">{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}</p>
                @else
                    <p class="text-lg font-black text-gray-800">-</p>
                @endif
                <p class="text-sm text-gray-600 font-medium mt-2">Status Pembayaran:</p>
                <p class="text-lg font-black {{ $tagihan->status == 'Lunas' ? 'text-green-600' : 'text-red-600' }} uppercase">{{ $tagihan->status }}</p>
            </div>
        </div>

        <!-- Detail Tagihan -->
        <table class="w-full mb-8 border-collapse">
            <thead>
                <tr class="bg-gray-100 border-y border-gray-300">
                    <th class="py-3 px-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Keterangan / Jenis Tagihan</th>
                    <th class="py-3 px-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-200">
                    <td class="py-4 px-4">
                        <p class="font-bold text-gray-800 text-base">{{ $tagihan->kategoriTagihan->nama }}</p>
                        <p class="text-sm text-gray-500">{{ $tagihan->keterangan ?? 'Bulan: ' . ($tagihan->bulan_tagihan ?? '-') }}</p>
                    </td>
                    <td class="py-4 px-4 text-right font-bold text-gray-800 text-lg">
                        {{ number_format($tagihan->nominal, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="py-3 px-4 text-right text-sm font-bold text-gray-600">Total Dibayar:</td>
                    <td class="py-3 px-4 text-right font-bold text-green-600 text-lg">
                        - {{ number_format($tagihan->nominal - $tagihan->sisa_tagihan, 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="bg-gray-50 border-t border-gray-300">
                    <td class="py-4 px-4 text-right text-base font-black text-gray-800 uppercase tracking-widest">Sisa Tagihan / Kurang:</td>
                    <td class="py-4 px-4 text-right font-black text-red-600 text-2xl">
                        Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer / Tanda Tangan -->
        <div class="mt-16 flex justify-between">
            <div class="w-1/2 text-sm text-gray-500">
                <p class="font-bold mb-1 text-gray-700">Catatan Tambahan:</p>
                <p>Harap melakukan pembayaran sebelum tanggal jatuh tempo. Abaikan tagihan ini jika Anda sudah melunasinya.</p>
            </div>
            <div class="w-1/3 text-center">
                <p class="text-sm text-gray-600 mb-16">Bagian Administrasi Keuangan</p>
                <p class="font-bold text-gray-800 border-t border-gray-400 pt-1 w-full mx-auto">( .................................. )</p>
            </div>
        </div>

    </div>
</body>
</html>
