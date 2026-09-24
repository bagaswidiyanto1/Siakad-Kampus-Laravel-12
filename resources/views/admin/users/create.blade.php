<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah User Baru') }}
            </h2>
            <a href="{{ route('admin.users.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
                        @csrf

                        <!-- Pilih Role (ini menentukan field apa yang muncul) -->
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role *</label>
                            <select name="role" id="role" required data-tom-select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field Nama -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field Username (dinamis) -->
                        <div id="usernameField" class="mb-4 hidden">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username *</label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Username untuk login (format NIP/NIK disarankan)</p>
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field NIM (untuk mahasiswa) -->
                        <div id="nimField" class="mb-4 hidden">
                            <label for="nim" class="block text-sm font-medium text-gray-700">NIM *</label>
                            <input type="text" name="nim" id="nim" value="{{ old('nim') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">NIM akan otomatis menjadi username untuk login</p>
                            @error('nim')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field NIDN (untuk dosen) -->
                        <div id="nidnField" class="mb-4 hidden">
                            <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN/NIP *</label>
                            <input type="text" name="nidn" id="nidn" value="{{ old('nidn') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">NIDN/NIP akan otomatis menjadi username untuk login
                            </p>
                            @error('nidn')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (opsional) -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Opsional, hanya untuk notifikasi</p>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fakultas & Prodi (opsional, akan jadi dropdown di TAHAP 5) -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="fakultas_id"
                                    class="block text-sm font-medium text-gray-700">Fakultas</label>
                                <input type="text" name="fakultas_id" id="fakultas_id"
                                    value="{{ old('fakultas_id') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">Sementara input manual, akan jadi dropdown di
                                    TAHAP 5</p>
                            </div>
                            <div class="mb-4">
                                <label for="prodi_id" class="block text-sm font-medium text-gray-700">Program
                                    Studi</label>
                                <input type="text" name="prodi_id" id="prodi_id" value="{{ old('prodi_id') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">Sementara input manual, akan jadi dropdown di
                                    TAHAP 5</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk toggle field berdasarkan role -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const usernameField = document.getElementById('usernameField');
            const nimField = document.getElementById('nimField');
            const nidnField = document.getElementById('nidnField');

            function toggleFields() {
                const role = roleSelect.value;

                // Sembunyikan semua
                usernameField.classList.add('hidden');
                nimField.classList.add('hidden');
                nidnField.classList.add('hidden');

                // Tampilkan sesuai role
                if (role === 'mahasiswa') {
                    nimField.classList.remove('hidden');
                } else if (role === 'dosen' || role === 'dosen_wali') {
                    nidnField.classList.remove('hidden');
                } else if (role) {
                    usernameField.classList.remove('hidden');
                }
            }

            roleSelect.addEventListener('change', toggleFields);
            toggleFields(); // Jalankan saat pertama load
        });
    </script>
</x-app-layout>
