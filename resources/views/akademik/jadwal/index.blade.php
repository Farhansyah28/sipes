<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Jadwal Mengajar') }}
        </h2>
    </x-slot>

    @php
        // Pre-process Data Jadwal untuk akses O(1) di Grid
        $jadwalKelas = [];
        $jadwalUstadz = [];
        foreach($jadwals as $j) {
            $jamKey = date('H:i', strtotime($j->jam_mulai));
            $jadwalKelas[$j->kelas_id][$j->hari][$jamKey] = $j;
            $jadwalUstadz[$j->ustadz_id][$j->hari][$jamKey] = $j;
        }
    @endphp

    <div class="py-8" x-data="{ 
        mode: 'kelas', 
        activeKelas: '{{ $kelases->first()->id ?? '' }}', 
        activeUstadz: '{{ $ustadzs->first()->id ?? '' }}',
        showForm: false
    }">
        <div class="w-full sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- TAB CONTROLS & ADD BUTTON -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                <div class="flex bg-white rounded-lg shadow-sm p-1 border border-gray-200">
                    <button @click="mode = 'kelas'" 
                            :class="{'bg-indigo-600 text-white shadow': mode === 'kelas', 'text-gray-600 hover:bg-gray-100': mode !== 'kelas'}" 
                            class="px-6 py-2 rounded-md text-sm font-bold transition-all duration-200">
                        Jadwal Per-Kelas
                    </button>
                    <button @click="mode = 'ustadz'" 
                            :class="{'bg-indigo-600 text-white shadow': mode === 'ustadz', 'text-gray-600 hover:bg-gray-100': mode !== 'ustadz'}" 
                            class="px-6 py-2 rounded-md text-sm font-bold transition-all duration-200 ml-1">
                        Jadwal Per-Ustadz
                    </button>
                </div>
                
                <div class="flex space-x-2">
                    <a href="{{ route('auto_schedule.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out text-sm flex items-center">
                        Pengaturan Jadwal Otomatis
                    </a>
                    <button @click="showForm = !showForm" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out text-sm">
                        <span x-text="showForm ? 'Tutup Form' : 'Buat Jadwal Manual'"></span>
                    </button>
                </div>
            </div>

            <!-- FORM JADWAL MANUAL (COLLAPSIBLE) -->
            <div x-show="showForm" x-transition.opacity class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-gray-800">
                <div class="p-6 text-gray-900 bg-gray-50">
                    <h3 class="text-lg font-bold mb-4">Form Tambah Jadwal Manual</h3>
                    <form action="{{ route('jadwal.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">Semester</label>
                            <select name="semester_id" class="shadow-sm border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                                @foreach($semesters as $s) <option value="{{ $s->id }}">{{ $s->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">Kelas</label>
                            <select name="kelas_id" class="shadow-sm border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                                @foreach($kelases as $k) <option value="{{ $k->id }}">{{ $k->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">Mapel</label>
                            <select name="mata_pelajaran_id" class="shadow-sm border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                                @foreach($mapels as $m) <option value="{{ $m->id }}">{{ $m->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">Ustadz</label>
                            <select name="ustadz_id" class="shadow-sm border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                                @foreach($ustadzs as $u) <option value="{{ $u->id }}">{{ $u->nama_lengkap }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">Hari</label>
                            <select name="hari" class="shadow-sm border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                                @foreach($hariList as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-gray-700 text-xs font-bold mb-1">Jam Pelajaran</label>
                            <select name="waktu" class="shadow-sm border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                                @foreach($jamList as $jam)
                                    @if(!$jam->is_istirahat)
                                        <option value="{{ date('H:i', strtotime($jam->jam_mulai)) }}-{{ date('H:i', strtotime($jam->jam_selesai)) }}">
                                            {{ date('H:i', strtotime($jam->jam_mulai)) }} - {{ date('H:i', strtotime($jam->jam_selesai)) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1 md:col-span-4 flex justify-end">
                            <button class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow-sm text-sm" type="submit">Simpan Manual</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CONTAINER 1: JADWAL PER KELAS -->
            <div x-show="mode === 'kelas'" x-transition.opacity class="bg-white shadow-xl sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-black text-indigo-900">Jadwal Matriks Kelas</h3>
                    <div class="flex items-center">
                        <label class="text-sm font-bold text-gray-700 mr-3">Pilih Kelas:</label>
                        <select x-model="activeKelas" class="shadow-sm border-gray-300 rounded-lg text-sm font-bold text-indigo-700 py-2 pl-4 pr-8 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($kelases as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-indigo-900 text-white">
                            <tr>
                                <th class="p-3 border border-indigo-800 text-center w-24">Waktu</th>
                                @foreach($hariList as $hari)
                                    <th class="p-3 border border-indigo-800 text-center w-32">{{ $hari }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jamList as $jam)
                                @php $jamKey = date('H:i', strtotime($jam->jam_mulai)); @endphp
                                @if($jam->is_istirahat)
                                    <tr class="bg-yellow-50">
                                        <td class="p-2 border border-gray-200 text-center font-bold text-yellow-700 text-xs">
                                            {{ date('H:i', strtotime($jam->jam_mulai)) }} - {{ date('H:i', strtotime($jam->jam_selesai)) }}
                                        </td>
                                        <td colspan="7" class="p-2 border border-gray-200 text-center font-black tracking-widest text-yellow-600">I S T I R A H A T</td>
                                    </tr>
                                @else
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-2 border border-gray-200 text-center font-bold text-gray-700 bg-gray-50 text-xs">
                                            {{ date('H:i', strtotime($jam->jam_mulai)) }} - {{ date('H:i', strtotime($jam->jam_selesai)) }}
                                        </td>
                                        
                                        @foreach($hariList as $hari)
                                            <td class="p-2 border border-gray-200 align-top h-20">
                                                @foreach($kelases as $k)
                                                    <div x-show="activeKelas == '{{ $k->id }}'" style="display: none;">
                                                        @if(isset($jadwalKelas[$k->id][$hari][$jamKey]))
                                                            @php $jadwal = $jadwalKelas[$k->id][$hari][$jamKey]; @endphp
                                                            <div class="bg-indigo-100 border-l-4 border-indigo-500 p-2 rounded shadow-sm h-full flex flex-col justify-between">
                                                                <div class="font-bold text-indigo-900 leading-tight mb-1">{{ $jadwal->mataPelajaran->nama ?? '-' }}</div>
                                                                <div class="text-xs text-indigo-700 flex justify-between items-end">
                                                                    <span>{{ $jadwal->ustadz->nama_lengkap ?? '-' }}</span>
                                                                    <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="inline" @click.stop>
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="text-red-500 hover:text-red-700 text-[10px] bg-white px-1 rounded border border-red-200" onclick="return confirm('Hapus?')">•</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="h-full flex items-center justify-center text-gray-300 text-xs border border-dashed border-gray-200 rounded">Kosong</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CONTAINER 2: JADWAL PER USTADZ -->
            <div x-show="mode === 'ustadz'" x-transition.opacity class="bg-white shadow-xl sm:rounded-xl border border-gray-100 overflow-hidden" style="display: none;">
                <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-black text-purple-900">Jadwal Matriks Ustadz</h3>
                    <div class="flex items-center">
                        <label class="text-sm font-bold text-gray-700 mr-3">Pilih Ustadz:</label>
                        <select x-model="activeUstadz" class="shadow-sm border-gray-300 rounded-lg text-sm font-bold text-purple-700 py-2 pl-4 pr-8 focus:ring-purple-500 focus:border-purple-500">
                            @foreach($ustadzs as $u)
                                <option value="{{ $u->id }}">{{ $u->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-purple-900 text-white">
                            <tr>
                                <th class="p-3 border border-purple-800 text-center w-24">Waktu</th>
                                @foreach($hariList as $hari)
                                    <th class="p-3 border border-purple-800 text-center w-32">{{ $hari }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jamList as $jam)
                                @php $jamKey = date('H:i', strtotime($jam->jam_mulai)); @endphp
                                @if($jam->is_istirahat)
                                    <tr class="bg-yellow-50">
                                        <td class="p-2 border border-gray-200 text-center font-bold text-yellow-700 text-xs">
                                            {{ date('H:i', strtotime($jam->jam_mulai)) }} - {{ date('H:i', strtotime($jam->jam_selesai)) }}
                                        </td>
                                        <td colspan="7" class="p-2 border border-gray-200 text-center font-black tracking-widest text-yellow-600">I S T I R A H A T</td>
                                    </tr>
                                @else
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-2 border border-gray-200 text-center font-bold text-gray-700 bg-gray-50 text-xs">
                                            {{ date('H:i', strtotime($jam->jam_mulai)) }} - {{ date('H:i', strtotime($jam->jam_selesai)) }}
                                        </td>
                                        
                                        @foreach($hariList as $hari)
                                            <td class="p-2 border border-gray-200 align-top h-20">
                                                @foreach($ustadzs as $u)
                                                    <div x-show="activeUstadz == '{{ $u->id }}'" style="display: none;">
                                                        @if(isset($jadwalUstadz[$u->id][$hari][$jamKey]))
                                                            @php $jadwal = $jadwalUstadz[$u->id][$hari][$jamKey]; @endphp
                                                            <div class="bg-purple-100 border-l-4 border-purple-500 p-2 rounded shadow-sm h-full flex flex-col justify-between">
                                                                <div class="font-bold text-purple-900 leading-tight mb-1">{{ $jadwal->mataPelajaran->nama ?? '-' }}</div>
                                                                <div class="text-xs text-purple-700 flex justify-between items-end">
                                                                    <span class="bg-white px-1 rounded font-bold border border-purple-200">Kls: {{ $jadwal->kelas->nama ?? '-' }}</span>
                                                                    <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="inline" @click.stop>
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="text-red-500 hover:text-red-700 text-[10px] bg-white px-1 rounded border border-red-200" onclick="return confirm('Hapus?')">•</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="h-full flex items-center justify-center text-gray-300 text-xs border border-dashed border-gray-200 rounded">Kosong</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
