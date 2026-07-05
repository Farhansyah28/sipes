@extends('portal.layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-emerald-500 rounded-3xl p-8 mb-10 overflow-hidden shadow-xl border border-emerald-600">
    <div class="absolute inset-0 bg-slate-50 opacity-5"></div>
    <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500 rounded-full blur-3xl opacity-20"></div>
    <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-amber-500 rounded-full blur-3xl opacity-10"></div>
    <div class="relative z-10">
        <h1 class="text-3xl font-extrabold text-slate-50 tracking-tight">Halo, {{ $orangTua->nama_ayah ?? $orangTua->nama_ibu ?? 'Bapak/Ibu' }} 👋</h1>
        <p class="text-emerald-50 mt-2 text-lg font-medium max-w-lg">Pantau perkembangan akademik, tagihan, dan uang saku anak Anda secara *real-time* dari genggaman.</p>
    </div>
</div>

@if(isset($total_tunggakan) && $total_tunggakan > 0)
<!-- Alert Tunggakan Premium -->
<div class="bg-amber-500 rounded-2xl p-5 mb-10 shadow-lg shadow-amber-500/20 flex items-start text-white transform transition-transform hover:scale-[1.01]">
    <div class="flex-shrink-0 bg-white/20 p-3 rounded-xl backdrop-blur-sm">
        <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>
    <div class="ml-4">
        <h3 class="text-lg font-bold text-slate-900">Perhatian: Ada Tunggakan</h3>
        <p class="text-amber-900 mt-1">Terdapat akumulasi tagihan sebesar <strong class="text-slate-900 text-xl ml-1">Rp {{ number_format($total_tunggakan, 0, ',', '.') }}</strong> yang menunggu pelunasan. Silakan cek menu Keuangan.</p>
    </div>
</div>
@endif

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-900 flex items-center">
        <span class="bg-emerald-500/10 text-emerald-500 p-2 rounded-lg mr-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </span>
        Profil Anak & Ringkasan
    </h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    @forelse($anak ?? [] as $santri)
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgb(0,0,0,0.05)] border border-slate-100 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300">
            <!-- Header Card -->
            <div class="relative h-28 bg-slate-900 overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500 rounded-full blur-2xl opacity-30"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-amber-500 rounded-full blur-2xl opacity-20"></div>
                
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ ($santri->status ?? 'Aktif') == 'Aktif' ? 'bg-white text-emerald-500' : 'bg-slate-100 text-slate-600' }}">
                        @if(($santri->status ?? 'Aktif') == 'Aktif') <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> @endif
                        {{ $santri->status ?? 'Aktif' }}
                    </span>
                </div>
            </div>
            
            <div class="relative pt-12 pb-6 px-6 bg-white">
                <!-- Avatar Overlap -->
                <div class="absolute -top-12 left-6">
                    <div class="w-20 h-20 rounded-2xl bg-white p-1 shadow-lg border border-slate-100">
                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center font-bold text-3xl shadow-inner">
                            {{ substr($santri->nama_lengkap ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>

                <!-- Info Basic Santri -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-2xl leading-tight">{{ $santri->nama_lengkap ?? 'Nama Santri' }}</h3>
                        <p class="text-sm font-semibold text-slate-500 mt-1">NIS: {{ $santri->nis ?? '12345678' }} • Kelas {{ $santri->kelas->nama ?? 'VII-A' }}</p>
                    </div>
                </div>
                
                <!-- 4 KOTAK WIDGET: Tabungan, Tagihan, Asrama, Akademik -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- Widget Tabungan (Fase 2 & 3) -->
                    <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100">
                        <div class="flex items-center text-emerald-600 mb-1">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Uang Saku</span>
                        </div>
                        <h4 class="text-xl font-extrabold text-slate-900">Rp {{ number_format($santri->tabungan->saldo ?? 150000, 0, ',', '.') }}</h4>
                        <p class="text-[10px] font-medium text-emerald-600 mt-1 mt-1">Tarik hari ini: Rp {{ number_format($santri->tarik_hari_ini ?? 0, 0, ',', '.') }}</p>
                    </div>

                    <!-- Widget Tagihan (Fase 2) -->
                    <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                        <div class="flex items-center text-amber-600 mb-1">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Tunggakan</span>
                        </div>
                        <h4 class="text-xl font-extrabold {{ ($santri->tunggakan ?? 500000) > 0 ? 'text-amber-600' : 'text-slate-900' }}">Rp {{ number_format($santri->tunggakan ?? 500000, 0, ',', '.') }}</h4>
                        <p class="text-[10px] font-medium text-amber-600 mt-1">Batas Bayar: 10 Bulan ini</p>
                    </div>
                    
                    <!-- Widget Asrama (Fase 1) -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center text-slate-600 mb-1">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Asrama</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900">{{ $santri->kamar->nama ?? 'Kamar Abu Bakar' }}</h4>
                        <p class="text-[10px] font-medium text-slate-500 mt-1 flex items-center">
                            Musyrif: {{ $santri->kamar->musyrif->nama ?? 'Ust. Fulan' }}
                        </p>
                    </div>

                    <!-- Widget Akademik (Fase 1) -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center text-slate-600 mb-1">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Kehadiran (Hari Ini)</span>
                        </div>
                        <h4 class="text-sm font-bold text-emerald-600 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Hadir
                        </h4>
                        <p class="text-[10px] font-medium text-slate-500 mt-1">Check-in Pukul 07:15 WIB</p>
                    </div>
                </div>
                
                <!-- Action Buttons (Menu Lanjutan) -->
                <div class="grid grid-cols-3 gap-3 pt-2 border-t border-slate-100">
                    <a href="{{ route('portal.akademik', $santri->id ?? 1) }}" class="flex flex-col items-center justify-center py-3 rounded-2xl bg-white hover:bg-emerald-500 hover:text-white text-slate-600 transition-all duration-300 group hover:shadow-lg hover:shadow-emerald-500/30 border border-slate-100 hover:border-transparent">
                        <svg class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="text-[11px] font-bold">Detail Akademik</span>
                    </a>
                    
                    <a href="{{ route('portal.keuangan', $santri->id ?? 1) }}" class="flex flex-col items-center justify-center py-3 rounded-2xl bg-white hover:bg-amber-500 hover:text-slate-900 text-slate-600 transition-all duration-300 group hover:shadow-lg hover:shadow-amber-500/30 border border-slate-100 hover:border-transparent">
                        <svg class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                        <span class="text-[11px] font-bold">Rincian Tagihan</span>
                    </a>
                    
                    <a href="{{ route('portal.tabungan', $santri->id ?? 1) }}" class="flex flex-col items-center justify-center py-3 rounded-2xl bg-white hover:bg-emerald-700 hover:text-white text-slate-600 transition-all duration-300 group hover:shadow-lg hover:shadow-emerald-700/30 border border-slate-100 hover:border-transparent">
                        <svg class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[11px] font-bold">Mutasi Kantin</span>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <!-- State jika belum punya anak yang masuk Dummy Array -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgb(0,0,0,0.05)] border border-slate-100 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300">
            <div class="relative h-28 bg-slate-900 overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500 rounded-full blur-2xl opacity-30"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-amber-500 rounded-full blur-2xl opacity-20"></div>
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm bg-white text-emerald-500">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Aktif
                    </span>
                </div>
            </div>
            <div class="relative pt-12 pb-6 px-6 bg-white">
                <div class="absolute -top-12 left-6">
                    <div class="w-20 h-20 rounded-2xl bg-white p-1 shadow-lg border border-slate-100">
                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center font-bold text-3xl shadow-inner">F</div>
                    </div>
                </div>
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-2xl leading-tight">Fulan bin Fulan</h3>
                        <p class="text-sm font-semibold text-slate-500 mt-1">NIS: 12345678 • Kelas VII-A</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100">
                        <div class="flex items-center text-emerald-600 mb-1">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Uang Saku</span>
                        </div>
                        <h4 class="text-xl font-extrabold text-slate-900">Rp 150.000</h4>
                        <p class="text-[10px] font-medium text-emerald-600 mt-1">Tarik hari ini: Rp 15.000</p>
                    </div>
                    <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                        <div class="flex items-center text-amber-600 mb-1">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Tunggakan</span>
                        </div>
                        <h4 class="text-xl font-extrabold text-amber-600">Rp 500.000</h4>
                        <p class="text-[10px] font-medium text-amber-600 mt-1">Batas Bayar: 10 Bulan ini</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center text-slate-600 mb-1">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Asrama</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900">Kamar Abu Bakar</h4>
                        <p class="text-[10px] font-medium text-slate-500 mt-1 flex items-center">Musyrif: Ust. Fulan</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center text-slate-600 mb-1">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Kehadiran (Hari Ini)</span>
                        </div>
                        <h4 class="text-sm font-bold text-emerald-600 flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Hadir</h4>
                        <p class="text-[10px] font-medium text-slate-500 mt-1">Check-in Pukul 07:15 WIB</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3 pt-2 border-t border-slate-100">
                    <a href="{{ route('portal.akademik', 1) }}" class="flex flex-col items-center justify-center py-3 rounded-2xl bg-white hover:bg-emerald-500 hover:text-white text-slate-600 transition-all duration-300 group hover:shadow-lg hover:shadow-emerald-500/30 border border-slate-100 hover:border-transparent">
                        <svg class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="text-[11px] font-bold">Detail Akademik</span>
                    </a>
                    <a href="{{ route('portal.keuangan', 1) }}" class="flex flex-col items-center justify-center py-3 rounded-2xl bg-white hover:bg-amber-500 hover:text-slate-900 text-slate-600 transition-all duration-300 group hover:shadow-lg hover:shadow-amber-500/30 border border-slate-100 hover:border-transparent">
                        <svg class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                        <span class="text-[11px] font-bold">Rincian Tagihan</span>
                    </a>
                    <a href="{{ route('portal.tabungan', 1) }}" class="flex flex-col items-center justify-center py-3 rounded-2xl bg-white hover:bg-emerald-700 hover:text-white text-slate-600 transition-all duration-300 group hover:shadow-lg hover:shadow-emerald-700/30 border border-slate-100 hover:border-transparent">
                        <svg class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[11px] font-bold">Mutasi Kantin</span>
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection
