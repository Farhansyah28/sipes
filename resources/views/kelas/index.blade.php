<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Kelas') }}
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
                        <h3 class="text-lg font-bold">Daftar Kelas</h3>
                        <a href="{{ route('kelas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Kelas
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">No</th>
                                    <th class="py-2 px-4 border-b text-left">Tingkat</th>
                                    <th class="py-2 px-4 border-b text-left">Nama Kelas</th>
                                    <th class="py-2 px-4 border-b text-left">Wali Kelas</th>
                                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelas as $index => $k)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $kelas->firstItem() + $index }}</td>
                                    <td class="py-2 px-4 border-b">{{ $k->tingkat }}</td>
                                    <td class="py-2 px-4 border-b">{{ $k->nama }}</td>
                                    <td class="py-2 px-4 border-b">{{ $k->waliKelas->nama_lengkap ?? 'Belum Ditentukan' }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="{{ route('kelas.edit', $k->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                        <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $kelas->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
