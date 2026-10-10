<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="wali_nama_wali" class="block text-sm font-medium text-gray-700">Nama Wali</label>
        <input type="text" name="wali[nama_wali]" id="wali_nama_wali"
            value="{{ old('wali.nama_wali', $mahasiswa->wali->nama_wali ?? '') }}" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('wali.nama_wali')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="wali_hubungan" class="block text-sm font-medium text-gray-700">Hubungan</label>
        <input type="text" name="wali[hubungan]" id="wali_hubungan"
            value="{{ old('wali.hubungan', $mahasiswa->wali->hubungan ?? '') }}" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('wali.hubungan')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="wali_pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
        <input type="text" name="wali[pekerjaan]" id="wali_pekerjaan"
            value="{{ old('wali.pekerjaan', $mahasiswa->wali->pekerjaan ?? '') }}" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('wali.pekerjaan')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="wali_no_hp" class="block text-sm font-medium text-gray-700">No HP Wali</label>
        <input type="text" name="wali[no_hp]" id="wali_no_hp"
            value="{{ old('wali.no_hp', $mahasiswa->wali->no_hp ?? '') }}" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('wali.no_hp')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="md:col-span-2">
        <label for="wali_alamat" class="block text-sm font-medium text-gray-700">Alamat Wali</label>
        <textarea name="wali[alamat]" id="wali_alamat" rows="2" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">{{ old('wali.alamat', $mahasiswa->wali->alamat ?? '') }}</textarea>
        @error('wali.alamat')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
