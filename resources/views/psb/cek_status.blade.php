<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran - SIPES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .input-glass {
            background: rgba(255, 255, 255, 0.9);
        }
        .input-glass:focus {
            background: #ffffff;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 min-h-screen py-10 px-4 sm:px-6 lg:px-8 flex items-center justify-center relative overflow-x-hidden">
    
    <!-- Decorative Islamic Geometry Hints -->
    <div class="absolute top-0 left-0 -ml-32 -mt-32 w-96 h-96 border-[40px] border-emerald-700/20 rounded-full pointer-events-none blur-sm"></div>
    <div class="absolute bottom-0 right-0 -mr-32 -mb-32 w-96 h-96 border-[40px] border-emerald-700/20 rounded-full pointer-events-none blur-sm"></div>
    
    <div class="w-full max-w-md mx-auto glass-card rounded-[2rem] shadow-2xl shadow-emerald-950/50 border border-emerald-400/20 overflow-hidden relative z-10">
        
        <!-- Header Section -->
        <div class="px-8 pt-10 pb-6 text-center border-b border-emerald-500/20 bg-emerald-800/40">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-500/20 text-emerald-300 mb-4 ring-1 ring-emerald-400/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Cek Status</h2>
            <p class="mt-2 text-emerald-200 font-medium">Pendaftaran Santri Baru</p>
        </div>
        
        <div class="px-6 sm:px-10 py-8">
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-400/50 text-red-100 px-4 py-3 rounded-xl shadow-sm mb-6 flex items-start backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('psb.cek_status.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="bg-emerald-900/40 rounded-2xl p-6 border border-emerald-500/20 shadow-inner space-y-4">
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-emerald-100 mb-2">NISN Calon Santri</label>
                        <input type="text" name="nisn" id="nisn" class="block w-full input-glass border-transparent rounded-xl py-3 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm text-lg text-center tracking-wider" required placeholder="Masukkan NISN">
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-emerald-100 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="block w-full input-glass border-transparent rounded-xl py-3 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm text-lg text-center" required>
                    </div>
                </div>
                
                <button type="submit" class="w-full px-8 py-4 rounded-xl shadow-lg shadow-emerald-900/50 text-lg font-bold text-emerald-900 bg-emerald-400 hover:bg-emerald-300 hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-emerald-400/50 transition-all duration-200">
                    Periksa Status
                </button>
            </form>

            @if(session('status_santri'))
                @php $santri = (object) session('status_santri'); @endphp
                <div class="mt-8 p-6 bg-emerald-950/60 border border-emerald-400/30 rounded-2xl shadow-inner relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
                    
                    <h3 class="text-sm uppercase tracking-widest text-emerald-300 font-bold mb-4 text-center">Hasil Pencarian</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex flex-col">
                            <span class="text-emerald-300/70 text-xs mb-1">Nama Lengkap</span>
                            <span class="font-bold text-white text-base">{{ $santri->nama_lengkap }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-emerald-300/70 text-xs mb-1">NISN</span>
                            <span class="font-bold text-emerald-100">{{ $santri->nisn }}</span>
                        </div>
                        <div class="pt-4 border-t border-emerald-500/20 mt-2">
                            <span class="block text-emerald-300/70 text-xs mb-2 text-center">Status Saat Ini</span>
                            <div class="flex justify-center">
                                <span class="px-6 py-2 text-sm font-black rounded-full uppercase tracking-wider
                                    @if($santri->status == 'Lulus' || $santri->status == 'Aktif') bg-emerald-400 text-emerald-950 shadow-[0_0_15px_rgba(52,211,153,0.5)]
                                    @elseif($santri->status == 'Tidak Lulus') bg-red-500 text-white shadow-[0_0_15px_rgba(239,68,68,0.5)]
                                    @else bg-yellow-400 text-yellow-950 shadow-[0_0_15px_rgba(250,204,21,0.5)] @endif">
                                    {{ $santri->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mt-8 text-center border-t border-emerald-500/20 pt-6">
                <a href="{{ route('psb.create') }}" class="inline-flex items-center text-sm text-emerald-200 font-medium hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Form Pendaftaran
                </a>
            </div>
        </div>
    </div>
</body>
</html>
