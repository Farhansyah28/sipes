<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Data Ustadz via CSV') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-8 p-4 bg-indigo-50 border border-indigo-200 rounded-lg flex items-start">
                        <svg class="w-6 h-6 text-indigo-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="font-bold text-indigo-800 text-lg mb-1">Panduan Import (Data Cleansing Sandbox)</h3>
                            <p class="text-sm text-indigo-600 mb-3">Gunakan form ini untuk meng-import biodata dasar Ustadz. Untuk penugasan Mata Pelajaran dan Jadwal, akan dilakukan di menu "Penugasan Akademik" setelah data ini tersimpan.</p>
                            <ol class="list-decimal ml-5 text-sm text-indigo-700 space-y-1 mb-3">
                                <li>Unduh template CSV di bawah ini.</li>
                                <li>Isi data Ustadz tanpa mengubah header kolom.</li>
                                <li>Simpan file (Save As) dengan format <strong>.csv (Comma delimited)</strong>.</li>
                                <li>Unggah file tersebut ke form di bawah ini.</li>
                            </ol>
                            <div class="bg-indigo-100 p-3 rounded text-xs text-indigo-800 font-medium">
                                ðŸ’¡ <strong>Tips Excel:</strong> Jika No. HP Anda berubah menjadi angka aneh (seperti <code>8,57E+11</code>) atau angka nol (<code>0</code>) di depannya hilang, tambahkan tanda kutip satu (<code>'</code>) di awal nomor. Contoh: <code>'0857812345601</code>. Sistem kami akan otomatis membersihkan tanda kutip tersebut saat di-import.
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mb-8">
                        <a href="{{ route('import.ustadz.template') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Template CSV
                        </a>
                    </div>

                    <hr class="mb-8">

                    <form action="{{ route('import.ustadz.upload') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="file_csv">
                                Pilih File CSV
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white" id="file_csv" type="file" name="file_csv" accept=".csv" required>
                            <p class="text-xs text-gray-500 mt-2">Maksimal ukuran file: 2MB. Hanya menerima ekstensi .csv</p>
                        </div>
                        <div class="flex items-center justify-start">
                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline" type="submit">
                                Unggah & Karantina Data
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
