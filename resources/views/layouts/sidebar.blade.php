<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800 custom-scrollbar">
      <ul class="space-y-1 font-medium">
         
         <li>
            <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 group {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 font-bold' : '' }}">
               <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('dashboard') ? 'text-emerald-700' : 'text-emerald-500 group-hover:text-emerald-600' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard Utama</span>
            </a>
         </li>

         <!-- PSB & MASTER DATA -->
         <li class="pt-4 pb-2">
             <span class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Master Data & PSB</span>
         </li>
         <li>
            <a href="{{ route('psb.index') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 group {{ request()->routeIs('psb.*') ? 'bg-blue-50 text-blue-700 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('psb.*') ? 'text-blue-700' : 'text-blue-500 group-hover:text-blue-600' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                  <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Pendaftaran (PSB)</span>
               @php $psbCount = \App\Models\Santri::where('status', 'Verifikasi')->count(); @endphp
               @if($psbCount > 0)
                   <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $psbCount }}</span>
               @endif
            </a>
         </li>
         
         @php
            $isMasterActive = request()->routeIs('santri.*', 'orang_tua.*', 'import.santri.*', 'ustadz.*', 'import.ustadz.*');
         @endphp
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg transition duration-75 text-gray-900 hover:bg-gray-100 group {{ $isMasterActive ? 'bg-indigo-50' : '' }}" aria-controls="dropdown-master" data-collapse-toggle="dropdown-master" aria-expanded="{{ $isMasterActive ? 'true' : 'false' }}">
                  <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ $isMasterActive ? 'text-indigo-700' : 'text-indigo-500 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap {{ $isMasterActive ? 'text-indigo-700 font-bold' : '' }}">Data Induk</span>
                  <svg class="w-3 h-3 transition-transform duration-200 {{ $isMasterActive ? 'rotate-180 text-indigo-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-master" class="{{ $isMasterActive ? '' : 'hidden' }} py-2 space-y-1">
                  <li>
                     <a href="{{ route('santri.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('santri.*') ? 'text-indigo-700 font-bold bg-indigo-50/50' : 'text-gray-600 hover:text-indigo-700 hover:bg-gray-100' }}">Data Induk Santri</a>
                  </li>
                  <li>
                     <a href="{{ route('orang_tua.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('orang_tua.*') ? 'text-indigo-700 font-bold bg-indigo-50/50' : 'text-gray-600 hover:text-indigo-700 hover:bg-gray-100' }}">Data Orang Tua</a>
                  </li>

                  <li>
                     <a href="{{ route('ustadz.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('ustadz.*') && !request()->routeIs('penugasan.ustadz.*') ? 'text-indigo-700 font-bold bg-indigo-50/50' : 'text-gray-600 hover:text-indigo-700 hover:bg-gray-100' }}">Pegawai & Ustadz</a>
                  </li>

            </ul>
         </li>

         <!-- AKADEMIK & ASRAMA -->
         <li class="pt-4 pb-2">
             <span class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Akademik & Asrama</span>
         </li>
         @php
            $isAkademikActive = request()->routeIs('jadwal.*', 'auto_schedule.*', 'absensi.*', 'nilai.*', 'penugasan.ustadz.*', 'kelas.*', 'mata_pelajaran.*', 'tahun_ajaran.*', 'semester.*', 'kenaikan_kelas.*');
         @endphp
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg transition duration-75 text-gray-900 hover:bg-gray-100 group {{ $isAkademikActive ? 'bg-purple-50' : '' }}" aria-controls="dropdown-akademik" data-collapse-toggle="dropdown-akademik" aria-expanded="{{ $isAkademikActive ? 'true' : 'false' }}">
                  <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ $isAkademikActive ? 'text-purple-700' : 'text-purple-500 group-hover:text-purple-600' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                     <path fill-rule="evenodd" d="M11 4.717c-2.286-.58-4.16-.756-7.045-.71A1.99 1.99 0 0 0 2 6v11c0 1.133.893 2.01 1.954 2.052.274.01.55.018.824.025.5.012 1.07.026 1.66.045 1.144.037 2.416.1 3.513.255a7.1 7.1 0 0 1 1.05.228 1 1 0 0 0 .998 0 7.1 7.1 0 0 1 1.05-.228c1.097-.155 2.37-.218 3.514-.255.59-.019 1.16-.033 1.66-.045.274-.007.55-.015.824-.025C21.107 19.01 22 18.133 22 17V6c0-1.125-.87-1.99-1.954-2.01-2.885-.046-4.76.13-7.045.71a1 1 0 0 0-.001 0Zm-1 12.923V5.518a24.16 24.16 0 0 0-5.741-.5c-1.396.046-2.553.136-3.26.23v11.14c.731-.115 1.954-.218 3.42-.265.595-.018 1.186-.033 1.706-.046.257-.006.516-.013.771-.02 1.042-.026 1.99-.052 2.646-.119.26-.026.54-.06.858-.106Zm2-.106c.318.046.598.08.858.106.656.067 1.604.093 2.646.119.255.007.514.014.771.02.52.013 1.11.028 1.706.046 1.466.047 2.69.15 3.42.265v-11.14c-.707-.094-1.864-.184-3.26-.23a24.15 24.15 0 0 0-5.742.5v12.124Z" clip-rule="evenodd"/>
                  </svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap {{ $isAkademikActive ? 'text-purple-700 font-bold' : '' }}">Akademik</span>
                  <svg class="w-3 h-3 transition-transform duration-200 {{ $isAkademikActive ? 'rotate-180 text-purple-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-akademik" class="{{ $isAkademikActive ? '' : 'hidden' }} py-2 space-y-1">
                  <li>
                     <a href="{{ route('tahun_ajaran.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('tahun_ajaran.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Tahun Ajaran</a>
                  </li>
                  <li>
                     <a href="{{ route('semester.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('semester.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Semester</a>
                  </li>
                  <li>
                     <a href="{{ route('kelas.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('kelas.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Manajemen Kelas</a>
                  </li>
                  <li>
                     <a href="{{ route('kenaikan_kelas.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('kenaikan_kelas.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Kenaikan Kelas</a>
                  </li>
                  <li>
                     <a href="{{ route('mata_pelajaran.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('mata_pelajaran.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Mata Pelajaran</a>
                  </li>
                  <li>
                     <a href="{{ route('penugasan.ustadz.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('penugasan.ustadz.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Penugasan Guru</a>
                  </li>
                  <li>
                     <a href="{{ route('jadwal.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('jadwal.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Dashboard Jadwal</a>
                  </li>
                  <li>
                     <a href="{{ route('auto_schedule.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('auto_schedule.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Generator Jadwal Otomatis</a>
                  </li>
                  <li>
                     <a href="{{ route('absensi.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('absensi.*') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Absensi Harian</a>
                  </li>
                  <li>
                     <a href="{{ route('nilai.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('nilai.index') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Input Nilai</a>
                  </li>
                  <li>
                     <a href="{{ route('nilai.raport') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('nilai.raport') ? 'text-purple-700 font-bold bg-purple-50/50' : 'text-gray-600 hover:text-purple-700 hover:bg-gray-100' }}">Cetak Raport</a>
                  </li>
            </ul>
         </li>
         @php
            $isAsramaActive = request()->routeIs('gedung.*', 'kamar.*', 'riwayat_kamar.*');
         @endphp
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg transition duration-75 text-gray-900 hover:bg-gray-100 group {{ $isAsramaActive ? 'bg-amber-50' : '' }}" aria-controls="dropdown-asrama" data-collapse-toggle="dropdown-asrama" aria-expanded="{{ $isAsramaActive ? 'true' : 'false' }}">
                  <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ $isAsramaActive ? 'text-amber-700' : 'text-amber-500 group-hover:text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap {{ $isAsramaActive ? 'text-amber-700 font-bold' : '' }}">Asrama</span>
                  <svg class="w-3 h-3 transition-transform duration-200 {{ $isAsramaActive ? 'rotate-180 text-amber-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-asrama" class="{{ $isAsramaActive ? '' : 'hidden' }} py-2 space-y-1">
                  <li>
                     <a href="{{ route('gedung.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('gedung.*') ? 'text-amber-700 font-bold bg-amber-50/50' : 'text-gray-600 hover:text-amber-700 hover:bg-gray-100' }}">Master Gedung</a>
                  </li>
                  <li>
                     <a href="{{ route('kamar.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('kamar.*') ? 'text-amber-700 font-bold bg-amber-50/50' : 'text-gray-600 hover:text-amber-700 hover:bg-gray-100' }}">Manajemen Kamar</a>
                  </li>
                  <li>
                     <a href="{{ route('riwayat_kamar.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('riwayat_kamar.*') ? 'text-amber-700 font-bold bg-amber-50/50' : 'text-gray-600 hover:text-amber-700 hover:bg-gray-100' }}">Riwayat Penempatan</a>
                  </li>
            </ul>
         </li>

         <!-- KEUANGAN & KANTIN -->
         <li class="pt-4 pb-2">
             <span class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Keuangan & Kantin</span>
         </li>
         @php
            $isKeuanganActive = request()->routeIs('kategori_tagihan.*', 'tagihan.*', 'pembayaran.*', 'ledger.*');
         @endphp
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg transition duration-75 text-gray-900 hover:bg-gray-100 group {{ $isKeuanganActive ? 'bg-green-50' : '' }}" aria-controls="dropdown-keuangan" data-collapse-toggle="dropdown-keuangan" aria-expanded="{{ $isKeuanganActive ? 'true' : 'false' }}">
                  <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ $isKeuanganActive ? 'text-green-700' : 'text-green-500 group-hover:text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap {{ $isKeuanganActive ? 'text-green-700 font-bold' : '' }}">Keuangan</span>
                  <svg class="w-3 h-3 transition-transform duration-200 {{ $isKeuanganActive ? 'rotate-180 text-green-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-keuangan" class="{{ $isKeuanganActive ? '' : 'hidden' }} py-2 space-y-1">
                  <li>
                     <a href="{{ route('kategori_tagihan.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('kategori_tagihan.*') ? 'text-green-700 font-bold bg-green-50/50' : 'text-gray-600 hover:text-green-700 hover:bg-gray-100' }}">Master Kategori</a>
                  </li>
                  <li>
                     <a href="{{ route('tagihan.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('tagihan.*') ? 'text-green-700 font-bold bg-green-50/50' : 'text-gray-600 hover:text-green-700 hover:bg-gray-100' }}">Data Tagihan</a>
                  </li>
                  <li>
                     <a href="{{ route('pembayaran.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('pembayaran.*') ? 'text-green-700 font-bold bg-green-50/50' : 'text-gray-600 hover:text-green-700 hover:bg-gray-100' }}">Pembayaran (Kasir)</a>
                  </li>
                  <li>
                     <a href="{{ route('ledger.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('ledger.*') ? 'text-green-700 font-bold bg-green-50/50' : 'text-gray-600 hover:text-green-700 hover:bg-gray-100' }}">Buku Besar (Ledger)</a>
                  </li>
            </ul>
         </li>
         <li>
            <a href="{{ route('tabungan.index') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 group {{ request()->routeIs('tabungan.*') ? 'bg-pink-50 text-pink-700 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('tabungan.*') ? 'text-pink-700' : 'text-pink-500 group-hover:text-pink-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Tabungan Santri</span>
            </a>
         </li>
         @php
            $isKantinActive = request()->routeIs('transaksi_pos.*', 'kategori_barang.*', 'supplier.*', 'barang.*', 'batch_stok.*', 'laporan_kantin.*');
         @endphp
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg transition duration-75 text-gray-900 hover:bg-gray-100 group {{ $isKantinActive ? 'bg-cyan-50' : '' }}" aria-controls="dropdown-kantin" data-collapse-toggle="dropdown-kantin" aria-expanded="{{ $isKantinActive ? 'true' : 'false' }}">
                  <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ $isKantinActive ? 'text-cyan-700' : 'text-cyan-500 group-hover:text-cyan-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap {{ $isKantinActive ? 'text-cyan-700 font-bold' : '' }}">Kantin POS</span>
                  <svg class="w-3 h-3 transition-transform duration-200 {{ $isKantinActive ? 'rotate-180 text-cyan-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-kantin" class="{{ $isKantinActive ? '' : 'hidden' }} py-2 space-y-1">
                  <li>
                     <a href="{{ route('transaksi_pos.create') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('transaksi_pos.create') ? 'text-cyan-700 font-bold bg-cyan-50/50' : 'text-gray-600 hover:text-cyan-700 hover:bg-gray-100' }}">Buka Kasir (POS)</a>
                  </li>
                  <li>
                     <a href="{{ route('transaksi_pos.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('transaksi_pos.index') ? 'text-cyan-700 font-bold bg-cyan-50/50' : 'text-gray-600 hover:text-cyan-700 hover:bg-gray-100' }}">Riwayat Transaksi</a>
                  </li>
                  <li>
                     <a href="{{ route('barang.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('barang.*') ? 'text-cyan-700 font-bold bg-cyan-50/50' : 'text-gray-600 hover:text-cyan-700 hover:bg-gray-100' }}">Master Barang</a>
                  </li>
                  <li>
                     <a href="{{ route('kategori_barang.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('kategori_barang.*') ? 'text-cyan-700 font-bold bg-cyan-50/50' : 'text-gray-600 hover:text-cyan-700 hover:bg-gray-100' }}">Kategori Barang</a>
                  </li>
                  <li>
                     <a href="{{ route('supplier.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('supplier.*') ? 'text-cyan-700 font-bold bg-cyan-50/50' : 'text-gray-600 hover:text-cyan-700 hover:bg-gray-100' }}">Data Supplier</a>
                  </li>
                  <li>
                     <a href="{{ route('batch_stok.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('batch_stok.*') ? 'text-cyan-700 font-bold bg-cyan-50/50' : 'text-gray-600 hover:text-cyan-700 hover:bg-gray-100' }}">Pembelian & Stok</a>
                  </li>
                  <li>
                     <a href="{{ route('laporan_kantin.index') }}" class="flex items-center w-full p-2 text-sm transition duration-75 rounded-lg pl-11 group {{ request()->routeIs('laporan_kantin.*') ? 'text-cyan-700 font-bold bg-cyan-50/50' : 'text-gray-600 hover:text-cyan-700 hover:bg-gray-100' }}">Laporan Keuangan</a>
                  </li>
            </ul>
         </li>

         <!-- SISTEM -->
         <li class="pt-4 pb-2">
             <span class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sistem</span>
         </li>
         <li>
            <a href="{{ route('users.index') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 group {{ request()->routeIs('users.*') ? 'bg-orange-50 text-orange-700 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('users.*') ? 'text-orange-700' : 'text-orange-500 group-hover:text-orange-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Manajemen User</span>
            </a>
         </li>
         <li>
            <a href="{{ route('activity_log.index') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 group {{ request()->routeIs('activity_log.*') ? 'bg-gray-100 text-gray-900 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('activity_log.*') ? 'text-gray-800' : 'text-slate-500 group-hover:text-gray-800' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Activity Logs</span>
            </a>
         </li>

      </ul>
   </div>
</aside>
<style>
/* Custom Scrollbar for Sidebar */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent; 
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1; 
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8; 
}
</style>
