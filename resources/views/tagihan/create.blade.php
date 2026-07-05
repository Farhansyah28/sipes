<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Tagihan Santri Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('tagihan.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Santri</label>
                            <select name="santri_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">-- Pilih Santri --</option>
                                @foreach($santris as $s)
                                    <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>{{ $s->nis }} - {{ $s->nama_lengkap }} ({{ $s->kelas->nama ?? 'Tanpa Kelas' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kategori Tagihan (Nominal otomatis ditarik dari Master)</label>
                            <select name="kategori_tagihan_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">-- Pilih Kategori Tagihan --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" {{ old('kategori_tagihan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }} (Rp {{ number_format($k->nominal_default, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Bulan Tagihan (Misal: 2024-07) - Opsional</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="bulan_tagihan" type="month" value="{{ old('bulan_tagihan') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Batas Jatuh Tempo</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="jatuh_tempo" type="date" value="{{ old('jatuh_tempo') }}">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan Tambahan</label>
                            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Terbitkan Tagihan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
