<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="sekolah_asal_id" class="block text-sm font-medium text-gray-700">Sekolah Asal</label>
        <select name="sekolah_asal_id" id="sekolah_asal_id" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            <option value="">Pilih Sekolah</option>
            @foreach ($sekolahAsal as $item)
                <option value="{{ $item->id }}"
                    {{ old('sekolah_asal_id', $mahasiswa->sekolah_asal_id ?? '') == $item->id ? 'selected' : '' }}>
                    {{ $item->nama }} - {{ $item->kota }}
                </option>
            @endforeach
        </select>
        @error('sekolah_asal_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-4">
        <!-- Informasi tambahan bisa ditampilkan di sini -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Data ini akan digunakan untuk dropdown di biodata</p>
            <p class="text-xs text-gray-500 mt-1">* Data diambil dari master Sekolah Asal</p>
        </div>
    </div>
</div>
