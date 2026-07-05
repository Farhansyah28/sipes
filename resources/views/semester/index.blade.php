<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Semester') }}
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
                        <h3 class="text-lg font-bold">Daftar Semester</h3>
                        <a href="{{ route('semester.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Data
                        </a>
                    </div>

                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">No</th>
                                <th class="py-2 px-4 border-b text-left">Tahun Ajaran</th>
                                <th class="py-2 px-4 border-b text-left">Semester</th>
                                <th class="py-2 px-4 border-b text-left">Status</th>
                                <th class="py-2 px-4 border-b text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semesters as $index => $item)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $semesters->firstItem() + $index }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->tahunAjaran->nama ?? '-' }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->nama }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if($item->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <a href="{{ route('semester.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    <form action="{{ route('semester.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin dihapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $semesters->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
