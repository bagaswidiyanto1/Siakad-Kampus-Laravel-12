<div class="space-y-4">
    <div>
        <label for="alamat_ktp" class="block text-sm font-medium text-gray-700">Alamat KTP <span
                class="text-red-600">*</span></label>
        <textarea name="alamat_ktp" id="alamat_ktp" rows="3" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">{{ old('alamat_ktp', $mahasiswa->alamat_ktp) }}</textarea>
        @error('alamat_ktp')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="alamat_domisili" class="block text-sm font-medium text-gray-700">Alamat Domisili</label>
        <textarea name="alamat_domisili" id="alamat_domisili" rows="3" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">{{ old('alamat_domisili', $mahasiswa->alamat_domisili) }}</textarea>
        @error('alamat_domisili')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="no_telepon" class="block text-sm font-medium text-gray-700">No Telepon <span
                    class="text-red-600">*</span></label>
            <input type="text" name="no_telepon" id="no_telepon"
                value="{{ old('no_telepon', $mahasiswa->no_telepon) }}" {{ $isReadOnly ? 'disabled' : '' }}
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            @error('no_telepon')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="no_hp" class="block text-sm font-medium text-gray-700">No HP <span
                    class="text-red-600">*</span></label>
            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp) }}"
                {{ $isReadOnly ? 'disabled' : '' }}
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            @error('no_hp')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email_pribadi" class="block text-sm font-medium text-gray-700">Email Pribadi <span
                    class="text-red-600">*</span></label>
            <input type="email" name="email_pribadi" id="email_pribadi"
                value="{{ old('email_pribadi', $mahasiswa->email_pribadi) }}" {{ $isReadOnly ? 'disabled' : '' }}
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            @error('email_pribadi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
