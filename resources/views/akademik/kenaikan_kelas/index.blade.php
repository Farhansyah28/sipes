<x-app-layout>
    <div class="px-4 py-8 sm:px-6 lg:px-8 mx-auto w-full max-w-7xl">
        
        <!-- Header -->
        <div class="mb-8 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Kenaikan Kelas
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Proses kenaikan kelas santri secara massal (bulk promotion).
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-100">
            <div class="p-6">
                <form action="{{ route('kenaikan_kelas.index') }}" method="GET" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        
                        <div>
                            <label for="kelas_asal" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas Asal</label>
                            <select id="kelas_asal" name="kelas_asal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ request('kelas_asal') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }} ({{ $k->tingkat }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" class="text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 w-full md:w-auto shadow-sm transition-colors duration-200">
                                Tampilkan Santri
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table & Action Form -->
        @if(request()->has('kelas_asal'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <form action="{{ route('kenaikan_kelas.store') }}" method="POST" id="kenaikanForm">
                    @csrf
                    
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="kelas_tujuan" class="block text-sm font-medium text-gray-700 mb-1">Naik Ke Kelas Tujuan <span class="text-red-500">*</span></label>
                                <select id="kelas_tujuan" name="kelas_tujuan" required class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 shadow-sm">
                                    <option value="">-- Pilih Kelas Tujuan --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">
                                            {{ $k->nama_kelas }} ({{ $k->tingkat }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran Baru <span class="text-red-500">*</span></label>
                                <select id="tahun_ajaran_id" name="tahun_ajaran_id" required class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 shadow-sm">
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    @foreach($tahun_ajarans as $ta)
                                        <option value="{{ $ta->id }}">
                                            {{ $ta->nama }} ({{ $ta->is_active ? 'Aktif' : 'Tidak Aktif' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memproses kenaikan kelas untuk santri yang dipilih?')" class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 w-full shadow-sm transition-colors duration-200">
                                    Proses Kenaikan Kelas
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 cursor-pointer">
                                            <label for="checkbox-all" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3">NIS</th>
                                    <th scope="col" class="px-6 py-3">Nama Santri</th>
                                    <th scope="col" class="px-6 py-3">Jenis Kelamin</th>
                                    <th scope="col" class="px-6 py-3">Kelas Asal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($santris as $santri)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-{{ $santri->id }}" name="santri_ids[]" value="{{ $santri->id }}" type="checkbox" class="santri-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 cursor-pointer">
                                                <label for="checkbox-{{ $santri->id }}" class="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $santri->nis }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $santri->nama_lengkap }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $santri->jenis_kelamin }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">
                                                {{ $santri->kelas->nama_kelas ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                <p class="text-base font-semibold text-gray-600">Tidak ada santri aktif</p>
                                                <p class="text-sm mt-1">Kelas asal ini kosong atau semua santri tidak aktif.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        @else
            <!-- Empty State Before Filter -->
            <div class="bg-white rounded-lg border border-gray-100 border-dashed p-12 text-center shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">Mulai Proses</h3>
                <p class="mt-1 text-sm text-gray-500">Pilih "Kelas Asal" di atas lalu klik "Tampilkan Santri" untuk memulai proses kenaikan kelas.</p>
            </div>
        @endif

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('checkbox-all');
            const santriCheckboxes = document.querySelectorAll('.santri-checkbox');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    santriCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });

                santriCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(santriCheckboxes).every(c => c.checked);
                        const someChecked = Array.from(santriCheckboxes).some(c => c.checked);
                        
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
