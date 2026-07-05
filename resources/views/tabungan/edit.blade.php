<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mutasi Tabungan Santri: ') . $tabungan->santri->nama_lengkap }}
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
                <!-- PANEL TRANSAKSI -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-1">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-2">Transaksi Mutasi</h3>
                        <div class="bg-blue-50 p-4 rounded mb-6 text-center">
                            <span class="text-gray-600 text-sm">Saldo Saat Ini</span>
                            <div class="text-2xl font-bold text-blue-700">Rp {{ number_format($tabungan->saldo, 0, ',', '.') }}</div>
                        </div>

                        <form action="{{ route('tabungan.update', $tabungan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Transaksi</label>
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio text-green-500" name="tipe" value="Setor" {{ old('tipe') == 'Setor' ? 'checked' : 'checked' }}>
                                        <span class="ml-2">Setor Tunai</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio text-red-500" name="tipe" value="Tarik" {{ old('tipe') == 'Tarik' ? 'checked' : '' }}>
                                        <span class="ml-2">Tarik Tunai</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nominal (Rp)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nominal" type="number" min="1" value="{{ old('nominal') }}" required>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan Tambahan</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="keterangan" type="text" value="{{ old('keterangan', 'Uang Jajan') }}" required>
                            </div>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full" type="submit">Proses Transaksi</button>
                        </form>
                    </div>
                </div>

                <!-- PANEL HISTORI -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-2">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Histori Mutasi Terakhir</h3>
                        <table class="min-w-full bg-white border border-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Tanggal</th>
                                    <th class="py-2 px-4 border-b text-center">Tipe</th>
                                    <th class="py-2 px-4 border-b text-right">Nominal</th>
                                    <th class="py-2 px-4 border-b text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mutasis as $m)
                                <tr>
                                    <td class="py-2 px-4 border-b text-gray-500">{{ \Carbon\Carbon::parse($m->tanggal)->format('d M Y') }}</td>
                                    <td class="py-2 px-4 border-b text-center">
                                        @if($m->tipe == 'Setor')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">+ SETOR</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">- TARIK</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b text-right font-bold {{ $m->tipe == 'Setor' ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($m->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 px-4 border-b">{{ $m->keterangan }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500">Belum ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
