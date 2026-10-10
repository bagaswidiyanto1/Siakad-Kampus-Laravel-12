<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="orang_tua_nama_ayah" class="block text-sm font-medium text-gray-700">Nama Ayah <span
                class="text-red-600">*</span></label>
        <input type="text" name="orang_tua[nama_ayah]" id="orang_tua_nama_ayah"
            value="{{ old('orang_tua.nama_ayah', $mahasiswa->orangTua->nama_ayah ?? '') }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('orang_tua.nama_ayah')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="orang_tua_nama_ibu" class="block text-sm font-medium text-gray-700">Nama Ibu <span
                class="text-red-600">*</span></label>
        <input type="text" name="orang_tua[nama_ibu]" id="orang_tua_nama_ibu"
            value="{{ old('orang_tua.nama_ibu', $mahasiswa->orangTua->nama_ibu ?? '') }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('orang_tua.nama_ibu')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="orang_tua_pekerjaan_ayah" class="block text-sm font-medium text-gray-700">Pekerjaan Ayah <span
                class="text-red-600">*</span></label>
        <input type="text" name="orang_tua[pekerjaan_ayah]" id="orang_tua_pekerjaan_ayah"
            value="{{ old('orang_tua.pekerjaan_ayah', $mahasiswa->orangTua->pekerjaan_ayah ?? '') }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('orang_tua.pekerjaan_ayah')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="orang_tua_pekerjaan_ibu" class="block text-sm font-medium text-gray-700">Pekerjaan Ibu <span
                class="text-red-600">*</span></label>
        <input type="text" name="orang_tua[pekerjaan_ibu]" id="orang_tua_pekerjaan_ibu"
            value="{{ old('orang_tua.pekerjaan_ibu', $mahasiswa->orangTua->pekerjaan_ibu ?? '') }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('orang_tua.pekerjaan_ibu')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="md:col-span-2">
        <label for="orang_tua_alamat_ortu" class="block text-sm font-medium text-gray-700">Alamat Orang Tua <span
                class="text-red-600">*</span></label>
        <textarea name="orang_tua[alamat_ortu]" id="orang_tua_alamat_ortu" rows="2" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">{{ old('orang_tua.alamat_ortu', $mahasiswa->orangTua->alamat_ortu ?? '') }}</textarea>
        @error('orang_tua.alamat_ortu')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="orang_tua_no_hp_ortu" class="block text-sm font-medium text-gray-700">No HP Orang Tua <span
                class="text-red-600">*</span></label>
        <input type="text" name="orang_tua[no_hp_ortu]" id="orang_tua_no_hp_ortu"
            value="{{ old('orang_tua.no_hp_ortu', $mahasiswa->orangTua->no_hp_ortu ?? '') }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('orang_tua.no_hp_ortu')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
