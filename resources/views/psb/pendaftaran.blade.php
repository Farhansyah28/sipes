<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Santri Baru - SIPES</title>
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
    <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 border-[40px] border-emerald-700/20 rounded-full pointer-events-none blur-sm"></div>
    <div class="absolute bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 border-[40px] border-emerald-700/20 rounded-full pointer-events-none blur-sm"></div>
    
    <div class="w-full max-w-3xl mx-auto glass-card rounded-[2rem] shadow-2xl shadow-emerald-950/50 border border-emerald-400/20 overflow-hidden relative z-10">
        
        <!-- Header Section -->
        <div class="px-8 pt-10 pb-6 text-center border-b border-emerald-500/20 bg-emerald-800/40">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-500/20 text-emerald-300 mb-4 ring-1 ring-emerald-400/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Formulir Pendaftaran</h2>
            <p class="mt-2 text-emerald-200 font-medium">Penerimaan Santri Baru (PSB)</p>
        </div>
        
        <div class="px-6 sm:px-10 py-8">
            @if (session('success'))
                <div class="bg-emerald-500/20 border border-emerald-400/50 p-4 mb-8 rounded-2xl shadow-sm flex items-start backdrop-blur-sm">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="h-5 w-5 text-emerald-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-emerald-100 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('psb.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Section 1: Data Diri -->
                <div class="bg-emerald-900/40 rounded-2xl p-6 sm:p-8 border border-emerald-500/20 shadow-inner">
                    <div class="flex items-center mb-6 pb-4 border-b border-emerald-500/30">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500 text-white font-bold text-sm mr-3 shadow-md">1</div>
                        <h3 class="text-lg font-bold text-white">Data Calon Santri</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-y-5 gap-x-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-emerald-100 mb-1">Nama Lengkap Sesuai Akte</label>
                            <input type="text" name="nama_lengkap" required class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-emerald-100 mb-1">NISN (Opsional)</label>
                            <input type="text" name="nisn" class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-emerald-100 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm">
                                <option value="" disabled selected>Pilih...</option>
                                <option value="L">Laki-laki (Putra)</option>
                                <option value="P">Perempuan (Putri)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-emerald-100 mb-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" required class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-emerald-100 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" required class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Data Orang Tua -->
                <div class="bg-emerald-900/40 rounded-2xl p-6 sm:p-8 border border-emerald-500/20 shadow-inner">
                    <div class="flex items-center mb-6 pb-4 border-b border-emerald-500/30">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500 text-white font-bold text-sm mr-3 shadow-md">2</div>
                        <h3 class="text-lg font-bold text-white">Data Orang Tua / Wali</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-y-5 gap-x-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-emerald-100 mb-1">Nama Ayah</label>
                            <input type="text" name="nama_ayah" required class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-emerald-100 mb-1">Nama Ibu</label>
                            <input type="text" name="nama_ibu" required class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-emerald-100 mb-1">Nomor WhatsApp / HP Ayah</label>
                            <input type="text" name="no_hp_ayah" required class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-emerald-100 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" required class="block w-full input-glass border-transparent rounded-xl py-2.5 px-4 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all shadow-sm"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Berkas -->
                <div class="bg-emerald-900/40 rounded-2xl p-6 sm:p-8 border border-emerald-500/20 shadow-inner">
                    <div class="flex items-center mb-6 pb-4 border-b border-emerald-500/30">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500 text-white font-bold text-sm mr-3 shadow-md">3</div>
                        <h3 class="text-lg font-bold text-white">Berkas Persyaratan</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="group relative border-2 border-dashed border-emerald-400/40 hover:border-emerald-300 rounded-2xl p-6 bg-emerald-950/30 hover:bg-emerald-900/50 text-center transition-all">
                            <label class="cursor-pointer flex flex-col items-center">
                                <div class="w-12 h-12 mb-3 text-emerald-200 bg-emerald-800 rounded-full shadow-md flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-700 transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </div>
                                <span class="block text-sm font-semibold text-emerald-50 mb-1">Scan Kartu Keluarga (KK)</span>
                                <span class="block text-xs text-emerald-300/70 mb-4">PDF, JPG, PNG (Maks 2MB)</span>
                                <input type="file" name="kk_file" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-emerald-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-500 file:text-white hover:file:bg-emerald-400 cursor-pointer transition-colors">
                            </label>
                        </div>

                        <div class="group relative border-2 border-dashed border-emerald-400/40 hover:border-emerald-300 rounded-2xl p-6 bg-emerald-950/30 hover:bg-emerald-900/50 text-center transition-all">
                            <label class="cursor-pointer flex flex-col items-center">
                                <div class="w-12 h-12 mb-3 text-emerald-200 bg-emerald-800 rounded-full shadow-md flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-700 transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </div>
                                <span class="block text-sm font-semibold text-emerald-50 mb-1">Scan Akte Kelahiran</span>
                                <span class="block text-xs text-emerald-300/70 mb-4">PDF, JPG, PNG (Maks 2MB)</span>
                                <input type="file" name="akte_file" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-emerald-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-500 file:text-white hover:file:bg-emerald-400 cursor-pointer transition-colors">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex flex-col items-center">
                    <button type="submit" class="w-full sm:w-auto px-12 py-4 rounded-xl shadow-lg shadow-emerald-900/50 text-lg font-bold text-emerald-900 bg-emerald-400 hover:bg-emerald-300 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-emerald-400/50 transition-all duration-200">
                        Kirim Formulir Pendaftaran
                    </button>
                    <p class="text-center text-xs text-emerald-200/70 mt-4 max-w-sm">
                        Dengan mengirimkan formulir ini, Anda menyatakan bahwa seluruh data yang diisi adalah benar dan valid.
                    </p>
                </div>

                <div class="mt-8 text-center border-t border-emerald-500/20 pt-6">
                    <p class="text-sm text-emerald-100">Sudah mendaftar?</p>
                    <a href="{{ route('psb.cek_status') }}" class="inline-flex items-center mt-2 text-emerald-300 font-bold hover:text-white transition-colors">
                        Cek Status Pendaftaran Anda
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
