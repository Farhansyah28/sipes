<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</title>
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
        td, th { padding: 2px 0; }
        
        .header { margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; }
        .header p { margin: 2px 0; font-size: 12px; }
        
        .info { margin-bottom: 10px; font-size: 12px; }
        .item-name { font-size: 12px; display: block; }
        .item-detail { font-size: 12px; color: #444; }
        
        .footer { margin-top: 15px; font-size: 12px; }
        
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
            <h2>KANTIN PESANTREN</h2>
            <p>Sistem ERP Terpadu</p>
        </div>

        <div class="border-top border-bottom info">
            <table style="width: 100%">
                <tr>
                    <td class="text-left">No</td>
                    <td class="text-right">#{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Tgl</td>
                    <td class="text-right">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="text-left">Kasir</td>
                    <td class="text-right">{{ $transaksi->kasir->name ?? 'Kasir' }}</td>
                </tr>
                <tr>
                    <td class="text-left">Metode</td>
                    <td class="text-right">{{ $transaksi->metode_pembayaran }}</td>
                </tr>
                @if($transaksi->metode_pembayaran === 'Tabungan' && $transaksi->santri)
                <tr>
                    <td class="text-left">Santri</td>
                    <td class="text-right">{{ substr($transaksi->santri->nama_lengkap, 0, 15) }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="items">
            <table>
                @foreach($transaksi->details as $detail)
                <tr>
                    <td colspan="3"><span class="item-name">{{ $detail->barang->nama }}</span></td>
                </tr>
                <tr>
                    <td class="text-left item-detail">{{ $detail->qty }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right item-detail" colspan="2">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="border-top">
            <table>
                <tr class="bold">
                    <td class="text-left">TOTAL</td>
                    <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer text-center">
            <p>Terima Kasih</p>
            <p style="font-size: 10px; margin-top:10px;">-- Dicetak otomatis oleh sistem --</p>
        </div>
    </div>
</body>
</html>
