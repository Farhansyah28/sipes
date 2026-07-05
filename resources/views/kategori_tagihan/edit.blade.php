<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kategori Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('kategori_tagihan.update', $kategori_tagihan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tagihan</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama" type="text" value="{{ old('nama', $kategori_tagihan->nama) }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Siklus</label>
                            <select name="tipe_siklus" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="Bulanan" {{ old('tipe_siklus', $kategori_tagihan->tipe_siklus) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="Sekali Bayar" {{ old('tipe_siklus', $kategori_tagihan->tipe_siklus) == 'Sekali Bayar' ? 'selected' : '' }}>Sekali Bayar</option>
                                <option value="Tahunan" {{ old('tipe_siklus', $kategori_tagihan->tipe_siklus) == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nominal Default (Rp)</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nominal_default" type="number" min="0" step="1000" value="{{ old('nominal_default', $kategori_tagihan->nominal_default) }}" required>
                        </div>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" type="submit">Perbarui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
