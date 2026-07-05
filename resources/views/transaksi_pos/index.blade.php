<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan Kantin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Histori Transaksi Kasir</h3>

                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">No Nota</th>
                                <th class="py-2 px-4 border-b text-left">Tgl Transaksi</th>
                                <th class="py-2 px-4 border-b text-left">Pembeli (Santri)</th>
                                <th class="py-2 px-4 border-b text-center">Metode</th>
                                <th class="py-2 px-4 border-b text-center">Status</th>
                                <th class="py-2 px-4 border-b text-right">Total Nominal</th>
                                <th class="py-2 px-4 border-b text-left">Kasir</th>
                                <th class="py-2 px-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $t)
                            <tr>
                                <td class="py-2 px-4 border-b font-mono text-gray-500">#{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y') }}</td>
                                <td class="py-2 px-4 border-b font-semibold">{{ $t->santri->nama_lengkap ?? 'UMUM' }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if($t->metode_pembayaran == 'Tabungan')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-bold">TABUNGAN</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">CASH</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if($t->status == 'Batal')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold" title="{{ $t->alasan_batal }}">BATAL</span>
                                    @else
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded text-xs font-bold">SUKSES</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-right text-indigo-700 font-bold {{ $t->status == 'Batal' ? 'line-through text-red-500' : '' }}">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border-b text-xs text-gray-500">{{ $t->kasir->name ?? '-' }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if($t->status == 'Sukses')
                                        <form method="POST" action="{{ route('transaksi_pos.void', $t->id) }}" onsubmit="let res = prompt('Masukkan alasan pembatalan transaksi ini:'); if(res) { this.alasan.value = res; return true; } return false;">
                                            @csrf
                                            <input type="hidden" name="alasan" value="">
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs bg-red-50 px-2 py-1 rounded">Batalkan</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Telah Dibatalkan</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $transaksis->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
