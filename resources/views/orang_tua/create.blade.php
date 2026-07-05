<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Wali Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('orang_tua.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Ayah</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama_ayah" type="text" value="{{ old('nama_ayah') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Ibu</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama_ibu" type="text" value="{{ old('nama_ibu') }}">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">No HP Ayah</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="no_hp_ayah" type="text" value="{{ old('no_hp_ayah') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">No HP Ibu</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="no_hp_ibu" type="text" value="{{ old('no_hp_ibu') }}">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap</label>
                            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
