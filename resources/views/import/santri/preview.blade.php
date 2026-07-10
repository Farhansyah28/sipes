<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Preview Karantina Data Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg mb-1">Hasil Validasi Otomatis</h3>
                        <p class="text-sm text-gray-600">Total Data: <span class="font-bold">{{ $total }}</span> |
                            Valid: <span class="font-bold text-emerald-600">{{ $valid }}</span> | Error: <span
                                class="font-bold text-red-600">{{ $error }}</span></p>
                    </div>
                    <div>
                        @if($valid > 0)
                            <form action="{{ route('import.santri.commit', $session_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="all_valid">
                                <button type="submit"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow"
                                    onclick="return confirm('Sistem hanya akan menyimpan baris yang Valid. Baris yang Error akan diabaikan/dibuang. Lanjutkan?')">
                                    “ Simpan Hanya Data Valid ({{ $valid }})
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if($error > 0)
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Perhatian!</p>
                    <p>Ditemukan {{ $error }} baris data bermasalah. Anda bisa menyimpan data yang Valid saja sekarang, lalu
                        memperbaiki sisanya di Excel dan meng-import ulang nanti.</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    NIS</th>
                                <th
                                    class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nama Lengkap</th>
                                <th
                                    class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    L/P</th>
                                <th
                                    class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tgl Lahir</th>
                                <th
                                    class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    ID Kelas</th>
                                <th
                                    class="py-3 px-4 border-b text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Saldo Awal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($rows as $r)
                                @php $data = $r->row_data; @endphp
                                <tr class="{{ $r->status === 'Error' ? 'bg-red-50/50' : 'hover:bg-gray-50' }}">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if($r->status === 'Error')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Error
                                            </span>
                                            <p class="text-[10px] text-red-600 mt-1 max-w-[150px] whitespace-normal font-bold">
                                                {{ $r->error_messages }}</p>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Valid
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="py-3 px-4 whitespace-nowrap {{ $r->status === 'Error' ? 'text-red-900 font-medium' : 'text-gray-900' }}">
                                        {{ $data['nis'] }}</td>
                                    <td
                                        class="py-3 px-4 whitespace-nowrap {{ $r->status === 'Error' ? 'text-red-900 font-medium' : 'text-gray-900' }}">
                                        {{ $data['nama_lengkap'] }}</td>
                                    <td
                                        class="py-3 px-4 whitespace-nowrap {{ $r->status === 'Error' ? 'text-red-900 font-medium' : 'text-gray-900' }}">
                                        {{ $data['jenis_kelamin'] }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap text-gray-500">{{ $data['tanggal_lahir'] }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap text-gray-500">{{ $data['kelas_id'] }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap text-right font-medium text-gray-900">Rp
                                        {{ number_format((float) ($data['saldo_awal'] ?? 0), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 flex justify-between">
                <a href="{{ route('import.santri.index') }}"
                    class="text-gray-600 hover:text-gray-900 underline text-sm">&laquo; Batal dan Kembali ke Form
                    Upload</a>
            </div>

        </div>
    </div>
</x-app-layout>