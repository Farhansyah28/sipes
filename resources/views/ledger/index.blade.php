<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buku Besar / Ledger Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showModal: false }">
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

            <!-- Modal Tambah Transaksi Kas Manual -->
            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showModal" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showModal = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8211;</span>
                    <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Catat Arus Kas Manual</h3>
                            <form action="{{ route('ledger.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                        <input type="date" name="tanggal" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tipe Kas</label>
                                        <select name="tipe" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="Keluar">Kas Keluar (Pengeluaran)</option>
                                            <option value="Masuk">Kas Masuk (Pendapatan)</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Kategori (Cth: Listrik, Gaji, Donasi)</label>
                                        <input type="text" name="kategori" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                                        <input type="number" name="nominal" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Keterangan / Catatan</label>
                                        <textarea name="keterangan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Simpan Kas
                                    </button>
                                    <button type="button" @click="showModal = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Action -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center flex-wrap gap-4">
                    <form action="{{ route('ledger.index') }}" method="GET" class="flex items-end space-x-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Bulan Laporan</label>
                            <input type="month" name="bulan" value="{{ $bulan }}" class="shadow border rounded py-2 px-3 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">Filter Data</button>
                    </form>
                    <div class="flex space-x-2">
                        <button @click="showModal = true" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Catat Kas
                        </button>
                        <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow">ðŸ–¨ï¸ Cetak PDF</button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm border-l-4 border-green-500 p-6">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Total Kas Masuk</h4>
                    <p class="text-2xl font-black text-green-600">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border-l-4 border-red-500 p-6">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Total Kas Keluar</h4>
                    <p class="text-2xl font-black text-red-600">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border-l-4 {{ $saldoAkhir >= 0 ? 'border-indigo-500' : 'border-red-600' }} p-6">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Surplus / Defisit (Bulan Ini)</h4>
                    <p class="text-2xl font-black {{ $saldoAkhir >= 0 ? 'text-indigo-600' : 'text-red-600' }}">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 print:p-0 print:shadow-none">
                    <h3 class="text-2xl font-black mb-1 print:block uppercase tracking-widest text-gray-900">Jurnal Buku Besar (General Ledger)</h3>
                    <p class="text-gray-500 font-bold mb-6">Periode: {{ \Carbon\Carbon::parse($bulan . '-01')->format('F Y') }}</p>

                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 border-b text-left text-xs font-bold text-gray-600 uppercase">Tanggal</th>
                                <th class="py-3 px-4 border-b text-left text-xs font-bold text-gray-600 uppercase">Kategori</th>
                                <th class="py-3 px-4 border-b text-left text-xs font-bold text-gray-600 uppercase">Keterangan</th>
                                <th class="py-3 px-4 border-b text-right text-xs font-bold text-gray-600 uppercase">Kas Masuk (Debit)</th>
                                <th class="py-3 px-4 border-b text-right text-xs font-bold text-gray-600 uppercase">Kas Keluar (Kredit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kas_list as $kas)
                                <tr class="hover:bg-gray-50 border-b border-gray-100">
                                    <td class="py-3 px-4 text-gray-600">{{ $kas->tanggal }}</td>
                                    <td class="py-3 px-4 font-semibold text-gray-700">
                                        <span class="bg-gray-200 px-2 py-1 rounded text-xs">{{ $kas->kategori }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">{{ $kas->keterangan ?? '-' }}</td>
                                    @if($kas->tipe == 'Masuk')
                                        <td class="py-3 px-4 text-right font-bold text-green-600">Rp {{ number_format($kas->nominal, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right text-gray-400">-</td>
                                    @else
                                        <td class="py-3 px-4 text-right text-gray-400">-</td>
                                        <td class="py-3 px-4 text-right font-bold text-red-600">Rp {{ number_format($kas->nominal, 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500 italic">Tidak ada transaksi kas pada bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
