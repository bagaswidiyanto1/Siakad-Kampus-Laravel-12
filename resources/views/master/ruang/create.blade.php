<x-app-layout>
    <x-slot name="title">Tambah Ruang</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Ruang Baru') }}
            </h2>
            <a href="{{ route('master.ruang.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.ruang.store') }}" class="ajax-form">
                        @csrf

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="kode" class="block text-sm font-medium text-gray-700">Kode *</label>
                                <input type="text" name="kode" id="kode" value="{{ old('kode') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('kode')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama *</label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="gedung" class="block text-sm font-medium text-gray-700">Gedung</label>
                                <input type="text" name="gedung" id="gedung" value="{{ old('gedung') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('gedung')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas
                                    *</label>
                                <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', 40) }}"
                                    required min="1" max="500"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('kapasitas')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis *</label>
                            <select name="jenis" id="jenis" data-tom-select required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Pilih Jenis</option>
                                <option value="kelas" {{ old('jenis') == 'kelas' ? 'selected' : '' }}>Kelas</option>
                                <option value="lab" {{ old('jenis') == 'lab' ? 'selected' : '' }}>Laboratorium
                                </option>
                                <option value="auditorium" {{ old('jenis') == 'auditorium' ? 'selected' : '' }}>
                                    Auditorium</option>
                                <option value="ruang_seminar" {{ old('jenis') == 'ruang_seminar' ? 'selected' : '' }}>
                                    Ruang Seminar</option>
                            </select>
                            @error('jenis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="fasilitas" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                            <textarea name="fasilitas" id="fasilitas" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('fasilitas') }}</textarea>
                            @error('fasilitas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                            <a href="{{ route('master.ruang.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
