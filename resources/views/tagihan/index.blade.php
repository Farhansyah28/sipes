<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Tagihan Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Tagihan</h3>
                        <div>
                            <a href="{{ route('tagihan.bulk_create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                                + Generate Massal (SPP)
                            </a>
                            <a href="{{ route('tagihan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Buat Tagihan Satuan
                            </a>
                        </div>
                    </div>

                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Tgl/Jatuh Tempo</th>
                                <th class="py-2 px-4 border-b text-left">Santri</th>
                                <th class="py-2 px-4 border-b text-left">Kategori</th>
                                <th class="py-2 px-4 border-b text-right">Nominal</th>
                                <th class="py-2 px-4 border-b text-right">Sisa Tagihan</th>
                                <th class="py-2 px-4 border-b text-center">Status</th>
                                <th class="py-2 px-4 border-b text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihans as $t)
                            <tr>
                                <td class="py-2 px-4 border-b text-xs text-gray-500">
                                    Bln: {{ $t->bulan_tagihan ?? '-' }}<br>
                                    JT: {{ $t->jatuh_tempo ? \Carbon\Carbon::parse($t->jatuh_tempo)->format('d M Y') : '-' }}
                                </td>
                                <td class="py-2 px-4 border-b font-semibold">{{ $t->santri->nama_lengkap ?? '-' }}</td>
                                <td class="py-2 px-4 border-b">{{ $t->kategoriTagihan->nama ?? '-' }}</td>
                                <td class="py-2 px-4 border-b text-right text-gray-600">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border-b text-right text-red-600 font-bold">Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if($t->status == 'Lunas')
                                        <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs">Lunas</span>
                                    @elseif($t->status == 'Sebagian')
                                        <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded text-xs">Sebagian</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-xs">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <a href="{{ route('tagihan.print', $t->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 mr-2">Cetak</a>
                                    @if($t->status != 'Lunas')
                                    <a href="{{ route('pembayaran.create', ['tagihan_id' => $t->id]) }}" class="text-green-600 hover:text-green-900 mr-2">Bayar</a>
                                    <form action="{{ route('tagihan.destroy', $t->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin menghapus tagihan ini?')">Hapus</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $tagihans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
