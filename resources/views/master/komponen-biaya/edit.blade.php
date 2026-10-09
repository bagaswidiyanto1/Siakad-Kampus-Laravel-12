<x-app-layout>
    <x-slot name="title">Edit Komponen Biaya</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Komponen Biaya Baru') }}
            </h2>
            <a href="{{ route('master.komponen-biaya.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.komponen-biaya.update', $komponenBiaya->id) }}"
                        class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="kode" class="block text-sm font-medium text-gray-700">Kode *</label>
                                <input type="text" name="kode" id="kode"
                                    value="{{ old('kode', $komponenBiaya->kode) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('kode')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama *</label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama', $komponenBiaya->nama) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis *</label>
                            <select name="jenis" id="jenis" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="ukt"
                                    {{ old('jenis', $komponenBiaya->jenis) == 'ukt' ? 'selected' : '' }}>UKT</option>
                                <option value="spp"
                                    {{ old('jenis', $komponenBiaya->jenis) == 'spp' ? 'selected' : '' }}>SPP</option>
                                <option value="praktikum"
                                    {{ old('jenis', $komponenBiaya->jenis) == 'praktikum' ? 'selected' : '' }}>
                                    Praktikum
                                </option>
                                <option value="seminar"
                                    {{ old('jenis', $komponenBiaya->jenis) == 'seminar' ? 'selected' : '' }}>Seminar
                                </option>
                                <option value="wisuda"
                                    {{ old('jenis', $komponenBiaya->jenis) == 'wisuda' ? 'selected' : '' }}>Wisuda
                                </option>
                                <option value="lainnya"
                                    {{ old('jenis', $komponenBiaya->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                            @error('jenis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nominal" class="block text-sm font-medium text-gray-700">Nominal (Rp) *</label>
                            <input type="text" name="nominal" id="nominal"
                                value="{{ old('nominal', $komponenBiaya->nominal) }}" data-number required
                                min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('nominal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="semester_berlaku" class="block text-sm font-medium text-gray-700">Semester
                                Berlaku</label>
                            <input type="text" name="semester_berlaku" id="semester_berlaku"
                                value="{{ old('semester_berlaku', $komponenBiaya->semester_berlaku) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('semester_berlaku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="prodi_id" class="block text-sm font-medium text-gray-700">Program Studi
                                (Opsional)</label>
                            <select name="prodi_id" id="prodi_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @foreach ($prodi as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('prodi_id', $komponenBiaya->prodi_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->kode }} - {{ $item->nama }} ({{ $item->jenjang }})
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $komponenBiaya->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Aktif</span>
                            </label>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                            <a href="{{ route('master.komponen-biaya.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
