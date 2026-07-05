<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buka Rekening Tabungan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('tabungan.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Santri (Yang Belum Punya Tabungan)</label>
                            <select name="santri_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">-- Pilih Santri --</option>
                                @foreach($santris as $s)
                                    <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>{{ $s->nis }} - {{ $s->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Setoran Awal (Rp)</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="saldo_awal" type="number" min="0" value="{{ old('saldo_awal', 0) }}" required>
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Buka Rekening</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
