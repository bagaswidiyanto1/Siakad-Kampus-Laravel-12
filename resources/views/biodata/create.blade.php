<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Biodata Mahasiswa') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('biodata.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">


                    <div class="mb-6 rounded-lg bg-blue-50 border border-blue-200 px-4 py-3 text-sm text-blue-800">
                        <strong>Info:</strong> Akun login mahasiswa akan otomatis dibuat dengan
                        <strong>username = NIM</strong> dan password default <strong>"password"</strong>.
                    </div>

                    <form method="POST" action="{{ route('biodata.store') }}" class="ajax-form">
                        @csrf

                        {{-- Baris 1: NIM + Nama --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="nim" class="block text-sm font-medium text-gray-700">
                                    NIM *
                                </label>
                                <input type="text" name="nim" id="nim" value="{{ old('nim') }}" required
                                    maxlength="20" placeholder="Contoh: 2024001"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('nim')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Nama Lengkap *
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    maxlength="255" placeholder="Contoh: Budi Santoso"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Baris 2: Email --}}
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email (untuk notifikasi)
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                maxlength="255" placeholder="Contoh: budi@siakad.test"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Baris 3: Fakultas + Prodi --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="fakultas_id" class="block text-sm font-medium text-gray-700">
                                    Fakultas *
                                </label>
                                <select name="fakultas_id" id="fakultas_id" required data-tom-select
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Pilih Fakultas</option>
                                    @foreach ($fakultas as $f)
                                        <option value="{{ $f->id }}"
                                            {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>
                                            {{ $f->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fakultas_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="prodi_id" class="block text-sm font-medium text-gray-700">
                                    Program Studi *
                                </label>
                                <select name="prodi_id" id="prodi_id" required data-tom-select
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Pilih Prodi</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('prodi_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Baris 4: Dosen Wali + Angkatan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="dosen_wali_id" class="block text-sm font-medium text-gray-700">
                                    Dosen Wali
                                </label>
                                <select name="dosen_wali_id" id="dosen_wali_id" data-tom-select
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Belum ditentukan</option>
                                    @foreach ($dosen as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('dosen_wali_id') == $d->id ? 'selected' : '' }}>
                                            {{ $d->user->name ?? $d->nidn }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dosen_wali_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="angkatan" class="block text-sm font-medium text-gray-700">
                                    Angkatan
                                </label>
                                <input type="text" name="angkatan" id="angkatan"
                                    value="{{ old('angkatan', date('Y')) }}" maxlength="4" placeholder="Contoh: 2024"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('angkatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Baris 5: Tanggal Masuk --}}
                        <div class="mb-4">
                            <label for="tgl_masuk" class="block text-sm font-medium text-gray-700">
                                Tanggal Masuk
                            </label>
                            <input type="date" name="tgl_masuk" id="tgl_masuk" value="{{ old('tgl_masuk') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('tgl_masuk')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="flex gap-4 mt-6">
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                                Simpan
                            </button>
                            <a href="{{ route('biodata.index') }}"
                                class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
