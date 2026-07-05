<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Tabungan Santri') }}
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
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Rekening Tabungan</h3>
                        <a href="{{ route('tabungan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Buka Rekening Santri Baru
                        </a>
                    </div>

                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">NIS</th>
                                <th class="py-2 px-4 border-b text-left">Nama Santri</th>
                                <th class="py-2 px-4 border-b text-right">Total Saldo</th>
                                <th class="py-2 px-4 border-b text-center">Aksi (Mutasi)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tabungans as $t)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $t->santri->nis ?? '-' }}</td>
                                <td class="py-2 px-4 border-b font-semibold">{{ $t->santri->nama_lengkap ?? '-' }}</td>
                                <td class="py-2 px-4 border-b text-right font-bold text-blue-600">Rp {{ number_format($t->saldo, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="{{ route('tabungan.edit', $t->id) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs">Setor / Tarik Tunai</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $tabungans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
