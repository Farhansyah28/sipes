<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kantin') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="mb-8 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl shadow-lg border-0 overflow-hidden relative">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-32 h-32 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div class="p-8 relative z-10 flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl text-white font-bold mb-1">Laporan Kantin & POS</h2>
                    <p class="text-emerald-100 mb-0">Ringkasan Omzet, Laba/Rugi, dan Pergerakan Inventaris</p>
                </div>
                <a href="{{ route('laporan_kantin.print_shift') }}" target="_blank" onclick="console.log('Event Tracked: laporan_dicetak');" class="inline-flex items-center justify-center px-6 py-3 bg-white text-emerald-600 font-bold rounded-full shadow-md hover:bg-gray-50 active:scale-95 active:-translate-y-1 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Shift Kasir
                </a>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Penjualan Hari Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <h6 class="text-gray-500 font-semibold text-sm">Omzet Hari Ini</h6>
                    <div class="bg-blue-50 text-blue-600 rounded-xl p-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($penjualan_hari_ini, 0, ',', '.') }}</h3>
            </div>

            <!-- Laba Hari Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <h6 class="text-gray-500 font-semibold text-sm">Laba Hari Ini</h6>
                    <div class="bg-emerald-50 text-emerald-600 rounded-xl p-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-emerald-600 mb-1">Rp {{ number_format($laba_hari_ini, 0, ',', '.') }}</h3>
                <span class="text-xs text-gray-400">Net Profit dari FIFO HPP</span>
            </div>

            <!-- Penjualan Bulan Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <h6 class="text-gray-500 font-semibold text-sm">Omzet Bulan Ini</h6>
                    <div class="bg-indigo-50 text-indigo-600 rounded-xl p-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($penjualan_bulan_ini, 0, ',', '.') }}</h3>
            </div>

            <!-- Laba Bulan Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <h6 class="text-gray-500 font-semibold text-sm">Laba Bulan Ini</h6>
                    <div class="bg-emerald-50 text-emerald-600 rounded-xl p-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-emerald-600">Rp {{ number_format($laba_bulan_ini, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Barang Terlaris -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-amber-50 text-amber-500 rounded-full p-2 mr-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <h5 class="font-bold text-gray-800 text-lg">5 Barang Terlaris (All Time)</h5>
                    </div>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                                    <th class="py-4 px-6 font-semibold w-16">No</th>
                                    <th class="py-4 px-6 font-semibold">Nama Barang</th>
                                    <th class="py-4 px-6 font-semibold text-right">Terjual</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($barang_terlaris as $index => $bt)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6 font-medium text-gray-800">
                                        {{ $bt->barang->nama ?? 'Unknown' }}
                                        @if($index == 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 ml-2">#1 Best Seller</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-gray-700">{{ $bt->total_qty }} pcs</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-16 px-6 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-50 p-4 rounded-full mb-4">
                                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            </div>
                                            <h3 class="text-gray-500 font-medium text-lg">Belum ada penjualan</h3>
                                            <p class="text-gray-400 text-sm mt-1">Data barang terlaris akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div>
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-red-50 text-red-500 rounded-full p-2 mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h5 class="font-bold text-red-600 text-lg">Peringatan Stok Menipis</h5>
                    </div>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                                    <th class="py-4 px-6 font-semibold">Barang</th>
                                    <th class="py-4 px-6 font-semibold">Stok Saat Ini</th>
                                    <th class="py-4 px-6 font-semibold text-right">Batas Minimal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($stok_menipis as $stok)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="font-medium text-gray-800">{{ $stok->nama }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $stok->kategoriBarang->nama ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $stok->stok_total == 0 ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $stok->stok_total }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right text-gray-500">{{ $stok->stok_minimal }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-12 px-6 text-center">
                                        <div class="flex justify-center mb-3">
                                            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div class="text-gray-500 font-medium">Semua stok barang aman</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dummy Analytics Tracking Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Track page view / alert view
            const warningStok = {{ $stok_menipis->count() > 0 ? 'true' : 'false' }};
            if (warningStok) {
                console.log('Event Tracked: peringatan_stok_dilihat', { jumlah: {{ $stok_menipis->count() }} });
            }
        });
    </script>
</x-app-layout>
