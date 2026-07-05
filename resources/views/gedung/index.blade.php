<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Gedung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Gedung</h3>
                        <a href="{{ route('gedung.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Gedung
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">No</th>
                                    <th class="py-2 px-4 border-b text-left">Nama Gedung</th>
                                    <th class="py-2 px-4 border-b text-left">Tipe Asrama</th>
                                    <th class="py-2 px-4 border-b text-left">Deskripsi</th>
                                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gedungs as $index => $g)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $gedungs->firstItem() + $index }}</td>
                                    <td class="py-2 px-4 border-b">{{ $g->nama }}</td>
                                    <td class="py-2 px-4 border-b">
                                        @if($g->jenis_kelamin == 'L')
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Asrama Putra</span>
                                        @elseif($g->jenis_kelamin == 'P')
                                            <span class="bg-pink-100 text-pink-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Asrama Putri</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Campur / Umum</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b">{{ $g->deskripsi ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="{{ route('gedung.edit', $g->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                        <form action="{{ route('gedung.destroy', $g->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $gedungs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
