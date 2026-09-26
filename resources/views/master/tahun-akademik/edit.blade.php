<x-app-layout>
    <x-slot name="title">Edit Tahun Akademik</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Tahun Akademik Baru') }}
            </h2>
            <a href="{{ route('master.tahun-akademik.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.tahun-akademik.update', $tahunAkademik->id) }}"
                        class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="kode" class="block text-sm font-medium text-gray-700">Kode *</label>
                            <input type="text" name="kode" id="kode"
                                value="{{ old('kode', $tahunAkademik->kode) }}" required placeholder="Contoh: 2024/2025"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('kode')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama *</label>
                            <input type="text" name="nama" id="nama"
                                value="{{ old('nama', $tahunAkademik->nama) }}" required
                                placeholder="Contoh: Tahun Akademik 2024/2025"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester *</label>
                            <select name="semester" id="semester" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Pilih Semester</option>
                                <option value="ganjil"
                                    {{ old('semester', $tahunAkademik->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil
                                </option>
                                <option value="genap"
                                    {{ old('semester', $tahunAkademik->semester) == 'genap' ? 'selected' : '' }}>Genap
                                </option>
                            </select>
                            @error('semester')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai
                                    *</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                    value="{{ old('tanggal_mulai', $tahunAkademik->tanggal_mulai?->format('Y-m-d')) }}"required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('tanggal_mulai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal
                                    Selesai *</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                    value="{{ old('tanggal_selesai', $tahunAkademik->tanggal_selesai?->format('Y-m-d')) }}"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('tanggal_selesai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active"
                                    value="{{ old('is_active', $tahunAkademik->is_active) ? '1' : '0' }}"
                                    {{ old('is_active', $tahunAkademik->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Aktif (hanya 1 yang bisa aktif)</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_current"
                                    value="{{ old('is_current', $tahunAkademik->is_current) ? '1' : '0' }}"
                                    {{ old('is_current', $tahunAkademik->is_current) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Periode Berjalan (hanya 1 yang bisa)</span>
                            </label>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                            <a href="{{ route('master.tahun-akademik.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
