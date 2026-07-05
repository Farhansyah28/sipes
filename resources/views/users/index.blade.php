<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna & Hak Akses (Role)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong>Oops! Ada masalah dengan inputan Anda:</strong>
                    <ul class="list-disc mt-2 ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Tambah Pengguna Baru</h3>
                    <form action="{{ route('users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                            <input type="text" name="name" class="shadow border rounded w-full py-2 px-3" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Username Login</label>
                            <input type="text" name="username" class="shadow border rounded w-full py-2 px-3" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Role Akses (Bisa Pilih > 1)</label>
                            <select name="roles[]" class="shadow border rounded w-full py-2 px-3 h-24" multiple required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                            <input type="password" name="password" class="shadow border rounded w-full py-2 px-3" required>
                        </div>
                        <div class="md:col-span-4 mt-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                                + Tambah Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Nama</th>
                                <th class="py-2 px-4 border-b text-left">Username</th>
                                <th class="py-2 px-4 border-b text-left">Role / Hak Akses</th>
                                <th class="py-2 px-4 border-b text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="py-2 px-4">{{ $user->name }}</td>
                                <td class="py-2 px-4">{{ $user->username }}</td>
                                <td class="py-2 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2 py-0.5 rounded">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400 text-xs">No Role</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="py-2 px-4 flex space-x-2">
                                    <!-- Edit Modal Trigger -->
                                    <button onclick="document.getElementById('edit-modal-{{ $user->id }}').classList.remove('hidden')" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-xs">Edit</button>
                                    
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pengguna ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-white py-1 px-3 rounded text-xs {{ $user->hasRole('Super Admin') ? 'bg-red-300 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600' }}" {{ $user->hasRole('Super Admin') ? 'disabled' : '' }}>Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div id="edit-modal-{{ $user->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                    <div class="mt-3">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Pengguna</h3>
                                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
                                                <input type="text" name="name" value="{{ $user->name }}" class="shadow border rounded w-full py-2 px-3" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                                                <input type="text" name="username" value="{{ $user->username }}" class="shadow border rounded w-full py-2 px-3" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2">Role Akses (Tahan CTRL untuk multi-select)</label>
                                                <select name="roles[]" class="shadow border rounded w-full py-2 px-3 h-32" multiple required>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru (Kosongkan jika tidak diubah)</label>
                                                <input type="password" name="password" class="shadow border rounded w-full py-2 px-3">
                                            </div>
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" onclick="document.getElementById('edit-modal-{{ $user->id }}').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded">Batal</button>
                                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
