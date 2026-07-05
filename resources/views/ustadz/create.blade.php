<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Ustadz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('ustadz.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">NIP / Nomor Induk</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nip" type="text" value="{{ old('nip') }}">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">No HP</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="no_hp" type="text" value="{{ old('no_hp') }}">
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
