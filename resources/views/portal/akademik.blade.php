@extends('portal.layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-emerald-500 rounded-3xl p-6 text-slate-50 shadow-xl flex items-center justify-between relative overflow-hidden border border-emerald-600">
        <div class="absolute inset-0 bg-slate-50 opacity-5"></div>
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500 rounded-full blur-2xl opacity-20"></div>
        <div class="relative z-10">
            <p class="text-emerald-500 text-sm font-medium uppercase tracking-wider mb-1">Pusat Akademik</p>
            <h1 class="text-2xl font-extrabold">{{ $santri->nama_lengkap }}</h1>
            <p class="text-emerald-50 mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                NIS: {{ $santri->nis }}
            </p>
        </div>
        <div class="hidden sm:block relative z-10 bg-slate-50/10 p-4 rounded-2xl backdrop-blur-sm border border-white/5">
            <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
    </div>
</div>

<div class="space-y-8">
    <!-- Riwayat Absensi -->
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center">
            <div class="bg-emerald-500/10 text-emerald-500 p-2 rounded-lg mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-lg font-extrabold text-slate-900">Riwayat Absensi (30 Hari Terakhir)</h2>
        </div>
        <div class="p-0 overflow-x-auto">
            @if($absensis->count() > 0)
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($absensis as $ab)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ \Carbon\Carbon::parse($ab->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ab->status == 'Hadir')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">Hadir</span>
                                @elseif($ab->status == 'Sakit')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20">Sakit</span>
                                @elseif($ab->status == 'Izin')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-200">Izin</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-rose-100 text-rose-700 border border-rose-200">Alpa</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $ab->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-12 text-center flex flex-col items-center">
                    <div class="bg-slate-50 p-4 rounded-full mb-3 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada riwayat absensi tercatat.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Riwayat Nilai Raport -->
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center">
            <div class="bg-amber-500/10 text-amber-500 p-2 rounded-lg mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h2 class="text-lg font-extrabold text-slate-900">Riwayat Nilai Pelajaran</h2>
        </div>
        <div class="p-0 overflow-x-auto">
            @if($nilais->count() > 0)
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Semester</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($nilais as $nl)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">{{ $nl->jadwal->semester->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-bold">{{ $nl->jadwal->mataPelajaran->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-lg font-extrabold {{ $nl->nilai < 75 ? 'text-rose-600' : 'text-emerald-500' }}">{{ $nl->nilai }}</span>
                                    @if($nl->nilai < 75)
                                        <svg class="w-4 h-4 ml-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $nl->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-12 text-center flex flex-col items-center">
                    <div class="bg-slate-50 p-4 rounded-full mb-3 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada nilai tercatat.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
