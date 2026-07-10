<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Pendaftar: ') }} <span class="text-indigo-600">{{ $psb->nama_lengkap }}</span>
            </h2>
            <a href="{{ route('psb.index') }}" class="text-gray-500 hover:text-gray-700 font-bold text-sm">&larr;
                Kembali ke Daftar</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Status & Action (Kiri) -->
                <div class="col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 text-center">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Status Saat Ini
                            </h3>
                            <span
                                class="px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-800 inline-block uppercase shadow-sm mb-4">
                                {{ $psb->status }}
                            </span>
                            <hr class="my-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Update Status
                                Pipeline</h3>
                            <form action="{{ route('psb.update_status', $psb->id) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="status" class="w-full border-gray-300 rounded shadow-sm mb-4">
                                    <option value="Verifikasi" {{ $psb->status == 'Verifikasi' ? 'selected' : '' }}>
                                        Verifikasi Berkas</option>
                                    <option value="Tes" {{ $psb->status == 'Tes' ? 'selected' : '' }}>Masuk Tahap Tes
                                    </option>
                                    <option value="Lulus" {{ $psb->status == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="Tidak Lulus" {{ $psb->status == 'Tidak Lulus' ? 'selected' : '' }}>
                                        Tidak Lulus</option>
                                    <option value="Daftar Ulang" {{ $psb->status == 'Daftar Ulang' ? 'selected' : '' }}>
                                        Menunggu Daftar Ulang</option>
                                    <option value="Aktif" {{ $psb->status == 'Aktif' ? 'selected' : '' }}>Resmi Jadi
                                        Santri (Aktif)</option>
                                </select>
                                <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4 border-b pb-2">Data Pribadi</h3>
                            <div class="text-sm space-y-3">
                                <p><span class="font-bold text-gray-500 block">Nama Lengkap</span>
                                    {{ $psb->nama_lengkap }} (NISN: {{ $psb->nisn ?? '-' }})</p>
                                <p><span class="font-bold text-gray-500 block">TTL</span> {{ $psb->tempat_lahir }},
                                    {{ $psb->tanggal_lahir }}</p>
                                <p><span class="font-bold text-gray-500 block">Jenis Kelamin</span>
                                    {{ $psb->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>

                            <h3 class="text-lg font-bold mt-6 mb-4 border-b pb-2">Data Orang Tua</h3>
                            <div class="text-sm space-y-3">
                                <p><span class="font-bold text-gray-500 block">Nama Ayah</span>
                                    {{ $psb->orangTua->nama_ayah ?? '-' }}</p>
                                <p><span class="font-bold text-gray-500 block">Nama Ibu</span>
                                    {{ $psb->orangTua->nama_ibu ?? '-' }}</p>
                                <p><span class="font-bold text-gray-500 block">No HP / WhatsApp</span>
                                    {{ $psb->orangTua->no_hp_ayah ?? '-' }}</p>
                                <p><span class="font-bold text-gray-500 block">Alamat Lengkap</span>
                                    {{ $psb->orangTua->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen & Nilai Tes (Kanan) -->
                <div class="col-span-2 space-y-6">
                    <!-- Dokumen -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4">Verifikasi Dokumen Pendaftaran</h3>
                            <table class="min-w-full bg-white border border-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Jenis Dokumen</th>
                                        <th class="py-2 px-4 border-b text-left">File</th>
                                        <th class="py-2 px-4 border-b text-center">Status</th>
                                        <th class="py-2 px-4 border-b text-center">Aksi Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dokumens as $dok)
                                        <tr>
                                            <td class="py-2 px-4 border-b font-semibold">{{ $dok->jenis_dokumen }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ Storage::url($dok->file_path) }}" target="_blank"
                                                    class="text-indigo-600 hover:underline flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Lihat File
                                                </a>
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <span
                                                    class="px-2 py-1 text-xs font-bold rounded {{ $dok->status_verifikasi == 'Valid' ? 'bg-green-100 text-green-800' : ($dok->status_verifikasi == 'Tidak Valid' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ $dok->status_verifikasi }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <form action="{{ route('psb.verify_dokumen', $dok->id) }}" method="POST"
                                                    class="inline-flex space-x-2">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status_verifikasi" value="Valid">
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-800 font-bold text-xs">”
                                                        VALID</button>
                                                </form>
                                                <form action="{{ route('psb.verify_dokumen', $dok->id) }}" method="POST"
                                                    class="inline-flex space-x-2 ml-2">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status_verifikasi" value="Tidak Valid">
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 font-bold text-xs">–
                                                        TOLAK</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 text-center text-gray-500">Belum ada dokumen yang
                                                diunggah.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Input Nilai Tes -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4">Input Penilaian Tes Seleksi</h3>
                            <form action="{{ route('psb.store_penilaian', $psb->id) }}" method="POST"
                                class="flex space-x-4 mb-6">
                                @csrf
                                <div class="w-1/3">
                                    <input type="text" name="jenis_tes" placeholder="Jenis Tes (Membaca Quran, dll)"
                                        required class="w-full shadow-sm border-gray-300 rounded text-sm">
                                </div>
                                <div class="w-1/4">
                                    <input type="number" step="0.01" name="nilai" placeholder="Nilai (0-100)" required
                                        class="w-full shadow-sm border-gray-300 rounded text-sm font-mono">
                                </div>
                                <div class="w-1/3">
                                    <input type="text" name="catatan" placeholder="Catatan (Opsional)"
                                        class="w-full shadow-sm border-gray-300 rounded text-sm">
                                </div>
                                <div>
                                    <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm">Simpan
                                        Nilai</button>
                                </div>
                            </form>

                            <table class="min-w-full bg-white border border-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Jenis Tes</th>
                                        <th class="py-2 px-4 border-b text-center">Nilai</th>
                                        <th class="py-2 px-4 border-b text-left">Penilai</th>
                                        <th class="py-2 px-4 border-b text-left">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penilaians as $pen)
                                        <tr>
                                            <td class="py-2 px-4 border-b font-semibold text-gray-800">{{ $pen->jenis_tes }}
                                            </td>
                                            <td class="py-2 px-4 border-b text-center font-black text-indigo-600">
                                                {{ $pen->nilai }}</td>
                                            <td class="py-2 px-4 border-b text-xs text-gray-500">
                                                {{ $pen->penilai->name ?? 'Admin' }}</td>
                                            <td class="py-2 px-4 border-b text-xs text-gray-500">{{ $pen->catatan }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 text-center text-gray-500">Belum ada nilai tes.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>