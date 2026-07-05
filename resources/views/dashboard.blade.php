<x-app-layout>
    <div class="pt-8 pb-12 bg-gray-50 min-h-screen">
        <div class="w-full sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Panel -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-800 rounded-3xl shadow-lg overflow-hidden relative">
                <div class="absolute inset-0 bg-white opacity-5 pattern-grid"></div>
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-400 rounded-full blur-3xl opacity-30"></div>
                <div class="relative p-8 md:p-10 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Selamat Datang di SIPES! 🚀</h2>
                        <p class="text-emerald-100 text-lg max-w-2xl">Pusat kendali Sistem Informasi Pesantren. Pantau aktivitas pendaftaran, akademik, arus kas, hingga transaksi kantin secara *real-time*.</p>
                    </div>
                    <div class="hidden lg:block text-right">
                        <p class="text-sm text-emerald-200 font-bold uppercase tracking-wider mb-1">Tahun Ajaran</p>
                        <p class="text-2xl font-bold bg-white/20 px-4 py-1 rounded-lg backdrop-blur-sm">{{ $tahun_ajaran_aktif ?? '2026/2027 Ganjil' }}</p>
                    </div>
                </div>
            </div>

            <!-- Akademik, Asrama & PSB -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Akademik, Asrama & Pendaftaran
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Santri Aktif -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500">Santri Aktif</h4>
                            <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-extrabold text-gray-900">{{ number_format($total_santri_aktif ?? 1250) }}</p>
                        <p class="text-xs text-green-600 mt-2 font-medium">â†‘ 45 santri baru bulan ini</p>
                    </div>

                    <!-- PSB -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500">Pendaftar PSB</h4>
                            <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-extrabold text-gray-900">{{ number_format($total_psb ?? 320) }}</p>
                        <p class="text-xs text-amber-600 mt-2 font-medium">{{ $psb_menunggu ?? 45 }} Menunggu Verifikasi</p>
                    </div>

                    <!-- Asrama -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500">Kapasitas Asrama</h4>
                            <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $kamar_terisi ?? 85 }}% <span class="text-sm font-normal text-gray-400">Terisi</span></p>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2.5">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>

                    <!-- Absensi Hari Ini -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500">Kehadiran (Hari Ini)</h4>
                            <div class="p-2 rounded-lg bg-rose-50 text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $absensi_hadir ?? 98 }}%</p>
                        <p class="text-xs text-rose-600 mt-2 font-medium">{{ $absensi_alpa ?? 12 }} Santri Alpa/Sakit</p>
                    </div>
                </div>
            </div>

            <!-- Keuangan, Tabungan, Kantin -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Keuangan, Tabungan & Kantin
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Pemasukan Kas -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500">Pemasukan (Bulan Ini)</h4>
                            <div class="p-2 rounded-lg bg-green-50 text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                            </div>
                        </div>
                        <p class="text-xl font-extrabold text-gray-900">Rp {{ number_format($kas_bulan_ini ?? 125000000, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-2 font-medium">Dari SPP & Uang Pangkal</p>
                    </div>

                    <!-- Tunggakan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500">Total Tunggakan</h4>
                            <div class="p-2 rounded-lg bg-amber-50 text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                        </div>
                        <p class="text-xl font-extrabold text-amber-600">Rp {{ number_format($tunggakan_aktif ?? 45000000, 0, ',', '.') }}</p>
                        <p class="text-xs text-amber-600 mt-2 font-medium">Dari {{ $santri_menunggak ?? 120 }} santri aktif</p>
                    </div>

                    <!-- Saldo Tabungan Mengendap -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500">Tabungan Mengendap</h4>
                            <div class="p-2 rounded-lg bg-purple-50 text-purple-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                        </div>
                        <p class="text-xl font-extrabold text-gray-900">Rp {{ number_format($total_tabungan ?? 350000000, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-2 font-medium">Dana titipan wali santri</p>
                    </div>

                    <!-- Omzet Kantin Hari Ini -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-500">Omzet Kantin (Hari Ini)</h4>
                            <div class="p-2 rounded-lg bg-cyan-50 text-cyan-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                        </div>
                        <p class="text-xl font-extrabold text-cyan-600">Rp {{ number_format($omzet_kantin ?? 4500000, 0, ',', '.') }}</p>
                        <p class="text-xs text-cyan-600 mt-2 font-medium">{{ $transaksi_kantin ?? 342 }} transaksi POS sukses</p>
                    </div>
                </div>
            </div>

            <!-- Portal, Notifikasi & Infrastruktur -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Log Aktivitas & Notifikasi Operasional -->
                @php
                    $alert_count = ($alert_pembayaran > 0 ? 1 : 0) + ($alert_stok > 0 ? 1 : 0) + ($ortu_login_hari_ini > 0 ? 1 : 0);
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Menunggu Tindakan (Action Required)</h3>
                        @if($alert_count > 0)
                            <span class="bg-rose-100 text-rose-600 text-xs font-bold px-2.5 py-1 rounded-full">{{ $alert_count }} Alerts</span>
                        @else
                            <span class="bg-emerald-100 text-emerald-600 text-xs font-bold px-2.5 py-1 rounded-full">Clear</span>
                        @endif
                    </div>
                    <div class="divide-y divide-gray-50">
                        @if($alert_count == 0)
                        <!-- Empty State -->
                        <div class="p-8 flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Semua Terkendali!</p>
                            <p class="text-xs text-gray-500 mt-1">Tidak ada tindakan mendesak atau peringatan sistem saat ini. Anda bisa bersantai sejenak â˜•.</p>
                        </div>
                        @else
                            <!-- Alert 1: Pembayaran -->
                            @if($alert_pembayaran > 0)
                            <div class="p-4 hover:bg-gray-50 transition flex items-start">
                                <div class="w-2 h-2 mt-1.5 rounded-full bg-amber-500 mr-3"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $alert_pembayaran }} Pembayaran Manual Menunggu Verifikasi</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Wali santri telah mengunggah bukti transfer. <a href="#" class="text-emerald-600 hover:underline">Verifikasi sekarang</a></p>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Alert 2: Stok Menipis -->
                            @if($alert_stok > 0)
                            <div class="p-4 hover:bg-gray-50 transition flex items-start">
                                <div class="w-2 h-2 mt-1.5 rounded-full bg-rose-500 mr-3"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $alert_stok }} Barang Kantin Stok Menipis</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Sisa stok telah berada di bawah batas minimum. <a href="#" class="text-emerald-600 hover:underline">Lihat laporan FIFO</a></p>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Alert 3: Traffic Portal -->
                            @if($ortu_login_hari_ini > 0)
                            <div class="p-4 hover:bg-gray-50 transition flex items-start">
                                <div class="w-2 h-2 mt-1.5 rounded-full bg-blue-500 mr-3"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $ortu_login_hari_ini }} Orang Tua mengakses Portal hari ini</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Lonjakan traffic Portal Wali Santri (Fase 4).</p>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Sistem Gateway -->
                <div class="bg-slate-900 rounded-2xl shadow-sm border border-slate-800 overflow-hidden text-white">
                    <div class="p-5 border-b border-slate-800 flex justify-between items-center">
                        <h3 class="font-bold text-slate-100">Status Sistem & Gateway Layanan</h3>
                        <span class="flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <div>
                                    <p class="text-sm font-bold text-white">Payment Gateway (Midtrans)</p>
                                    <p class="text-xs text-slate-400">Webhook siap menerima notifikasi</p>
                                </div>
                            </div>
                            <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-2.5 py-1 rounded-md">CONNECTED</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <div>
                                    <p class="text-sm font-bold text-white">WhatsApp Gateway (Queue)</p>
                                    <p class="text-xs text-slate-400">{{ $wa_queue ?? 0 }} pesan dalam antrean Job</p>
                                </div>
                            </div>
                            <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-2.5 py-1 rounded-md">ACTIVE</span>
                        </div>
                    </div>
                    <div class="bg-slate-800 px-5 py-3 mt-2">
                        <p class="text-xs text-slate-400 text-center flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Seluruh lalu lintas transaksi dan data dilindungi secara otomatis.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
