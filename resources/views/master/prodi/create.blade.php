<x-app-layout>
    <x-slot name="title">Tambah Program Studi</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Program Studi Baru') }}
            </h2>
            <a href="{{ route('master.prodi.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.prodi.store') }}" class="ajax-form">
                        @csrf

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

                        <div class="mb-4">
                            <label for="jenjang" class="block text-sm font-medium text-gray-700">Jenjang *</label>
                            <select name="jenjang" id="jenjang" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Pilih Jenjang</option>
                                <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="D4" {{ old('jenjang') == 'D4' ? 'selected' : '' }}>D4</option>
                                <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('jenjang') == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                            @error('jenjang')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="fakultas_id" class="block text-sm font-medium text-gray-700">Fakultas *</label>
                            <select name="fakultas_id" id="fakultas_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Pilih Fakultas</option>
                                @foreach ($fakultas as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('fakultas_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->kode }} - {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fakultas_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                            <a href="{{ route('master.prodi.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
