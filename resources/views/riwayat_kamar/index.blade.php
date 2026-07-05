<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penempatan Kamar Asrama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Form Tambah -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-1">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Tempatkan Santri</h3>
                        <form action="{{ route('riwayat_kamar.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Santri</label>
                                <select name="santri_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                    <option value="">-- Cari Santri --</option>
                                    @foreach($santris as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->nis }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Kamar</label>
                                <select name="kamar_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                    <option value="">-- Pilih Kamar --</option>
                                    @foreach($kamars as $k)
                                        <option value="{{ $k->id }}">{{ $k->gedung->nama }} - Kamar {{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="tanggal_masuk" type="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <button class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow w-full" type="submit">Tempatkan di Kamar</button>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-2">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Penempatan Santri Aktif</h3>
                        <table class="min-w-full bg-white border border-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Santri</th>
                                    <th class="py-2 px-4 border-b text-left">Lokasi Kamar</th>
                                    <th class="py-2 px-4 border-b text-center">Tgl Masuk</th>
                                    <th class="py-2 px-4 border-b text-center">Aksi (Checkout)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayats as $r)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-2 px-4 border-b font-semibold text-gray-800">{{ $r->santri->nama_lengkap ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-bold">{{ $r->kamar->gedung->nama ?? '-' }} - {{ $r->kamar->nama ?? '-' }}</span>
                                    </td>
                                    <td class="py-2 px-4 border-b text-center text-gray-500">{{ \Carbon\Carbon::parse($r->tanggal_masuk)->format('d M Y') }}</td>
                                    <td class="py-2 px-4 border-b text-center">
                                        <form action="{{ route('riwayat_kamar.update', $r->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="tanggal_keluar" value="{{ date('Y-m-d') }}">
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded" onclick="return confirm('Keluarkan santri ini dari kamar sekarang?')">Checkout</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($riwayats->isEmpty())
                                <tr><td colspan="4" class="py-4 text-center text-gray-500">Belum ada santri yang menempati kamar.</td></tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $riwayats->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
