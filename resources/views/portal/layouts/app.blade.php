<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Orang Tua - SIPES</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="text-slate-900 antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-emerald-600 text-white sticky top-0 z-50 shadow-md">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="bg-emerald-500 p-2 rounded-lg mr-3 shadow-sm">
                        <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-50">SIPES Portal</span>
                </div>
                <div class="flex items-center">
                    <form method="POST" action="{{ route('portal.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-white bg-white/10 hover:bg-amber-500 hover:text-slate-900 px-4 py-2 rounded-full transition-all duration-300">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="w-full px-4 sm:px-6 lg:px-8 py-8 flex-grow pb-24">
        @yield('content')
    </main>

    <!-- Bottom Navigation (Mobile Friendly) -->
    <div class="fixed bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl flex justify-around py-3 sm:hidden shadow-lg z-50">
        <a href="{{ route('portal.dashboard') }}" class="flex flex-col items-center group {{ request()->routeIs('portal.dashboard') ? 'text-emerald-500' : 'text-slate-400 hover:text-emerald-700' }}">
            <div class="{{ request()->routeIs('portal.dashboard') ? 'bg-emerald-50' : 'group-hover:bg-slate-50' }} p-1.5 rounded-full transition-colors duration-200 mb-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Beranda</span>
        </a>
        
        <!-- Back button logic if inside a santri view -->
        @if(request()->routeIs('portal.akademik', 'portal.keuangan', 'portal.tabungan'))
            <a href="javascript:history.back()" class="flex flex-col items-center group text-slate-400 hover:text-emerald-500 transition-colors">
                <div class="group-hover:bg-emerald-50 p-1.5 rounded-full transition-colors duration-200 mb-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="text-[10px] font-bold">Kembali</span>
            </a>
        @endif
    </div>
</body>
</html>
