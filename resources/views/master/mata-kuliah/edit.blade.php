<x-app-layout>
    <x-slot name="title">Edit Mata Kuliah</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Mata Kuliah Baru') }}
            </h2>
            <a href="{{ route('master.mata-kuliah.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.mata-kuliah.update', $mataKuliah->id) }}"
                        class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="kode" class="block text-sm font-medium text-gray-700">Kode *</label>
                                <input type="text" name="kode" id="kode"
                                    value="{{ old('kode', $mataKuliah->kode) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('kode')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama *</label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama', $mataKuliah->nama) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="sks" class="block text-sm font-medium text-gray-700">SKS *</label>
                                <input type="number" name="sks" id="sks"
                                    value="{{ old('sks', $mataKuliah->sks) }}" required min="1" max="6"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('sks')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="semester" class="block text-sm font-medium text-gray-700">Semester *</label>
                                <input type="number" name="semester" id="semester"
                                    value="{{ old('semester', $mataKuliah->semester) }}" required min="1"
                                    max="14"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('semester')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis *</label>
                            <select data-tom-select name="jenis" id="jenis" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="wajib"
                                    {{ old('jenis', $mataKuliah->jenis) == 'wajib' ? 'selected' : '' }}>Wajib</option>
                                <option value="pilihan"
                                    {{ old('jenis', $mataKuliah->jenis) == 'pilihan' ? 'selected' : '' }}>Pilihan
                                </option>
                            </select>
                            @error('jenis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="prodi_id" class="block text-sm font-medium text-gray-700">Program Studi
                                    *</label>
                                <select data-tom-select name="prodi_id" id="prodi_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('prodi_id', $mataKuliah->prodi_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->kode }} - {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('prodi_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="kurikulum_id" class="block text-sm font-medium text-gray-700">Kurikulum
                                    *</label>
                                <select data-tom-select name="kurikulum_id" id="kurikulum_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    @foreach ($kurikulum as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('kurikulum_id', $mataKuliah->kurikulum_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }} ({{ $item->tahun_berlaku }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kurikulum_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('deskripsi', $mataKuliah->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $mataKuliah->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Aktif</span>
                            </label>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                            <a href="{{ route('master.mata-kuliah.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
