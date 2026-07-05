<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Semester') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('semester.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tahun Ajaran</label>
                            <select name="tahun_ajaran_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahun_ajarans as $ta)
                                    <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>{{ $ta->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Semester (Misal: Ganjil / Genap)</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama" type="text" value="{{ old('nama') }}" required>
                        </div>
                        <div class="mb-6 flex items-center">
                            <input class="mr-2 leading-tight" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="text-sm" for="is_active">Aktifkan Semester Ini</label>
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
