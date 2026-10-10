<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="angkatan" class="block text-sm font-medium text-gray-700">Angkatan</label>
        <input type="text" name="angkatan" id="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('angkatan')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="status_mahasiswa" class="block text-sm font-medium text-gray-700">Status Mahasiswa</label>
        <select name="status_mahasiswa" id="status_mahasiswa" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            @foreach ($statusList as $status)
                <option value="{{ $status }}"
                    {{ old('status_mahasiswa', $mahasiswa->status_mahasiswa) == $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        @error('status_mahasiswa')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="fakultas_id" class="block text-sm font-medium text-gray-700">Fakultas</label>
        <select name="fakultas_id" id="fakultas_id" {{ $isReadOnly ? 'disabled' : '' }} data-tom-select
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            <option value="">Pilih Fakultas</option>
            @foreach ($fakultas as $item)
                <option value="{{ $item->id }}"
                    {{ old('fakultas_id', $mahasiswa->fakultas_id) == $item->id ? 'selected' : '' }}>
                    {{ $item->nama }}
                </option>
            @endforeach
        </select>
        @error('fakultas_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="prodi_id" class="block text-sm font-medium text-gray-700">Program Studi</label>
        <select name="prodi_id" id="prodi_id" {{ $isReadOnly ? 'disabled' : '' }} data-tom-select
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            <option value="">Pilih Program Studi</option>
            @foreach ($prodi as $item)
                <option value="{{ $item->id }}"
                    {{ old('prodi_id', $mahasiswa->prodi_id) == $item->id ? 'selected' : '' }}>
                    {{ $item->nama }} ({{ $item->jenjang }})
                </option>
            @endforeach
        </select>
        @error('prodi_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="dosen_wali_id" class="block text-sm font-medium text-gray-700">Dosen Wali</label>
        <select name="dosen_wali_id" id="dosen_wali_id" {{ $isReadOnly ? 'disabled' : '' }} data-tom-select
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            <option value="">Pilih Dosen Wali</option>
            @foreach ($dosen as $item)
                <option value="{{ $item->id }}"
                    {{ old('dosen_wali_id', $mahasiswa->dosen_wali_id) == $item->id ? 'selected' : '' }}>
                    {{ $item->user->name }} ({{ $item->nidn }})
                </option>
            @endforeach
        </select>
        @error('dosen_wali_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="tgl_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
        <input type="date" name="tgl_masuk" id="tgl_masuk"
            value="{{ old('tgl_masuk', $mahasiswa->tgl_masuk ? $mahasiswa->tgl_masuk->format('Y-m-d') : '') }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('tgl_masuk')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="tgl_lulus" class="block text-sm font-medium text-gray-700">Tanggal Lulus</label>
        <input type="date" name="tgl_lulus" id="tgl_lulus"
            value="{{ old('tgl_lulus', $mahasiswa->tgl_lulus ? $mahasiswa->tgl_lulus->format('Y-m-d') : '') }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('tgl_lulus')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
