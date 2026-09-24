<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit User: ' . $user->username) }}
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
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <!-- Pilih Role -->
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role *</label>
                            <select name="role" id="role" required data-tom-select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- Username (dinamis) -->
                        <div id="usernameField" class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username *</label>
                            <input type="text" name="username" id="username"
                                value="{{ old('username', $user->username) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Username untuk login</p>
                        </div>

                        <!-- NIM untuk mahasiswa -->
                        <div id="nimField" class="mb-4 hidden">
                            <label for="nim" class="block text-sm font-medium text-gray-700">NIM *</label>
                            <input type="text" name="nim" id="nim"
                                value="{{ old('nim', $user->username) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">NIM akan otomatis menjadi username untuk login</p>
                        </div>

                        <!-- NIDN untuk dosen -->
                        <div id="nidnField" class="mb-4 hidden">
                            <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN/NIP *</label>
                            <input type="text" name="nidn" id="nidn"
                                value="{{ old('nidn', $user->username) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">NIDN/NIP akan otomatis menjadi username untuk login
                            </p>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $user->email) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Opsional, hanya untuk notifikasi</p>
                        </div>

                        <!-- Fakultas & Prodi -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="fakultas_id" class="block text-sm font-medium text-gray-700">Fakultas
                                    ID</label>
                                <input type="text" name="fakultas_id" id="fakultas_id"
                                    value="{{ old('fakultas_id', $user->fakultas_id) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">Akan jadi dropdown di TAHAP 5</p>
                            </div>
                            <div class="mb-4">
                                <label for="prodi_id" class="block text-sm font-medium text-gray-700">Program Studi
                                    ID</label>
                                <input type="text" name="prodi_id" id="prodi_id"
                                    value="{{ old('prodi_id', $user->prodi_id) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">Akan jadi dropdown di TAHAP 5</p>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const usernameField = document.getElementById('usernameField');
            const nimField = document.getElementById('nimField');
            const nidnField = document.getElementById('nidnField');
            const usernameInput = document.getElementById('username');
            const nimInput = document.getElementById('nim');
            const nidnInput = document.getElementById('nidn');

            function toggleFields() {
                const role = roleSelect.value;

                usernameField.classList.add('hidden');
                nimField.classList.add('hidden');
                nidnField.classList.add('hidden');

                if (role === 'mahasiswa') {
                    nimField.classList.remove('hidden');
                    // Set username dari nim
                    if (nimInput.value) {
                        usernameInput.value = nimInput.value;
                    }
                } else if (role === 'dosen' || role === 'dosen_wali') {
                    nidnField.classList.remove('hidden');
                    if (nidnInput.value) {
                        usernameInput.value = nidnInput.value;
                    }
                } else if (role) {
                    usernameField.classList.remove('hidden');
                }
            }

            // Auto-fill username dari NIM/NIDN
            nimInput.addEventListener('input', function() {
                if (document.getElementById('role').value === 'mahasiswa') {
                    usernameInput.value = this.value;
                }
            });

            nidnInput.addEventListener('input', function() {
                if (document.getElementById('role').value === 'dosen' ||
                    document.getElementById('role').value === 'dosen_wali') {
                    usernameInput.value = this.value;
                }
            });

            roleSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
</x-app-layout>
