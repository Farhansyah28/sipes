<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Shift Kasir - {{ $today }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }
        .receipt {
            width: 58mm;
            padding: 5mm;
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .border-top { border-top: 1px dashed #000; margin-top: 5px; padding-top: 5px; }
        .border-bottom { border-bottom: 1px dashed #000; margin-bottom: 5px; padding-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        td, th { padding: 4px 0; }
        
        .header { margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 14px; }
        .header p { margin: 2px 0; font-size: 12px; }
        
        .info { margin-bottom: 15px; font-size: 12px; }
        
        @media print {
            body { margin: 0; padding: 0; }
            .receipt { width: 100%; padding: 0; }
            @page { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <div class="header text-center">
            <h2>LAPORAN SHIFT HARIAN</h2>
            <p>KANTIN PESANTREN</p>
        </div>

        <div class="border-top border-bottom info">
            <table style="width: 100%">
                <tr>
                    <td class="text-left">Tanggal</td>
                    <td class="text-right">{{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="text-left">Kasir</td>
                    <td class="text-right">{{ $kasir->name ?? 'Kasir Umum' }}</td>
                </tr>
                <tr>
                    <td class="text-left">Jml. Transaksi</td>
                    <td class="text-right">{{ $jumlah_transaksi }} struk</td>
                </tr>
            </table>
        </div>

        <div class="items">
            <table>
                <tr>
                    <td class="text-left">Pembayaran Tunai</td>
                    <td class="text-right">Rp {{ number_format($tunai, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left">Pembayaran Tabungan</td>
                    <td class="text-right">Rp {{ number_format($tabungan, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="border-top">
            <table>
                <tr class="bold">
                    <td class="text-left">TOTAL OMZET SHIFT</td>
                    <td class="text-right">Rp {{ number_format($total_omzet, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer text-center" style="margin-top: 20px;">
            <p style="font-size: 12px;">Diserahkan oleh,</p>
            <br><br><br>
            <p style="font-size: 12px;">( {{ $kasir->name ?? '_______________' }} )</p>
        </div>
    </div>
</body>
</html>
