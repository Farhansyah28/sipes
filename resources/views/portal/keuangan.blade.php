@extends('portal.layouts.app')

@section('content')
<div class="mb-6">
    <div class="bg-emerald-500 rounded-3xl p-6 text-slate-50 shadow-xl flex items-center justify-between relative overflow-hidden border border-emerald-600">
        <div class="absolute inset-0 bg-slate-50 opacity-5"></div>
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-amber-500 rounded-full blur-2xl opacity-20"></div>
        <div class="relative z-10">
            <p class="text-amber-500 text-sm font-medium uppercase tracking-wider mb-1">Status Keuangan</p>
            <h1 class="text-2xl font-extrabold">{{ $santri->nama_lengkap }}</h1>
            <p class="text-emerald-50 mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                SPP & Administrasi
            </p>
        </div>
        <div class="hidden sm:block relative z-10 bg-slate-50/10 p-4 rounded-2xl backdrop-blur-sm border border-white/5">
            <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-[0_4px_20px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-12">
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center">
        <div class="bg-amber-500/10 text-amber-500 p-2 rounded-lg mr-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h2 class="text-lg font-extrabold text-slate-900">Daftar Tagihan Berjalan</h2>
    </div>
    
    <div class="p-6">
        @if($tagihans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tagihans as $tag)
                <div class="rounded-2xl p-5 relative transition-all duration-300 border-2 {{ $tag->status == 'Lunas' ? 'border-emerald-500/20 bg-emerald-500/5 hover:border-emerald-500/40' : 'border-rose-100 bg-rose-50/30 hover:border-rose-300' }}">
                    
                    @if($tag->status == 'Lunas')
                        <div class="absolute top-5 right-5 bg-emerald-500/10 p-1.5 rounded-full text-emerald-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    @else
                        <div class="absolute top-5 right-5 bg-rose-100 p-1.5 rounded-full text-rose-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @endif

                    <h3 class="font-extrabold text-slate-900 text-lg pr-10 leading-tight">{{ $tag->kategoriTagihan->nama ?? '-' }}</h3>
                    <div class="inline-flex items-center mt-2 px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase {{ $tag->status == 'Lunas' ? 'bg-emerald-500/10 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        Tempo: {{ \Carbon\Carbon::parse($tag->tanggal_jatuh_tempo)->translatedFormat('d M Y') }}
                    </div>
                    
                    <div class="mt-5 pt-4 border-t border-slate-200/60 border-dashed">
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="text-slate-500 font-medium">Total Tagihan</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($tag->nominal_tagihan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="text-slate-500 font-medium">Dibayar</span>
                            <span class="font-bold text-emerald-500">Rp {{ number_format($tag->nominal_dibayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Sisa Tunggakan</span>
                            <span class="text-lg font-extrabold {{ $tag->status == 'Lunas' ? 'text-emerald-500' : 'text-rose-600' }}">Rp {{ number_format($tag->sisa_tagihan, 0, ',', '.') }}</span>
                        </div>

                        @if($tag->status != 'Lunas')
                        <!-- Form Upload Bukti Bayar -->
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <form action="{{ route('portal.tagihan.bayar', $tag->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Upload Bukti Transfer</label>
                                    <input type="file" name="bukti_pembayaran" required accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                </div>
                                <div class="mb-3">
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nominal Transfer (Rp)</label>
                                    <input type="number" name="nominal" required min="1000" max="{{ $tag->sisa_tagihan }}" value="{{ $tag->sisa_tagihan }}" class="w-full rounded-lg border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <button type="submit" class="w-full bg-slate-900 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded-xl text-sm transition-colors shadow-md">
                                    Kirim Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center flex flex-col items-center">
                <div class="bg-slate-50 p-4 rounded-full mb-3 text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-700">Tidak ada tagihan</h3>
                <p class="text-slate-500 text-sm mt-1">Belum ada tagihan yang diterbitkan untuk santri ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
