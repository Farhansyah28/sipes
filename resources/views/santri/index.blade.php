<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Induk Santri') }}
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
                        <h3 class="text-lg font-bold">Daftar Santri</h3>
                        <div class="space-x-2">
                            <a href="{{ route('import.santri.index') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Import CSV
                            </a>
                            <a href="{{ route('santri.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Tambah Santri
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">No</th>
                                    <th class="py-2 px-4 border-b text-left">NIS</th>
                                    <th class="py-2 px-4 border-b text-left">Nama Lengkap</th>
                                    <th class="py-2 px-4 border-b text-left">L/P</th>
                                    <th class="py-2 px-4 border-b text-left">Kelas</th>
                                    <th class="py-2 px-4 border-b text-left">Kamar</th>
                                    <th class="py-2 px-4 border-b text-left">Status</th>
                                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($santris as $index => $s)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $santris->firstItem() + $index }}</td>
                                    <td class="py-2 px-4 border-b">{{ $s->nis ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b font-semibold">{{ $s->nama_lengkap }}</td>
                                    <td class="py-2 px-4 border-b">{{ $s->jenis_kelamin }}</td>
                                    <td class="py-2 px-4 border-b">{{ $s->kelas->nama ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b">{{ $s->kamar->nama ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <span class="px-2 py-1 rounded text-xs text-white bg-gray-500">{{ $s->status }}</span>
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="{{ route('santri.edit', $s->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                        <form action="{{ route('santri.destroy', $s->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $santris->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
