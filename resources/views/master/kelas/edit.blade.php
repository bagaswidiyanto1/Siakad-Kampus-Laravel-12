<x-app-layout>
    <x-slot name="title">Edit Kelas</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Kelas Baru') }}
            </h2>
            <a href="{{ route('master.kelas.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.kelas.update', $kelas) }}" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kelas
                                    *</label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama', $kelas->nama) }}" required placeholder="A, B, C, D"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas
                                    *</label>
                                <input type="number" name="kapasitas" id="kapasitas"
                                    value="{{ old('kapasitas', $kelas->kapasitas) }}" required min="10"
                                    max="100"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('kapasitas')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="mata_kuliah_id" class="block text-sm font-medium text-gray-700">Mata Kuliah
                                *</label>
                            <select name="mata_kuliah_id" id="mata_kuliah_id" required data-tom-select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @foreach ($mataKuliah as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('mata_kuliah_id', $kelas->mata_kuliah_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->kode }} - {{ $item->nama }} ({{ $item->sks }} SKS)
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_kuliah_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="dosen_id" class="block text-sm font-medium text-gray-700">Dosen Pengajar
                                *</label>
                            <select name="dosen_id" id="dosen_id" required data-tom-select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @foreach ($dosen as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('dosen_id', $kelas->dosen_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nidn }} - {{ $item->user->name }}
                                        {{ $item->gelar_belakang ? ', ' . $item->gelar_belakang : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tahun_akademik_id" class="block text-sm font-medium text-gray-700">Tahun
                                Akademik *</label>
                            <select name="tahun_akademik_id" id="tahun_akademik_id" required data-tom-select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @foreach ($tahunAkademik as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('tahun_akademik_id', $kelas->tahun_akademik_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }} - {{ ucfirst($item->semester) }}
                                        {{ $item->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_akademik_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ruang" class="block text-sm font-medium text-gray-700">Ruang</label>
                            <input type="text" name="ruang" id="ruang"
                                value="{{ old('ruang', $kelas->ruang) }}" placeholder="Contoh: R. 301"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('ruang')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value=" "
                                    {{ old('is_active', $kelas->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Aktif</span>
                            </label>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                            <a href="{{ route('master.kelas.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
