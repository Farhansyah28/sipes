<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modul PSB (Penerimaan Santri Baru)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Calon Santri</h3>
                        <a href="{{ route('psb.create') }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                            Buka Link Pendaftaran Publik (Guest)
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Nama Pendaftar</th>
                                    <th class="py-3 px-4 border-b text-center text-xs font-medium text-gray-500 uppercase">L/P</th>
                                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Tgl Daftar</th>
                                    <th class="py-3 px-4 border-b text-center text-xs font-medium text-gray-500 uppercase">Status Pipeline</th>
                                    <th class="py-3 px-4 border-b text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($calon_santris as $c)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 font-semibold text-indigo-700">{{ $c->nama_lengkap }}</td>
                                        <td class="py-3 px-4 text-center">{{ $c->jenis_kelamin }}</td>
                                        <td class="py-3 px-4 text-gray-500">{{ $c->created_at->format('d M Y') }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @php
                                                $color = match($c->status) {
                                                    'Draft' => 'bg-gray-100 text-gray-800',
                                                    'Verifikasi' => 'bg-yellow-100 text-yellow-800',
                                                    'Tes' => 'bg-blue-100 text-blue-800',
                                                    'Lulus' => 'bg-green-100 text-green-800',
                                                    'Tidak Lulus' => 'bg-red-100 text-red-800',
                                                    'Daftar Ulang' => 'bg-purple-100 text-purple-800',
                                                    default => 'bg-gray-100',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }} uppercase shadow-sm">
                                                {{ $c->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <a href="{{ route('psb.show', $c->id) }}" class="inline-block bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-3 py-1 rounded text-xs font-bold transition-colors">Kelola Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 px-4 text-center text-gray-500 italic">Belum ada calon santri yang mendaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $calon_santris->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
