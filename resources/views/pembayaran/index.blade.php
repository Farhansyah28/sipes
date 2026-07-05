<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penerimaan Kasir / Histori Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Histori Transaksi Masuk</h3>
                        <a href="{{ route('pembayaran.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            + Terima Pembayaran Kasir
                        </a>
                    </div>

                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Tgl Bayar</th>
                                <th class="py-2 px-4 border-b text-left">Santri</th>
                                <th class="py-2 px-4 border-b text-left">Tagihan Untuk</th>
                                <th class="py-2 px-4 border-b text-right">Nominal Masuk</th>
                                <th class="py-2 px-4 border-b text-center">Status</th>
                                <th class="py-2 px-4 border-b text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembayarans as $p)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}</td>
                                <td class="py-2 px-4 border-b font-semibold">{{ $p->tagihan->santri->nama_lengkap ?? '-' }}</td>
                                <td class="py-2 px-4 border-b">{{ $p->tagihan->kategoriTagihan->nama ?? '-' }}</td>
                                <td class="py-2 px-4 border-b text-right text-green-600 font-bold">Rp {{ number_format($p->nominal_dibayar, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="px-2 py-1 mb-1 rounded text-xs font-bold {{ $p->status === 'Menunggu Verifikasi' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $p->status ?? 'Valid' }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $p->metode_pembayaran }}</span>
                                    </div>
                                </td>
                                <td class="py-2 px-4 border-b space-x-2">
                                    @if($p->status === 'Menunggu Verifikasi')
                                        <form action="{{ route('pembayaran.verify', $p->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white text-xs font-bold py-1 px-2 rounded" onclick="return confirm('Verifikasi pembayaran ini? Saldo tagihan santri akan terpotong secara otomatis.')">
                                                âœ“ Verifikasi
                                            </button>
                                        </form>
                                        @if($p->bukti_pembayaran)
                                            <a href="{{ Storage::url($p->bukti_pembayaran) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold underline">Lihat Bukti</a>
                                        @endif
                                    @else
                                        <form action="{{ route('pembayaran.destroy', $p->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-semibold" onclick="return confirm('Membatalkan pembayaran akan mengembalikan sisa tagihan santri. Yakin batalkan?')">Batalkan / Void</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $pembayarans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
