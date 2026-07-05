<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tahun Ajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('tahun_ajaran.update', $tahun_ajaran->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tahun Ajaran</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="nama" type="text" value="{{ old('nama', $tahun_ajaran->nama) }}" required>
                        </div>
                        <div class="mb-6 flex items-center">
                            <input class="mr-2 leading-tight" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $tahun_ajaran->is_active) ? 'checked' : '' }}>
                            <label class="text-sm" for="is_active">Aktifkan Tahun Ajaran Ini</label>
                        </div>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" type="submit">Perbarui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
