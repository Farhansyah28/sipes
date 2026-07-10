<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Otomatis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- GENERATOR SECTION -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-8 text-center flex flex-col md:flex-row items-center justify-between">
                    <div class="text-left mb-6 md:mb-0">
                        <h2 class="text-2xl font-black text-gray-900 mb-2">Mesin Penjadwalan Cerdas Siap! 🚀</h2>
                        <p class="text-gray-500 max-w-xl">Cukup atur beban mengajar ustadz di bawah, lalu klik Generate.
                            Sistem akan otomatis mencari jadwal terbaik tanpa bentrok.</p>
                    </div>
                    <form action="{{ route('auto_schedule.process') }}" method="POST">
                        @csrf
                        <button type="submit"
                            onclick="this.innerHTML='Sedang Mengkalkulasi... â³'; this.classList.add('opacity-75', 'cursor-not-allowed')"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/30 text-white font-bold py-4 px-8 rounded-full text-lg transition-all transform hover:scale-105">
                            ¨ Generate Jadwal!
                        </button>
                    </form>
                </div>
            </div>

            <!-- TWO COLUMNS: BEBAN AJAR & KETERSEDIAAN -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- BEBAN MENGAJAR -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4 text-indigo-700 border-b pb-2">ðŸ“š Beban Mengajar</h3>
                        <form action="{{ route('auto_schedule.store_pengampu') }}" method="POST"
                            class="space-y-4 mb-6 bg-gray-50 p-4 rounded-lg">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Ustadz</label>
                                    <select name="ustadz_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        @foreach($ustadzs as $u) <option value="{{ $u->id }}">{{ $u->nama_lengkap }}
                                        </option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                                    <select name="mata_pelajaran_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        @foreach($mapels as $m) <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                                    <select name="kelas_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Beban / Minggu (JP)</label>
                                    <div class="flex items-end">
                                        <input type="number" name="beban_jp" value="2" min="1"
                                            class="mt-1 block w-full rounded-l-md border-gray-300 shadow-sm" required>
                                        <button type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-r-md">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-3 border-b text-left">Pengajar</th>
                                        <th class="py-2 px-3 border-b text-left">Mapel & Kelas</th>
                                        <th class="py-2 px-3 border-b text-center">JP</th>
                                        <th class="py-2 px-3 border-b text-center">X</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengampus as $p)
                                        <tr>
                                            <td class="py-2 px-3 border-b font-semibold">{{ $p->ustadz->nama_lengkap }}</td>
                                            <td class="py-2 px-3 border-b">{{ $p->mata_pelajaran->nama }} <span
                                                    class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">{{ $p->kelas->nama }}</span>
                                            </td>
                                            <td class="py-2 px-3 border-b text-center">{{ $p->beban_jp }}</td>
                                            <td class="py-2 px-3 border-b text-center">
                                                <form action="{{ route('auto_schedule.destroy_pengampu', $p->id) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold"
                                                        onclick="return confirm('Hapus?')">Ã—</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 text-center text-gray-500 italic">Belum ada beban
                                                ajar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- KETERSEDIAAN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4 text-purple-700 border-b pb-2">ðŸ•’ Pengecualian Waktu</h3>
                        <p class="text-xs text-gray-500 mb-4">Secara default, ustadz dianggap selalu bersedia. Tambahkan
                            data di bawah <b>hanya</b> jika ustadz bersangkutan memiliki jam terbatas (Misal hanya bisa
                            di hari tertentu).</p>

                        <form action="{{ route('auto_schedule.store_ketersediaan') }}" method="POST"
                            class="space-y-4 mb-6 bg-gray-50 p-4 rounded-lg">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Ustadz</label>
                                    <select name="ustadz_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        @foreach($ustadzs as $u) <option value="{{ $u->id }}">{{ $u->nama_lengkap }}
                                        </option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Hari</label>
                                    <select name="hari" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        required>
                                        @foreach($hariList as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jam Ke</label>
                                    <div class="flex items-end">
                                        <select name="jam_ke"
                                            class="mt-1 block w-full rounded-l-md border-gray-300 shadow-sm" required>
                                            @foreach($jamList as $j) <option value="{{ $j->jam_ke }}">{{ $j->jam_ke }}
                                            </option> @endforeach
                                        </select>
                                        <button type="submit"
                                            class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded-r-md">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-3 border-b text-left">Ustadz</th>
                                        <th class="py-2 px-3 border-b text-left">Tersedia Di Waktu</th>
                                        <th class="py-2 px-3 border-b text-center">X</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ketersediaans as $k)
                                        <tr>
                                            <td class="py-2 px-3 border-b font-semibold">{{ $k->ustadz->nama_lengkap }}</td>
                                            <td class="py-2 px-3 border-b"><span
                                                    class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">{{ $k->hari }},
                                                    Jam Ke-{{ $k->jam_ke }}</span></td>
                                            <td class="py-2 px-3 border-b text-center">
                                                <form action="{{ route('auto_schedule.destroy_ketersediaan', $k->id) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold"
                                                        onclick="return confirm('Hapus?')">Ã—</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-gray-500 italic">Belum ada
                                                pengecualian (Semua ustadz bebas waktu).</td>
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