<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('santri.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">NIS (Nomor Induk Santri)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nis" type="text" value="{{ old('nis') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">NISN</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nisn" type="text" value="{{ old('nisn') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tempat Lahir</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="tempat_lahir" type="text" value="{{ old('tempat_lahir') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lahir</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}">
                            </div>
                        </div>

                        <hr class="my-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Wali Santri (Orang Tua)</label>
                                <select name="orang_tua_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    <option value="">-- Pilih Wali Santri --</option>
                                    @foreach($orang_tuas as $ot)
                                        <option value="{{ $ot->id }}" {{ old('orang_tua_id') == $ot->id ? 'selected' : '' }}>{{ $ot->nama_ayah ?? $ot->nama_ibu }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Status Santri</label>
                                <select name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                    @foreach(['Draft', 'Verifikasi', 'Tes', 'Lulus', 'Tidak Lulus', 'Daftar Ulang', 'Aktif', 'Alumni', 'Keluar'] as $stat)
                                        <option value="{{ $stat }}" {{ old('status') == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Penempatan Kelas</label>
                                <select name="kelas_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    <option value="">-- Belum Ada Kelas --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
