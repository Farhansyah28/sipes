<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('kelas.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tingkat (Misal: VII, VIII, IX)</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="tingkat" type="text" value="{{ old('tingkat') }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kelas (Misal: 7A, 7B)</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama" type="text" value="{{ old('nama') }}" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Wali Kelas</label>
                            <select name="wali_kelas_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($ustadzs as $u)
                                    <option value="{{ $u->id }}" {{ old('wali_kelas_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
