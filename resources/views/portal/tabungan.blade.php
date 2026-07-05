@extends('portal.layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-emerald-500 rounded-3xl p-6 text-slate-50 shadow-xl flex items-center justify-between relative overflow-hidden border border-emerald-600">
        <div class="absolute inset-0 bg-slate-50 opacity-5"></div>
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-700 rounded-full blur-2xl opacity-30"></div>
        <div class="relative z-10">
            <p class="text-emerald-500 text-sm font-medium uppercase tracking-wider mb-1">Kantin & Uang Saku</p>
            <h1 class="text-2xl font-extrabold">{{ $santri->nama_lengkap }}</h1>
            <p class="text-emerald-50 mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Sisa Saldo & Riwayat Jajan
            </p>
        </div>
        <div class="hidden sm:block relative z-10 bg-slate-50/10 p-4 rounded-2xl backdrop-blur-sm border border-white/5">
            <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Kartu Saldo -->
    <div class="bg-emerald-500 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden lg:col-span-1 transform transition-transform hover:scale-[1.02] border border-emerald-700">
        <div class="absolute inset-0 bg-slate-50 opacity-10"></div>
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white rounded-full blur-3xl opacity-20"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-700 rounded-full blur-3xl opacity-30"></div>
        
        <div class="relative z-10 h-full flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-emerald-50 font-semibold text-sm uppercase tracking-widest">Saldo Virtual</h3>
                    <svg class="w-8 h-8 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                </div>
                <div class="text-4xl font-extrabold mb-2 tracking-tight text-slate-50">Rp {{ number_format($tabungan->saldo ?? 0, 0, ',', '.') }}</div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-white/20">
                <p class="text-xs text-emerald-100 font-medium leading-relaxed">Dana ini dapat digunakan untuk transaksi Kantin (POS) atau terpotong otomatis untuk SPP.</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Mutasi Topup/Tarik -->
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgb(0,0,0,0.04)] border border-slate-100 lg:col-span-2 overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center">
            <div class="bg-emerald-500/10 text-emerald-700 p-2 rounded-lg mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <h2 class="text-lg font-extrabold text-slate-900">Riwayat Top-Up & Penarikan</h2>
        </div>
        <div class="p-0 overflow-y-auto max-h-80 flex-1">
            @if($mutasis->count() > 0)
                <ul class="divide-y divide-slate-50">
                    @foreach($mutasis as $mutasi)
                    <li class="p-5 flex justify-between items-center hover:bg-slate-50/80 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center {{ $mutasi->jenis_mutasi == 'Topup' ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/20' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                @if($mutasi->jenis_mutasi == 'Topup')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-slate-900">{{ $mutasi->jenis_mutasi }}</p>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($mutasi->created_at)->translatedFormat('d M Y, H:i') }} • {{ $mutasi->keterangan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-base font-extrabold {{ $mutasi->jenis_mutasi == 'Topup' ? 'text-emerald-500' : 'text-rose-600' }}">
                            {{ $mutasi->jenis_mutasi == 'Topup' ? '+' : '-' }} Rp {{ number_format($mutasi->nominal, 0, ',', '.') }}
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="py-12 text-center flex flex-col items-center">
                    <div class="bg-slate-50 p-4 rounded-full mb-3 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada riwayat top-up atau penarikan.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Riwayat Jajan Kantin (Transaksi POS) -->
<div class="bg-white rounded-3xl shadow-[0_4px_20px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-12">
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center">
        <div class="bg-amber-500/10 text-amber-500 p-2 rounded-lg mr-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <h2 class="text-lg font-extrabold text-slate-900">Riwayat Jajan Kantin</h2>
    </div>
    <div class="p-6">
        @if($transaksi_pos->count() > 0)
            <div class="space-y-5">
                @foreach($transaksi_pos as $transaksi)
                <div class="border {{ $transaksi->status === 'Batal' ? 'border-rose-100 bg-rose-50/20' : 'border-slate-100 bg-slate-50/30' }} rounded-2xl p-5 hover:bg-slate-50 transition-colors">
                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-200/60 border-dashed">
                        <div>
                            <span class="inline-flex items-center px-2 py-1 rounded bg-slate-200 text-slate-600 text-[10px] font-bold tracking-wider uppercase mr-2">ID: #{{ $transaksi->id }}</span>
                            @if($transaksi->status === 'Batal')
                                <span class="inline-flex items-center px-2 py-1 rounded bg-rose-100 text-rose-600 text-[10px] font-bold tracking-wider uppercase mr-2">BATAL</span>
                            @endif
                            <span class="text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div class="text-base font-extrabold {{ $transaksi->status === 'Batal' ? 'text-slate-400 line-through' : 'text-rose-600 bg-rose-50 px-3 py-1 rounded-lg' }}">
                            - Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <ul class="text-sm space-y-2">
                            @foreach($transaksi->details as $detail)
                            <li class="flex justify-between items-center text-slate-700">
                                <div class="flex items-center {{ $transaksi->status === 'Batal' ? 'opacity-50' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span>
                                    <span class="font-medium {{ $transaksi->status === 'Batal' ? 'line-through' : '' }}">{{ $detail->barang->nama ?? 'Item' }}</span> 
                                    <span class="ml-2 text-[10px] font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">x{{ $detail->qty }}</span>
                                </div>
                                <span class="font-bold {{ $transaksi->status === 'Batal' ? 'text-slate-400 line-through' : 'text-slate-900' }}">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center flex flex-col items-center">
                <div class="bg-slate-50 p-4 rounded-full mb-3 text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-700">Belum ada riwayat</h3>
                <p class="text-slate-500 text-sm mt-1">Anak ini belum melakukan pembelian di kantin.</p>
            </div>
        @endif
    </div>
</div>
@endsection
