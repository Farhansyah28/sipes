<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Kategori Barang Kantin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Form Tambah -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-1">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Tambah Kategori Baru</h3>
                        <form action="{{ route('kategori_barang.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama" type="text" placeholder="Misal: Makanan Ringan" required>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" type="submit">Simpan</button>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-2">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Daftar Kategori</h3>
                        <table class="min-w-full bg-white border border-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">No</th>
                                    <th class="py-2 px-4 border-b text-left">Nama Kategori</th>
                                    <th class="py-2 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $k)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4 border-b font-semibold">{{ $k->nama }}</td>
                                    <td class="py-2 px-4 border-b text-center">
                                        <form action="{{ route('kategori_barang.destroy', $k->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $kategoris->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
