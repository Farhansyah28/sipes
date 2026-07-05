<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Absensi Harian') }}
        </h2>
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
                    <form action="{{ route('absensi.index') }}" method="GET" class="flex items-end space-x-4">
                        <div class="w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Jadwal Mengajar</label>
                            <select name="jadwal_id" class="shadow border rounded w-full py-2 px-3" required>
                                <option value="">-- Pilih Jadwal --</option>
                                @foreach($jadwals as $j)
                                    <option value="{{ $j->id }}" {{ $jadwal_id == $j->id ? 'selected' : '' }}>
                                        {{ $j->hari }} - {{ $j->kelas->nama ?? '-' }} - {{ $j->mataPelajaran->nama ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-1/4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                            <input name="tanggal" type="date" value="{{ $tanggal }}" class="shadow border rounded w-full py-2 px-3" required>
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">Muat Santri</button>
                        </div>
                    </form>
                </div>
            </div>

            @if($jadwal_id)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Input Kehadiran Massal</h3>
                    <form action="{{ route('absensi.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jadwal_id" value="{{ $jadwal_id }}">
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        
                        <table class="min-w-full bg-white border border-gray-200 text-sm mb-6">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nama Santri</th>
                                    <th class="py-2 px-4 border-b text-center">Hadir</th>
                                    <th class="py-2 px-4 border-b text-center">Izin</th>
                                    <th class="py-2 px-4 border-b text-center">Sakit</th>
                                    <th class="py-2 px-4 border-b text-center">Alpha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($santris as $s)
                                @php $current = $absensi[$s->id]->status ?? 'Hadir'; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b font-semibold">{{ $s->nama_lengkap }} ({{ $s->nis }})</td>
                                    <td class="py-2 px-4 border-b text-center"><input type="radio" name="status[{{ $s->id }}]" value="Hadir" {{ $current == 'Hadir' ? 'checked' : '' }} class="w-5 h-5 text-green-600 cursor-pointer"></td>
                                    <td class="py-2 px-4 border-b text-center"><input type="radio" name="status[{{ $s->id }}]" value="Izin" {{ $current == 'Izin' ? 'checked' : '' }} class="w-5 h-5 text-blue-600 cursor-pointer"></td>
                                    <td class="py-2 px-4 border-b text-center"><input type="radio" name="status[{{ $s->id }}]" value="Sakit" {{ $current == 'Sakit' ? 'checked' : '' }} class="w-5 h-5 text-yellow-500 cursor-pointer"></td>
                                    <td class="py-2 px-4 border-b text-center"><input type="radio" name="status[{{ $s->id }}]" value="Alpha" {{ $current == 'Alpha' ? 'checked' : '' }} class="w-5 h-5 text-red-600 cursor-pointer"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($santris->isNotEmpty())
                        <div class="text-right">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow-lg text-lg">Simpan Kehadiran</button>
                        </div>
                        @else
                        <div class="text-center text-gray-500 italic">Tidak ada santri di kelas ini.</div>
                        @endif
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
