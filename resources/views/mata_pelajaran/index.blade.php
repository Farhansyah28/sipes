<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Mata Pelajaran') }}
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
                        <h3 class="text-lg font-bold">Daftar Mata Pelajaran</h3>
                        <a href="{{ route('mata_pelajaran.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Mapel
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">No</th>
                                    <th class="py-2 px-4 border-b text-left">Kode</th>
                                    <th class="py-2 px-4 border-b text-left">Nama Mata Pelajaran</th>
                                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mata_pelajarans as $index => $m)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $mata_pelajarans->firstItem() + $index }}</td>
                                    <td class="py-2 px-4 border-b font-mono">{{ $m->kode }}</td>
                                    <td class="py-2 px-4 border-b">{{ $m->nama }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="{{ route('mata_pelajaran.edit', $m->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                        <form action="{{ route('mata_pelajaran.destroy', $m->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $mata_pelajarans->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
