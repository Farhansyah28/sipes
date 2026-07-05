<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penugasan Akademik Ustadz (Mass Assignment)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="penugasanController()">
        <div class="w-full sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. Pemilihan Ustadz -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div class="flex-1 max-w-xl">
                        <label for="ustadz_select" class="block text-sm font-medium text-gray-700 mb-1">Pilih Ustadz untuk dikelola:</label>
                        <form action="{{ route('penugasan.ustadz.index') }}" method="GET" class="flex gap-2">
                            <select name="ustadz_id" id="ustadz_select" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Ustadz --</option>
                                @foreach($ustadzs as $u)
                                    <option value="{{ $u->id }}" {{ (isset($selected_ustadz) && $selected_ustadz->id == $u->id) ? 'selected' : '' }}>
                                        {{ $u->nip }} - {{ $u->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow">Kelola</button>
                        </form>
                    </div>
                </div>
            </div>

            @if($selected_ustadz)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- 2. Area Penugasan Mata Pelajaran -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                        <div class="p-4 bg-indigo-50 border-b border-indigo-100">
                            <h3 class="font-bold text-indigo-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Beban Mengajar & Mata Pelajaran
                            </h3>
                        </div>
                        <div class="p-6 flex-1 text-gray-900">
                            <div class="mb-4">
                                <table class="min-w-full divide-y divide-gray-200 border text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase">Kelas</th>
                                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                                            <th class="px-3 py-2 text-center font-medium text-gray-500 uppercase">JP</th>
                                            <th class="px-3 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($pengampus as $p)
                                            <tr>
                                                <td class="px-3 py-2 font-medium">{{ $p->kelas->nama ?? '-' }}</td>
                                                <td class="px-3 py-2">{{ $p->mata_pelajaran->nama }}</td>
                                                <td class="px-3 py-2 text-center">{{ $p->beban_jp }}</td>
                                                <td class="px-3 py-2 text-right">
                                                    <form action="{{ route('penugasan.ustadz.delete_pengampu', $p->id) }}" method="POST" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus penugasan ini?')" class="text-red-500 hover:text-red-700">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-3 py-4 text-center text-gray-400 italic">Belum ada penugasan mengajar.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Quick Add Form -->
                            <form action="{{ route('penugasan.ustadz.add_pengampu', $selected_ustadz->id) }}" method="POST" class="bg-gray-50 p-4 rounded border">
                                @csrf
                                <h4 class="text-sm font-bold text-gray-700 mb-3">Tambah Penugasan Baru</h4>
                                <div class="grid grid-cols-3 gap-3 mb-3">
                                    <div>
                                        <select name="kelas_id" required class="block w-full rounded-md border-gray-300 shadow-sm text-sm p-2">
                                            <option value="">-- Kelas --</option>
                                            @foreach($kelases as $k) <option value="{{ $k->id }}">{{ $k->nama }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <select name="mata_pelajaran_id" required class="block w-full rounded-md border-gray-300 shadow-sm text-sm p-2">
                                            <option value="">-- Mapel --</option>
                                            @foreach($mapels as $m) <option value="{{ $m->id }}">{{ $m->nama }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <input type="number" name="beban_jp" required min="1" placeholder="Beban JP (Misal: 2)" class="block w-full rounded-md border-gray-300 shadow-sm text-sm p-2">
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded text-sm font-bold">+ Tambah Tugaskan</button>
                            </form>
                        </div>
                    </div>

                    <!-- 3. Area Pengecualian Waktu (Time Exceptions) -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                        <div class="p-4 bg-red-50 border-b border-red-100 flex justify-between items-center">
                            <h3 class="font-bold text-red-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pengecualian Waktu (Klik Kotak)
                            </h3>
                        </div>
                        <div class="p-4 flex-1 text-gray-900 overflow-x-auto">
                            <p class="text-xs text-gray-600 mb-3">Klik kotak grid di bawah untuk menandai hari/jam di mana <span class="font-bold">{{ $selected_ustadz->nama_lengkap }}</span> <strong>TIDAK BISA</strong> mengajar. Kotak merah berarti tidak tersedia (Exception).</p>
                            
                            <table class="w-full border-collapse text-xs text-center">
                                <thead>
                                    <tr>
                                        <th class="border p-2 bg-gray-100">Hari \ Jam</th>
                                        @foreach($jam_pelajarans as $jp)
                                            <th class="border p-1 bg-gray-50 text-gray-500 w-10">J-{{ $jp->jam_ke }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hari_aktif as $hari)
                                        <tr>
                                            <td class="border p-2 font-bold bg-gray-50 text-left">{{ $hari }}</td>
                                            @foreach($jam_pelajarans as $jp)
                                                @php $cellKey = $hari . '_' . $jp->jam_ke; @endphp
                                                <td class="border p-0 m-0">
                                                    <button type="button" 
                                                            @click="toggleAvailability('{{ $hari }}', {{ $jp->jam_ke }})"
                                                            :class="isUnavailable('{{ $cellKey }}') ? 'bg-red-500 hover:bg-red-600' : 'bg-white hover:bg-gray-100'"
                                                            class="w-full h-8 transition-colors flex items-center justify-center cursor-pointer">
                                                        <svg x-show="isUnavailable('{{ $cellKey }}')" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4 flex gap-4 text-xs">
                                <div class="flex items-center"><div class="w-4 h-4 bg-white border mr-2"></div> Bisa Mengajar</div>
                                <div class="flex items-center"><div class="w-4 h-4 bg-red-500 border border-red-600 mr-2"></div> Tidak Bisa (Exception)</div>
                            </div>
                        </div>
                    </div>

                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col items-center justify-center py-20">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <p class="text-gray-500 font-medium">Silakan pilih Ustadz terlebih dahulu pada menu dropdown di atas.</p>
                </div>
            @endif

        </div>
    </div>

    @if($selected_ustadz)
    <script>
        function penugasanController() {
            return {
                unavailableSlots: @json($ketersediaans),
                ustadzId: {{ $selected_ustadz->id }},
                
                isUnavailable(key) {
                    return this.unavailableSlots.includes(key);
                },

                toggleAvailability(hari, jam_ke) {
                    let key = hari + '_' + jam_ke;
                    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`/penugasan-ustadz/${this.ustadzId}/ketersediaan`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            hari: hari,
                            jam_ke: jam_ke
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'unavailable') {
                            if (!this.unavailableSlots.includes(key)) {
                                this.unavailableSlots.push(key);
                            }
                        } else {
                            this.unavailableSlots = this.unavailableSlots.filter(k => k !== key);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan jaringan saat menyimpan data.');
                    });
                }
            }
        }
    </script>
    @endif
</x-app-layout>
