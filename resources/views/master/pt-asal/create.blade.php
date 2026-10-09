<x-app-layout>
    <x-slot name="title">Tambah PT Asal</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Perguruan Tinggi Asal Baru') }}
            </h2>
            <a href="{{ route('master.pt-asal.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.pt-asal.store') }}" class="ajax-form">
                        @csrf

                        {{-- Kode PT --}}
                        <div class="mb-4">
                            <label for="kode_pt" class="block text-sm font-medium text-gray-700">
                                Kode Perguruan Tinggi
                            </label>
                            <input type="text" name="kode_pt" id="kode_pt" value="{{ old('kode_pt') }}"
                                maxlength="20" placeholder="Contoh: 001001"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('kode_pt')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama PT --}}
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                Nama Perguruan Tinggi *
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                maxlength="200" placeholder="Contoh: Universitas Gadjah Mada"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis + Kota --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="jenis" class="block text-sm font-medium text-gray-700">
                                    Jenis *
                                </label>
                                <select name="jenis" id="jenis" required data-tom-select
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Pilih Jenis</option>
                                    @foreach (['PTN', 'PTS', 'Politeknik', 'Akademi'] as $jenis)
                                        <option value="{{ $jenis }}"
                                            {{ old('jenis', 'PTS') == $jenis ? 'selected' : '' }}>
                                            {{ $jenis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="kota" class="block text-sm font-medium text-gray-700">
                                    Kota
                                </label>
                                <input type="text" name="kota" id="kota" value="{{ old('kota') }}"
                                    maxlength="100" placeholder="Contoh: Yogyakarta"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('kota')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                            <a href="{{ route('master.pt-asal.index') }}"
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
