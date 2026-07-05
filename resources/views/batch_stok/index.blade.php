<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penerimaan Restock / Batch Inventori') }}
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
                        <h3 class="text-lg font-bold">Daftar Batch Stok Masuk (FIFO)</h3>
                        <a href="{{ route('batch_stok.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                            + Catat Restock Baru
                        </a>
                    </div>

                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Tgl Masuk</th>
                                <th class="py-2 px-4 border-b text-left">Barang</th>
                                <th class="py-2 px-4 border-b text-left">Supplier</th>
                                <th class="py-2 px-4 border-b text-right">Harga Beli</th>
                                <th class="py-2 px-4 border-b text-center">Qty Awal</th>
                                <th class="py-2 px-4 border-b text-center">Qty Sisa</th>
                                <th class="py-2 px-4 border-b text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($batches as $b)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($b->tanggal_masuk)->format('d M Y') }}</td>
                                <td class="py-2 px-4 border-b font-semibold text-indigo-700">{{ $b->barang->nama ?? '-' }}</td>
                                <td class="py-2 px-4 border-b">{{ $b->supplier->nama ?? 'Tanpa Supplier' }}</td>
                                <td class="py-2 px-4 border-b text-right">Rp {{ number_format($b->harga_beli_satuan, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $b->qty_awal }}</td>
                                <td class="py-2 px-4 border-b text-center font-bold {{ $b->qty_sisa == 0 ? 'text-red-500' : 'text-green-600' }}">{{ $b->qty_sisa }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if($b->qty_sisa == 0)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-bold">HABIS</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">TERSEDIA</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $batches->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
