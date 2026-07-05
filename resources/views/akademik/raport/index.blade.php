<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print:hidden">
            {{ __('Cetak Raport Santri') }}
        </h2>
    </x-slot>

    <div class="py-12 print:py-0">
        <div class="w-full sm:px-6 lg:px-8 print:px-0 print:max-w-none">
            <!-- Selector Area (Hidden when printing) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 print:hidden">
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <form action="{{ route('nilai.raport') }}" method="GET" class="flex items-end space-x-4">
                        <div class="w-1/3">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Santri</label>
                            <select name="santri_id" class="shadow border rounded w-full py-2 px-3" required>
                                <option value="">-- Pilih Santri --</option>
                                @foreach($santris as $s)
                                    <option value="{{ $s->id }}" {{ $santri_id == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama_lengkap }} ({{ $s->nis }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-1/4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Semester</label>
                            <select name="semester_id" class="shadow border rounded w-full py-2 px-3" required>
                                <option value="">-- Pilih Semester --</option>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                        {{ $sem->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">Muat Raport</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Raport Paper Area -->
            @if($santri_id && $semester_id)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg print:shadow-none print:rounded-none">
                <div class="p-8 text-gray-900" id="raport-area">
                    
                    <div class="flex justify-between items-start border-b-4 border-gray-800 pb-4 mb-6">
                        <div>
                            <h1 class="text-3xl font-black uppercase tracking-widest text-indigo-900">RAPORT AKADEMIK</h1>
                            <p class="text-gray-600 font-bold mt-1">Sistem Informasi Pesantren (SIPES)</p>
                        </div>
                        <button onclick="window.print()" class="print:hidden bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak PDF / Print
                        </button>
                    </div>

                    <!-- Identitas Santri -->
                    <div class="grid grid-cols-2 gap-4 mb-8 text-sm">
                        <table class="w-full">
                            <tr><td class="w-32 text-gray-500 font-bold py-1">Nama Santri</td><td class="font-bold text-gray-800">: {{ $santri->nama_lengkap }}</td></tr>
                            <tr><td class="text-gray-500 font-bold py-1">Nomor Induk</td><td class="font-bold text-gray-800">: {{ $santri->nis }}</td></tr>
                        </table>
                        <table class="w-full">
                            <tr><td class="w-32 text-gray-500 font-bold py-1">Kelas</td><td class="font-bold text-gray-800">: {{ $santri->kelas->nama ?? '-' }}</td></tr>
                            <tr><td class="text-gray-500 font-bold py-1">Semester</td><td class="font-bold text-gray-800">: {{ $semesters->where('id', $semester_id)->first()->nama }}</td></tr>
                        </table>
                    </div>

                    <!-- Tabel Nilai -->
                    <table class="min-w-full border-collapse border border-gray-400 mb-8">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-400 px-4 py-2 text-left uppercase text-xs font-black w-12 text-center">No</th>
                                <th class="border border-gray-400 px-4 py-2 text-left uppercase text-xs font-black">Mata Pelajaran</th>
                                <th class="border border-gray-400 px-4 py-2 text-center uppercase text-xs font-black w-24">Nilai Tugas</th>
                                <th class="border border-gray-400 px-4 py-2 text-center uppercase text-xs font-black w-24">Nilai UTS</th>
                                <th class="border border-gray-400 px-4 py-2 text-center uppercase text-xs font-black w-24">Nilai UAS</th>
                                <th class="border border-gray-400 px-4 py-2 text-center uppercase text-xs font-black bg-indigo-50 w-32">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total_akhir = 0; @endphp
                            @forelse($raport as $index => $row)
                                @php 
                                    // Bobot: Tugas 20%, UTS 30%, UAS 50%
                                    $akhir = ($row['tugas'] * 0.2) + ($row['uts'] * 0.3) + ($row['uas'] * 0.5);
                                    $total_akhir += $akhir;
                                    
                                    // Penentuan Predikat sederhana
                                    $predikat = 'A';
                                    if ($akhir < 60) $predikat = 'D';
                                    elseif ($akhir < 75) $predikat = 'C';
                                    elseif ($akhir < 85) $predikat = 'B';
                                @endphp
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $index + 1 }}</td>
                                    <td class="border border-gray-400 px-4 py-2 font-bold">{{ $row['mata_pelajaran'] }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ number_format($row['tugas'], 2) }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ number_format($row['uts'], 2) }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ number_format($row['uas'], 2) }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-black bg-indigo-50">{{ number_format($akhir, 2) }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-black bg-indigo-50 {{ $predikat == 'D' ? 'text-red-600' : '' }}">{{ $predikat }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="border border-gray-400 px-4 py-8 text-center text-gray-500 italic">Belum ada mata pelajaran / nilai untuk semester ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($raport) > 0)
                        <tfoot>
                            <tr class="bg-gray-100">
                                <th colspan="5" class="border border-gray-400 px-4 py-3 text-right uppercase text-xs font-black">Rata-Rata Kelas</th>
                                <th class="border border-gray-400 px-4 py-3 text-center font-black text-lg text-indigo-700 bg-indigo-100">
                                    {{ number_format($total_akhir / count($raport), 2) }}
                                </th>
                                <th class="border border-gray-400"></th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>

                    <!-- Rekap Absensi -->
                    <div class="mt-8 mb-8 w-1/3">
                        <table class="min-w-full border-collapse border border-gray-400">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th colspan="2" class="border border-gray-400 px-4 py-2 text-center uppercase text-xs font-black">Ketidakhadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-1 font-bold text-gray-700 text-sm">Sakit</td>
                                    <td class="border border-gray-400 px-4 py-1 text-center font-bold">{{ $rekap_absensi['Sakit'] }} hari</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-1 font-bold text-gray-700 text-sm">Izin</td>
                                    <td class="border border-gray-400 px-4 py-1 text-center font-bold">{{ $rekap_absensi['Izin'] }} hari</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-1 font-bold text-gray-700 text-sm">Tanpa Keterangan (Alpha)</td>
                                    <td class="border border-gray-400 px-4 py-1 text-center font-bold text-red-600">{{ $rekap_absensi['Alpha'] }} hari</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tanda Tangan -->
                    <div class="mt-16 flex justify-between px-12">
                        <div class="text-center">
                            <p class="mb-20 text-sm text-gray-600">Orang Tua / Wali Santri</p>
                            <p class="font-bold border-t border-gray-800 pt-1 w-48 mx-auto">( ...................................... )</p>
                        </div>
                        <div class="text-center">
                            <p class="mb-20 text-sm text-gray-600">Wali Kelas</p>
                            <p class="font-bold border-t border-gray-800 pt-1 w-48 mx-auto">( ...................................... )</p>
                        </div>
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
