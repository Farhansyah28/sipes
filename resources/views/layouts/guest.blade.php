<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ERP Pesantren') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-emerald-950 selection:bg-emerald-500 selection:text-white" style="font-family: 'Inter', sans-serif;">
        
        <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950">
            <!-- Background Decorations -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px]"></div>
                <div class="absolute top-[60%] -right-[10%] w-[60%] h-[60%] rounded-full bg-emerald-400/10 blur-[120px]"></div>
            </div>

            <!-- Login Container -->
            <div class="relative z-10 w-full max-w-md px-6 py-12 md:px-8">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex flex-col items-center group">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 mb-3 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-black text-white tracking-tight">SIPES</h1>
                        <p class="text-emerald-400/80 text-sm font-semibold tracking-wider uppercase mt-1">ERP Pesantren</p>
                    </a>
                </div>

                <!-- Form Card -->
                <div class="bg-white/10 backdrop-blur-xl border border-white/10 p-8 rounded-3xl shadow-2xl">
                    {{ $slot }}
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-emerald-500/50 text-xs font-medium">© {{ date('Y') }} SIPES. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </body>
</html>
