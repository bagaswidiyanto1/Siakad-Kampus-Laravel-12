<x-app-layout>
    <x-slot name="title">Edit Dosen</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Dosen Baru') }}
            </h2>
            <a href="{{ route('master.dosen.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('master.dosen.update', $dosen->id) }}" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN *</label>
                                <input type="text" name="nidn" id="nidn"
                                    value="{{ old('nidn', $dosen->nidn) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('nidn')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap
                                    *</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $dosen->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="gelar_depan" class="block text-sm font-medium text-gray-700">Gelar
                                    Depan</label>
                                <input type="text" name="gelar_depan" id="gelar_depan"
                                    value="{{ old('gelar_depan', $dosen->gelar_depan) }}" placeholder="Dr., Prof., dll"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('gelar_depan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="gelar_belakang" class="block text-sm font-medium text-gray-700">Gelar
                                    Belakang</label>
                                <input type="text" name="gelar_belakang" id="gelar_belakang"
                                    value="{{ old('gelar_belakang', $dosen->gelar_belakang) }}"
                                    placeholder="M.Kom., Ph.D., dll"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('gelar_belakang')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="spesialisasi"
                                class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                            <input type="text" name="spesialisasi" id="spesialisasi"
                                value="{{ old('spesialisasi', $dosen->spesialisasi) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('spesialisasi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="jabatan_fungsional" class="block text-sm font-medium text-gray-700">Jabatan
                                    Fungsional</label>
                                <select name="jabatan_fungsional" id="jabatan_fungsional" data-tom-select
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Asisten Ahli"
                                        {{ old('jabatan_fungsional', $dosen->jabatan_fungsional) == 'Asisten Ahli' ? 'selected' : '' }}>
                                        Asisten
                                        Ahli</option>
                                    <option value="Lektor"
                                        {{ old('jabatan_fungsional', $dosen->jabatan_fungsional) == 'Lektor' ? 'selected' : '' }}>
                                        Lektor</option>
                                    <option value="Lektor Kepala"
                                        {{ old('jabatan_fungsional', $dosen->jabatan_fungsional) == 'Lektor Kepala' ? 'selected' : '' }}>
                                        Lektor
                                        Kepala</option>
                                    <option value="Guru Besar"
                                        {{ old('jabatan_fungsional', $dosen->jabatan_fungsional) == 'Guru Besar' ? 'selected' : '' }}>
                                        Guru Besar
                                    </option>
                                </select>
                                @error('jabatan_fungsional')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal
                                    Masuk</label>
                                <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                    value="{{ old('tanggal_masuk', $dosen->tanggal_masuk?->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @error('tanggal_masuk')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role *</label>
                            <select name="role" id="role" required data-tom-select
                                class="mt-1 block w-full rounded-md ...">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role', $currentRole) == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $dosen->user->email) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select name="status" id="status" required data-tom-select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="aktif"
                                    {{ old('status', $dosen->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="cuti"
                                    {{ old('status', $dosen->status) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="pensiun"
                                    {{ old('status', $dosen->status) == 'pensiun' ? 'selected' : '' }}>Pensiun
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="fakultas_id"
                                    class="block text-sm font-medium text-gray-700">Fakultas</label>
                                <select name="fakultas_id" id="fakultas_id" data-tom-select
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    @foreach ($fakultas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('fakultas_id', $dosen->fakultas_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->kode }} - {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fakultas_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="prodi_id" class="block text-sm font-medium text-gray-700">Program
                                    Studi</label>
                                <select name="prodi_id" id="prodi_id" data-tom-select
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('prodi_id', $dosen->prodi_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->kode }} - {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('prodi_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                            <a href="{{ route('master.dosen.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
