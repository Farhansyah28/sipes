<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('mata_pelajaran.update', $mata_pelajaran->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kode Mapel</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="kode" type="text" value="{{ old('kode', $mata_pelajaran->kode) }}" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Mata Pelajaran</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama" type="text" value="{{ old('nama', $mata_pelajaran->nama) }}" required>
                        </div>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" type="submit">Perbarui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
