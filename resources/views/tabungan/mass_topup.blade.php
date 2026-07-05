<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Top-Up Tabungan Massal per Kelas') }}
            </h2>
            <a href="{{ route('tabungan.index') }}" class="text-gray-500 hover:text-gray-700 font-bold text-sm">&larr; Kembali ke Tabungan</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <form action="{{ route('tabungan.mass_topup') }}" method="GET" class="flex items-end space-x-4">
                        <div class="w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Kelas</label>
                            <select name="kelas_id" class="shadow border rounded w-full py-2 px-3" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelases as $k)
                                    <option value="{{ $k->id }}" {{ $kelas_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">Muat Rekening Kelas</button>
                        </div>
                    </form>
                </div>
            </div>

            @if($kelas_id)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Input Top-Up Massal</h3>
                    <form action="{{ route('tabungan.store_mass_topup') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan Top-Up</label>
                            <input type="text" name="keterangan" required placeholder="Contoh: Top-up uang saku bulanan dari bendahara" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <table class="min-w-full bg-white border border-gray-200 text-sm mb-6">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nama Santri</th>
                                    <th class="py-2 px-4 border-b text-left">Saldo Saat Ini</th>
                                    <th class="py-2 px-4 border-b text-left w-64">Nominal Top-Up (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tabungans as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 border-b font-semibold">{{ $t->santri->nama_lengkap }} ({{ $t->santri->nis }})</td>
                                    <td class="py-3 px-4 border-b font-bold text-gray-600">Rp {{ number_format($t->saldo, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 border-b">
                                        <input type="number" min="0" name="nominal[{{ $t->id }}]" value="0" class="shadow border rounded w-full py-1 px-3 focus:ring-indigo-500 font-mono text-lg font-bold text-right" placeholder="0">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($tabungans->isNotEmpty())
                        <div class="text-right">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow-lg text-lg">Proses Top-Up Massal</button>
                        </div>
                        @else
                        <div class="text-center text-gray-500 italic py-6 border rounded">
                            Tidak ada data santri dengan tabungan aktif di kelas ini.<br>
                            Pastikan santri sudah memiliki rekening tabungan.
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
