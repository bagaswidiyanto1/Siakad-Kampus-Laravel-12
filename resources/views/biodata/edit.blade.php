<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Biodata Mahasiswa') }}: {{ $mahasiswa->user->name }}
            </h2>
            <div class="flex gap-2">
                @if (!$isReadOnly)
                    <span class="text-sm text-green-600 bg-green-100 px-3 py-1 rounded-full">Mode Edit</span>
                @else
                    <span class="text-sm text-yellow-600 bg-yellow-100 px-3 py-1 rounded-full">Mode Lihat</span>
                @endif
                <a href="{{ route('biodata.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Alert summary (global) --}}
                    <div id="alertMessage" class="hidden mb-4"></div>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Form + Tabs --}}
                    <div x-data="biodataForm()" class="mb-6">

                        {{-- Tab Navigation --}}
                        <div class="border-b border-gray-200 overflow-x-auto">
                            <nav class="-mb-px flex flex-nowrap gap-1" aria-label="Tabs">
                                @php
                                    $tabs = [
                                        'data-diri' => 'Data Diri',
                                        'akademik' => 'Akademik',
                                        'alamat' => 'Alamat & Kontak',
                                        'orang-tua' => 'Orang Tua',
                                        'wali' => 'Wali',
                                        'sekolah' => 'Data Sekolah',
                                        'pt-asal' => 'Perguruan Tinggi Asal',
                                        'riwayat' => 'Riwayat Pendidikan',
                                        'ktm' => 'KTM',
                                    ];
                                @endphp
                                @foreach ($tabs as $key => $label)
                                    <button type="button" @click="activeTab = '{{ $key }}'"
                                        :class="{
                                            'bg-blue-600 text-white rounded-lg': activeTab === '{{ $key }}',
                                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '{{ $key }}'
                                        }"
                                        class="px-4 py-2 border-b-2 font-medium text-sm transition-colors duration-200 whitespace-nowrap">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </nav>
                        </div>

                        {{-- Form --}}
                        <form id="biodataForm" class="mt-6" onsubmit="return false;">
                            @csrf
                            @method('PUT')

                            @if (auth()->user()->hasRole('baa') || auth()->user()->hasRole('superadmin'))
                                <input type="hidden" name="nim" id="hidden_nim" value="{{ $mahasiswa->nim }}">
                                <input type="hidden" name="name" id="hidden_name"
                                    value="{{ $mahasiswa->user->name }}">
                            @endif

                            {{-- Tab Content --}}
                            <div class="mt-6">
                                <div x-show="activeTab === 'data-diri'" x-cloak>@include('biodata.partials.tab-data-diri')</div>
                                <div x-show="activeTab === 'akademik'" x-cloak>@include('biodata.partials.tab-akademik')</div>
                                <div x-show="activeTab === 'alamat'" x-cloak>@include('biodata.partials.tab-alamat')</div>
                                <div x-show="activeTab === 'orang-tua'" x-cloak>@include('biodata.partials.tab-orang-tua')</div>
                                <div x-show="activeTab === 'wali'" x-cloak>@include('biodata.partials.tab-wali')</div>
                                <div x-show="activeTab === 'sekolah'" x-cloak>@include('biodata.partials.tab-sekolah')</div>
                                <div x-show="activeTab === 'pt-asal'" x-cloak>@include('biodata.partials.tab-pt-asal')</div>
                                <div x-show="activeTab === 'riwayat'" x-cloak>@include('biodata.partials.tab-riwayat-pendidikan')</div>
                                <div x-show="activeTab === 'ktm'" x-cloak>@include('biodata.partials.tab-ktm')</div>
                            </div>

                            {{-- Tombol Simpan --}}
                            @if (!$isReadOnly)
                                <div class="flex justify-end gap-4 mt-6 border-t pt-6">
                                    <button type="button" @click="submitForm()" :disabled="loading"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span x-show="!loading">Simpan Perubahan</span>
                                        <span x-show="loading">Menyimpan...</span>
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @push('scripts')
        <script>
            // ======================================================
            // Alpine component: biodataForm()
            // ======================================================
            window.biodataForm = function() {
                return {
                    activeTab: 'data-diri',
                    loading: false,

                    submitForm() {
                        const form = document.getElementById('biodataForm');
                        if (!form) return;

                        // Bersihkan error lama
                        this.clearFieldErrors();
                        this.hideAlert();

                        // Loading ON
                        this.loading = true;

                        const formData = new FormData(form);

                        fetch("{{ route('biodata.update', $mahasiswa->id) }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: formData,
                            })
                            .then(async (response) => {
                                const data = await response.json();
                                if (!response.ok) throw data;
                                return data;
                            })
                            .then((data) => {
                                this.loading = false;

                                if (data.success) {
                                    // Sukses: alert hijau
                                    this.showAlert('success', data.message || 'Data berhasil disimpan!');

                                    // Update form dengan data baru
                                    if (data.data && data.data.mahasiswa) {
                                        this.updateFormData(data.data.mahasiswa);
                                    }

                                    // Toast SweetAlert2 (kalau tersedia)
                                    if (window.Swal) {
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'success',
                                            title: data.message || 'Data berhasil disimpan!',
                                            showConfirmButton: false,
                                            timer: 2000,
                                        });
                                    }
                                } else {
                                    // Gagal validasi
                                    if (data.errors) this.displayFieldErrors(data.errors);
                                    this.showAlert('error', data.message || 'Validasi gagal.');
                                }
                            })
                            .catch((error) => {
                                this.loading = false;

                                // Error validasi 422 (dikirim dari server sebagai response body)
                                if (error && error.errors) {
                                    this.displayFieldErrors(error.errors);
                                    this.showAlert('error', error.message || 'Validasi gagal.');
                                } else {
                                    this.showAlert('error', error?.message || 'Terjadi kesalahan, coba lagi.');
                                }
                            });
                    },

                    // ==========================================
                    // Bersihkan semua error lama di bawah input
                    // ==========================================
                    clearFieldErrors() {
                        document.querySelectorAll('.field-error-text').forEach(el => el.remove());
                        document.querySelectorAll('.field-error-border').forEach(el => {
                            el.classList.remove('field-error-border', 'border-red-500');
                        });
                    },

                    // ==========================================
                    // Suntikkan pesan error di bawah tiap input yang salah
                    // ==========================================
                    displayFieldErrors(errors) {
                        const fieldToTab = {
                            nisn: 'data-diri',
                            nik: 'data-diri',
                            tempat_lahir: 'data-diri',
                            tanggal_lahir: 'data-diri',
                            jenis_kelamin: 'data-diri',
                            agama: 'data-diri',
                            status_pernikahan: 'data-diri',
                            kewarganegaraan: 'data-diri',
                            alamat_ktp: 'alamat',
                            alamat_domisili: 'alamat',
                            no_telepon: 'alamat',
                            no_hp: 'alamat',
                            email_pribadi: 'alamat',
                            nim: 'akademik',
                            name: 'data-diri',
                            angkatan: 'akademik',
                            status_mahasiswa: 'akademik',
                            fakultas_id: 'akademik',
                            prodi_id: 'akademik',
                            dosen_wali_id: 'akademik',
                            tgl_masuk: 'akademik',
                            tgl_lulus: 'akademik',
                            foto: 'data-diri',
                            // orang_tua.* → orang-tua
                            // wali.* → wali
                        };

                        let firstErrorInput = null;
                        let firstErrorTab = null;

                        Object.entries(errors).forEach(([field, messages]) => {
                            // Konversi "orang_tua.nama_ayah" → "orang_tua[nama_ayah]"
                            const bracketName = field.replace(/\./g, '[').replace(/$/, ']');

                            // Cari input berdasar name persis atau versi bracket
                            let input = document.querySelector(`[name="${field}"]`) ||
                                document.querySelector(`[name="${bracketName}"]`);

                            if (!input) return;

                            // Tandai border merah
                            input.classList.add('border-red-500', 'field-error-border');

                            // Buat / update pesan error di bawah input
                            let errorEl = input.parentNode.querySelector('.field-error-text');
                            if (!errorEl) {
                                errorEl = document.createElement('p');
                                errorEl.className = 'text-red-500 text-xs mt-1 field-error-text';
                                input.parentNode.appendChild(errorEl);
                            }
                            const msg = Array.isArray(messages) ? messages[0] : messages;
                            errorEl.textContent = msg;

                            // Tentukan tab yang harus dibuka
                            const prefix = field.split('.')[0];
                            let tab = fieldToTab[prefix] || fieldToTab[prefix === 'orang_tua' ? 'orang_tua' :
                                prefix];

                            // Untuk sub-field orang_tua.* dan wali.*
                            if (prefix === 'orang_tua') tab = 'orang-tua';
                            if (prefix === 'wali') tab = 'wali';
                            if (prefix === 'riwayat_pendidikan') tab = 'riwayat';
                            if (prefix === 'data_sekolah' || prefix === 'sekolah_asal_id') tab = 'sekolah';
                            if (prefix === 'pt_asal' || prefix === 'pt_asal_id') tab = 'pt-asal';
                            if (prefix === 'ktm') tab = 'ktm';

                            if (!firstErrorInput) {
                                firstErrorInput = input;
                                firstErrorTab = tab;
                            }
                        });

                        // Buka tab yang berisi error pertama
                        if (firstErrorTab) this.activeTab = firstErrorTab;

                        // Scroll ke error pertama
                        if (firstErrorInput) {
                            setTimeout(() => {
                                firstErrorInput.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }, 100);
                        }
                    },

                    // ==========================================
                    // Alert summary (kotak merah / hijau di atas form)
                    // ==========================================
                    showAlert(type, message) {
                        const alertDiv = document.getElementById('alertMessage');
                        if (!alertDiv) return;

                        alertDiv.className = 'mb-4 border-l-4 p-4';
                        if (type === 'success') {
                            alertDiv.classList.add('bg-green-100', 'text-green-700', 'border-green-500');
                        } else {
                            alertDiv.classList.add('bg-red-100', 'text-red-700', 'border-red-500');
                        }
                        alertDiv.innerHTML = message;
                    },

                    hideAlert() {
                        const alertDiv = document.getElementById('alertMessage');
                        if (alertDiv) {
                            alertDiv.className = 'hidden mb-4';
                            alertDiv.innerHTML = '';
                        }
                    },

                    // ==========================================
                    // Update form dengan data baru (setelah sukses)
                    // ==========================================
                    updateFormData(data) {
                        const fields = [
                            'nisn', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                            'agama', 'status_pernikahan', 'kewarganegaraan', 'angkatan',
                            'status_mahasiswa', 'fakultas_id', 'prodi_id', 'dosen_wali_id',
                            'tgl_masuk', 'tgl_lulus', 'alamat_ktp', 'alamat_domisili',
                            'no_telepon', 'no_hp', 'email_pribadi',
                        ];

                        fields.forEach(field => {
                            const el = document.querySelector(`[name="${field}"]`);
                            if (el && data[field] !== undefined) {
                                el.value = data[field] || '';
                            }
                        });

                        const hiddenNim = document.getElementById('hidden_nim');
                        const hiddenName = document.getElementById('hidden_name');
                        if (hiddenNim && data.nim !== undefined) hiddenNim.value = data.nim || '';
                        if (hiddenName && data.user && data.user.name !== undefined) hiddenName.value = data.user.name ||
                            '';
                    },
                };
            };
        </script>
    @endpush
</x-app-layout>
