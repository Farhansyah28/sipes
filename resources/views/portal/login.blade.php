<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Orang Tua | SIPES</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet">

    <style>
        body{
            font-family:'Outfit',sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 via-white to-emerald-100 min-h-screen overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 overflow-hidden -z-10">

        <div class="absolute -top-40 -left-40 w-[450px] h-[450px] bg-green-300 rounded-full blur-[120px] opacity-20"></div>

        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-300 rounded-full blur-[140px] opacity-20"></div>

        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>

    </div>

<div class="min-h-screen flex items-center justify-center px-5">

<div class="max-w-6xl w-full grid lg:grid-cols-2 bg-white rounded-[40px] shadow-2xl overflow-hidden">

    <!-- LEFT -->
    <div class="hidden lg:flex relative bg-gradient-to-br from-green-600 to-emerald-500 text-white p-14 flex-col justify-between">

        <div>

            <div class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur flex items-center justify-center mb-8">

                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>

                </svg>

            </div>

            <h1 class="text-4xl font-extrabold leading-tight">

                Selamat Datang di
                <br>
                Portal Orang Tua

            </h1>

            <p class="mt-6 text-lg text-green-100 leading-8">

                Pantau perkembangan putra-putri Anda secara realtime.

                Mulai dari absensi, nilai akademik, hafalan, pembayaran hingga informasi dari pesantren.

            </p>

        </div>

        <div>

            <img
            src="https://storyset.com/illustration/family/cuate"
            class="w-full opacity-95">

        </div>

    </div>

    <!-- RIGHT -->

    <div class="p-8 lg:p-14 flex flex-col justify-center">

        <div class="lg:hidden text-center mb-8">

            <div class="mx-auto w-20 h-20 rounded-full bg-green-600 flex items-center justify-center text-white shadow-lg">

                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>

                </svg>

            </div>

        </div>

        <h2 class="text-3xl font-extrabold text-slate-800">

            Portal Orang Tua

        </h2>

        <p class="mt-2 text-slate-500">

            Silakan login menggunakan Nomor WhatsApp dan NIS Anak.

        </p>

        @if ($errors->any())

        <div class="mt-6 rounded-xl bg-red-50 border border-red-200 text-red-700 p-4">

            {{ $errors->first() }}

        </div>

        @endif

        <form action="{{ route('portal.login.post') }}" method="POST" class="mt-8 space-y-6">

            @csrf

            <div>

                <label class="font-semibold text-slate-700">

                    Nomor WhatsApp

                </label>

                <input
                    type="text"
                    name="no_hp"
                    required
                    placeholder="Contoh : 081234567890"
                    class="mt-2 w-full rounded-xl border border-green-200 bg-green-50 px-4 py-4 focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-500">

            </div>

            <div>

                <label class="font-semibold text-slate-700">

                    NIS / NISN Anak

                </label>

                <input
                    type="text"
                    name="nis"
                    required
                    placeholder="Masukkan Nomor Induk Santri"
                    class="mt-2 w-full rounded-xl border border-green-200 bg-green-50 px-4 py-4 focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-500">

            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 py-4 text-white font-bold text-lg shadow-lg hover:from-green-700 hover:to-emerald-600 transition duration-300">

                Masuk ke Portal

            </button>

        </form>

        <div class="mt-8 rounded-2xl border border-green-100 bg-green-50 p-5">

            <h4 class="font-bold text-green-700">

                Portal Aman & Privat

            </h4>

            <p class="text-sm text-slate-600 mt-2">

                Portal ini hanya dapat diakses oleh orang tua atau wali santri yang telah terdaftar di sistem.

            </p>

        </div>

        <div class="text-center mt-8">

            <a href="#" class="text-green-600 hover:text-green-700 font-semibold">

                Kesulitan Login? Hubungi Admin

            </a>

        </div>

        <div class="text-center mt-10 text-sm text-slate-400">

            © {{ date('Y') }} SIPES - Sistem Informasi Pesantren

        </div>

    </div>

</div>

</div>

</body>
</html>