<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Restock Barang Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('batch_stok.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Barang</label>
                                <select name="barang_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                    <option value="">-- Cari Barang --</option>
                                    @foreach($barangs as $b)
                                        <option value="{{ $b->id }}">{{ $b->kode_barang }} - {{ $b->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Supplier Pemasok (Opsional)</label>
                                <select name="supplier_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    <option value="">-- Beli Sendiri / Tanpa Supplier --</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Barang Masuk (Qty)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="qty_awal" type="number" min="1" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga Beli Satuan (Modal / Rp)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="harga_beli_satuan" type="number" min="0" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="tanggal_masuk" type="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kadaluarsa (Expired) - Opsional</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="tanggal_kadaluarsa" type="date">
                            </div>
                        </div>

                        <button class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow" type="submit">Catat Restock</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
