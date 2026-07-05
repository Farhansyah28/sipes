<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Katalog Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Form Tambah -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-1">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Daftarkan Barang Baru</h3>
                        <form action="{{ route('barang.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kode Barang (Barcode)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 font-mono" name="kode_barang" type="text" placeholder="Misal: BRG-001" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama" type="text" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                                <select name="kategori_barang_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga Jual (Rp)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-right font-bold text-green-600" name="harga_jual" type="number" min="0" required>
                            </div>
                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full" type="submit">Simpan Barang</button>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-2">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Katalog Barang Aktif</h3>
                        <table class="min-w-full bg-white border border-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Kode</th>
                                    <th class="py-2 px-4 border-b text-left">Nama Barang</th>
                                    <th class="py-2 px-4 border-b text-left">Kategori</th>
                                    <th class="py-2 px-4 border-b text-right">Harga Jual</th>
                                    <th class="py-2 px-4 border-b text-right">Stok Fisik</th>
                                    <th class="py-2 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangs as $b)
                                <tr>
                                    <td class="py-2 px-4 border-b font-mono text-xs text-gray-500">{{ $b->kode_barang }}</td>
                                    <td class="py-2 px-4 border-b font-bold text-gray-800">{{ $b->nama }}</td>
                                    <td class="py-2 px-4 border-b">{{ $b->kategoriBarang->nama ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b text-right text-green-600 font-semibold">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                                    <td class="py-2 px-4 border-b text-right">
                                        @if($b->stok_total > 10)
                                            <span class="text-blue-600 font-bold">{{ $b->stok_total }}</span>
                                        @elseif($b->stok_total > 0)
                                            <span class="text-yellow-600 font-bold">{{ $b->stok_total }}</span>
                                        @else
                                            <span class="text-red-600 font-bold">KOSONG</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b text-center">
                                        <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus barang ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $barangs->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
