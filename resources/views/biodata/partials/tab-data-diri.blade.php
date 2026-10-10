<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- ========================================== -->
    <!-- UPLOAD FOTO DENGAN DELETE DI BINGKAI      -->
    <!-- ========================================== -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
        <div class="flex items-start gap-6">
            <!-- Preview Foto dengan tombol delete di pojok -->
            <div class="flex-shrink-0 relative">
                <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-200 bg-gray-100 flex items-center justify-center"
                    id="fotoPreviewContainer">
                    @if ($mahasiswa->foto)
                        <img src="{{ Storage::disk('public')->url($mahasiswa->foto) }}"
                            alt="Foto {{ $mahasiswa->user->name }}" class="w-32 h-32 rounded-full object-cover">
                    @else
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    @endif
                </div>
                <!-- Tombol Delete di pojok kanan atas -->
                @if ($mahasiswa->foto && !$isReadOnly)
                    <button type="button" onclick="deleteFotoBiodata()"
                        class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-md transition duration-200"
                        title="Hapus Foto">
                        ✕
                    </button>
                @endif
            </div>

            <!-- Tombol Upload -->
            <div class="flex-1 space-y-2">
                @if (!$isReadOnly)
                    <div class="flex items-center gap-3">
                        <label for="fotoInput"
                            class="cursor-pointer bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition duration-200">
                            📷 Pilih Foto
                        </label>
                        <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden"
                            onchange="previewFoto(this)">
                        <span class="text-xs text-gray-500">Max 2MB (JPG, PNG)</span>
                    </div>
                    <div id="uploadProgress" class="hidden w-full">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 rounded-full h-2.5 transition-all duration-500" style="width: 0%"
                                id="progressBar"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" id="uploadStatus">Mengupload...</p>
                    </div>
                    <p class="text-xs text-gray-400 mt-1" id="fotoMessage"></p>
                    <p class="text-xs text-gray-400">* Foto akan tersimpan saat klik "Simpan Perubahan"</p>
                @else
                    <p class="text-sm text-gray-500">Foto profil (readonly)</p>
                @endif
            </div>
        </div>
    </div>


    <div>
        <label class="block text-sm font-medium text-gray-700">NIM <span class="text-red-600">*</span></label>
        @if (auth()->user()->hasRole('baa') || auth()->user()->hasRole('superadmin'))
            <input type="text" name="nim" id="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            <p class="text-xs text-blue-500 mt-1">🔑 BAA/Superadmin dapat mengubah NIM</p>
        @else
            <input type="text" value="{{ $mahasiswa->nim }}" disabled
                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
            <p class="text-xs text-gray-500 mt-1">NIM tidak dapat diubah</p>
        @endif
        @error('nim')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-600">*</span></label>
        @if (auth()->user()->hasRole('baa') || auth()->user()->hasRole('superadmin'))
            <input type="text" name="name" id="name" value="{{ old('name', $mahasiswa->user->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            <p class="text-xs text-blue-500 mt-1">🔑 BAA/Superadmin dapat mengubah nama</p>
        @else
            <input type="text" value="{{ $mahasiswa->user->name }}" disabled
                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
            <p class="text-xs text-gray-500 mt-1">Nama diambil dari data user</p>
        @endif
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="nisn" class="block text-sm font-medium text-gray-700">NISN <span
                class="text-red-600">*</span></label>
        <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $mahasiswa->nisn) }}"
            {{ $isReadOnly ? 'disabled' : '' }} inputmode="numeric" pattern="[0-9]*" x-data
            x-on:input="$event.target.value = $event.target.value.replace(/\D/g, '').slice(0, 16)"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('nisn')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="nik" class="block text-sm font-medium text-gray-700">NIK <span
                class="text-red-600">*</span></label>
        <input type="text" name="nik" id="nik" value="{{ old('nik', $mahasiswa->nik) }}"
            {{ $isReadOnly ? 'disabled' : '' }} maxlength="16" inputmode="numeric" pattern="[0-9]*" x-data
            x-on:input="$event.target.value = $event.target.value.replace(/\D/g, '').slice(0, 16)"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('nik')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir <span
                class="text-red-600">*</span></label>
        <input type="text" name="tempat_lahir" id="tempat_lahir"
            value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir) }}" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('tempat_lahir')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span
                class="text-red-600">*</span></label>
        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
            value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->format('Y-m-d') : '') }}"
            {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
        @error('tanggal_lahir')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span
                class="text-red-600">*</span></label>
        <select name="jenis_kelamin" id="jenis_kelamin" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            <option value="">Pilih</option>
            @foreach ($jenisKelaminList as $jk)
                <option value="{{ $jk }}"
                    {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == $jk ? 'selected' : '' }}>
                    {{ $jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </option>
            @endforeach
        </select>
        @error('jenis_kelamin')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="agama" class="block text-sm font-medium text-gray-700">Agama <span
                class="text-red-600">*</span></label>
        <select name="agama" id="agama" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            <option value="">Pilih</option>
            @foreach ($agamaList as $agama)
                <option value="{{ $agama }}"
                    {{ old('agama', $mahasiswa->agama) == $agama ? 'selected' : '' }}>
                    {{ $agama }}
                </option>
            @endforeach
        </select>
        @error('agama')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="status_pernikahan" class="block text-sm font-medium text-gray-700">Status Pernikahan</label>
        <select name="status_pernikahan" id="status_pernikahan" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            @foreach ($statusPernikahanList as $status)
                <option value="{{ $status }}"
                    {{ old('status_pernikahan', $mahasiswa->status_pernikahan) == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
        @error('status_pernikahan')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="kewarganegaraan" class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
        <select name="kewarganegaraan" id="kewarganegaraan" {{ $isReadOnly ? 'disabled' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ $isReadOnly ? 'bg-gray-100' : '' }}">
            @foreach ($kewarganegaraanList as $negara)
                <option value="{{ $negara }}"
                    {{ old('kewarganegaraan', $mahasiswa->kewarganegaraan) == $negara ? 'selected' : '' }}>
                    {{ $negara }}
                </option>
            @endforeach
        </select>
        @error('kewarganegaraan')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- ========================================== -->
<!-- JAVASCRIPT PREVIEW & DELETE FOTO          -->
<!-- ========================================== -->
@push('scripts')
    <script>
        // Preview foto sebelum submit (tanpa upload terpisah)
        function previewFoto(input) {
            const file = input.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                showMessage('fotoMessage', '❌ Format file tidak didukung! (JPG, PNG)', 'red');
                input.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                showMessage('fotoMessage', '❌ Ukuran file melebihi 2MB!', 'red');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.getElementById('fotoPreviewContainer');
                container.innerHTML =
                    `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover" id="fotoPreview">`;

                // Tambahkan tombol delete jika belum ada
                const parent = container.parentElement;
                let deleteBtn = parent.querySelector('button[onclick*="deleteFotoBiodata"]');
                if (!deleteBtn) {
                    deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className =
                        'absolute -top-1 -right-1 bg-red-500 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-md transition duration-200';
                    deleteBtn.innerHTML = '✕';
                    deleteBtn.title = 'Hapus Foto';
                    deleteBtn.onclick = function() {
                        deleteFotoBiodata();
                    };
                    parent.appendChild(deleteBtn);
                }

                showMessage('fotoMessage', '✅ Foto siap diupload (simpan perubahan untuk menyimpan)', 'green');
            };
            reader.readAsDataURL(file);
        }

        // Delete Foto Biodata (AJAX terpisah)
        function deleteFotoBiodata() {
            if (!confirm('Yakin ingin menghapus foto ini?')) return;

            fetch('{{ route('biodata.delete-foto') }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reset preview ke default
                        const container = document.getElementById('fotoPreviewContainer');
                        container.innerHTML = `<svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>`;

                        // Hapus tombol delete
                        const parent = container.parentElement;
                        const deleteBtn = parent.querySelector('button[onclick*="deleteFotoBiodata"]');
                        if (deleteBtn) deleteBtn.remove();

                        showMessage('fotoMessage', '✅ ' + data.message, 'green');
                    } else {
                        showMessage('fotoMessage', '❌ ' + data.message, 'red');
                    }
                })
                .catch(error => {
                    showMessage('fotoMessage', '❌ Terjadi kesalahan!', 'red');
                    console.error('Error:', error);
                });
        }

        function showMessage(elementId, message, type) {
            const el = document.getElementById(elementId);
            el.textContent = message;
            el.className = 'text-xs mt-1 ' + (type === 'green' ? 'text-green-600' : 'text-red-600');
        }
    </script>
@endpush
