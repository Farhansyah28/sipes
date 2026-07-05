<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penerimaan Pembayaran Kasir') }}
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
                    <form action="{{ route('pembayaran.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Tagihan (Belum Lunas)</label>
                            <select name="tagihan_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">-- Cari Tagihan --</option>
                                @foreach($tagihans as $t)
                                    <option value="{{ $t->id }}" {{ (old('tagihan_id') == $t->id || $tagihan_id == $t->id) ? 'selected' : '' }}>
                                        {{ $t->santri->nama_lengkap ?? '-' }} - {{ $t->kategoriTagihan->nama ?? '-' }} (Sisa: Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nominal Dibayar (Rp)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nominal_dibayar" type="number" min="1" value="{{ old('nominal_dibayar') }}" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Bayar</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="tanggal_bayar" type="date" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai (Cash)</option>
                                <option value="Transfer Bank" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="Potong Tabungan" {{ old('metode_pembayaran') == 'Potong Tabungan' ? 'selected' : '' }}>Potong Tabungan</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan / Catatan Kasir</label>
                            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                        </div>
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit">Simpan Transaksi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
