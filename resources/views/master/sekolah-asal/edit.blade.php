<x-app-layout>
    <x-slot name="title">Edit Sekolah Asal</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Sekolah Asal Baru') }}
            </h2>
            <a href="{{ route('master.sekolah-asal.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.sekolah-asal.update', $sekolahAsal) }}"
                        class="ajax-form">
                        @csrf
                        @method('PUT')

                        {{-- Nama Sekolah --}}
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                Nama Sekolah *
                            </label>
                            <input type="text" name="nama" id="nama"
                                value="{{ old('nama', $sekolahAsal->nama) }}" maxlength="200"
                                placeholder="Contoh: SMA Negeri 1 Yogyakarta"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenjang + Kota --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="jenjang" class="block text-sm font-medium text-gray-700">
                                    Jenjang *
                                </label>
                                <select name="jenjang" id="jenjang" required data-tom-select
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Pilih Jenjang</option>
                                    @foreach (['SMA', 'SMK', 'MA', 'Sederajat'] as $jenjang)
                                        <option value="{{ $jenjang }}"
                                            {{ old('jenjang', $sekolahAsal->jenjang) == $jenjang ? 'selected' : '' }}>
                                            {{ $jenjang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenjang')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="kota" class="block text-sm font-medium text-gray-700">
                                    Kota
                                </label>
                                <input type="text" name="kota" id="kota"
                                    value="{{ old('kota', $sekolahAsal->kota) }}" maxlength="100"
                                    placeholder="Contoh: Yogyakarta"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('kota')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- NPSN --}}
                        <div class="mb-4">
                            <label for="npsn" class="block text-sm font-medium text-gray-700">
                                NPSN
                            </label>
                            <input type="text" name="npsn" id="npsn"
                                value="{{ old('npsn', $sekolahAsal->npsn) }}" maxlength="20"
                                placeholder="Contoh: 20404123"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('npsn')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">
                                Alamat
                            </label>
                            <textarea name="alamat" id="alamat" rows="3" placeholder="Alamat lengkap sekolah"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('alamat', $sekolahAsal->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $sekolahAsal->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Aktif</span>
                            </label>
                        </div>


                        {{-- Tombol --}}
                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                            <a href="{{ route('master.sekolah-asal.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
