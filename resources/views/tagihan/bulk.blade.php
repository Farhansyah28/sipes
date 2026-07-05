<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Tagihan Massal (SPP)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form action="{{ route('tagihan.bulk_store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Target Kelas</label>
                            <select name="kelas_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="all">-- Semua Kelas (Seluruh Santri Aktif) --</option>
                                @foreach($kelases as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih kelas spesifik atau generate untuk seluruh santri aktif.</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kategori Tagihan</label>
                            <select name="kategori_tagihan_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama }} (Rp {{ number_format($kat->nominal_default, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jatuh Tempo (Opsional)</label>
                                <input type="date" name="jatuh_tempo" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Periode / Bulan (Opsional)</label>
                                <input type="text" name="bulan_tagihan" placeholder="Misal: Juli 2026" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700"></textarea>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('tagihan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Proses ini akan men-generate tagihan untuk banyak santri sekaligus. Lanjutkan?');">
                                Generate Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
