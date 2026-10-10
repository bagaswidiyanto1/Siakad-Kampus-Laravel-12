<div>
    <!-- Daftar Riwayat -->
    <div class="mb-4">
        <h4 class="text-lg font-semibold text-gray-700 mb-2">Daftar Riwayat Pendidikan</h4>

        @if ($mahasiswa->riwayatPendidikan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenjang</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Institusi</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($mahasiswa->riwayatPendidikan as $riwayat)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $riwayat->jenjang }}</td>
                                <td class="px-4 py-2 text-sm">{{ $riwayat->nama_institusi }}</td>
                                <td class="px-4 py-2 text-sm">{{ $riwayat->tahun_masuk }} -
                                    {{ $riwayat->tahun_lulus ?? 'Sekarang' }}</td>
                                <td class="px-4 py-2 text-sm">
                                    @if (!$isReadOnly)
                                        <button type="button"
                                            onclick="if(confirm('Yakin hapus riwayat ini?')) { deleteRiwayat({{ $riwayat->id }}) }"
                                            class="text-red-600 hover:text-red-900 text-sm">
                                            Hapus
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada riwayat pendidikan</p>
        @endif
    </div>

    <!-- Form Tambah Riwayat -->
    @if (!$isReadOnly)
        <div class="mt-6 border-t pt-4">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Tambah Riwayat Pendidikan</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="riwayatForm">
                <div>
                    <label for="riwayat_jenjang" class="block text-sm font-medium text-gray-700">Jenjang</label>
                    <select id="riwayat_jenjang"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Pilih</option>
                        @foreach ($jenjangList as $jenjang)
                            <option value="{{ $jenjang }}">{{ $jenjang }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="riwayat_nama_institusi" class="block text-sm font-medium text-gray-700">Nama
                        Institusi</label>
                    <input type="text" id="riwayat_nama_institusi"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="riwayat_kota" class="block text-sm font-medium text-gray-700">Kota</label>
                    <input type="text" id="riwayat_kota"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="riwayat_jurusan" class="block text-sm font-medium text-gray-700">Jurusan</label>
                    <input type="text" id="riwayat_jurusan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="riwayat_tahun_masuk" class="block text-sm font-medium text-gray-700">Tahun Masuk</label>
                    <input type="number" id="riwayat_tahun_masuk" min="1980" max="{{ date('Y') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="riwayat_tahun_lulus" class="block text-sm font-medium text-gray-700">Tahun Lulus</label>
                    <input type="number" id="riwayat_tahun_lulus" min="1980" max="{{ date('Y') + 1 }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="riwayat_nilai_akhir" class="block text-sm font-medium text-gray-700">Nilai Akhir</label>
                    <input type="text" id="riwayat_nilai_akhir"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div class="flex items-end">
                    <button type="button" id="btnTambahRiwayat"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm w-full">
                        + Tambah Riwayat
                    </button>
                </div>
            </div>
            <div id="riwayatMessage" class="mt-2"></div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        function deleteRiwayat(id) {
            fetch(`{{ url('biodata/riwayat') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Gagal menghapus riwayat!');
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan!');
                    console.error(error);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btnTambah = document.getElementById('btnTambahRiwayat');
            if (btnTambah) {
                btnTambah.addEventListener('click', function() {
                    const data = {
                        jenjang: document.getElementById('riwayat_jenjang').value,
                        nama_institusi: document.getElementById('riwayat_nama_institusi').value,
                        kota: document.getElementById('riwayat_kota').value,
                        jurusan: document.getElementById('riwayat_jurusan').value,
                        tahun_masuk: document.getElementById('riwayat_tahun_masuk').value,
                        tahun_lulus: document.getElementById('riwayat_tahun_lulus').value,
                        nilai_akhir: document.getElementById('riwayat_nilai_akhir').value,
                    };

                    fetch(`{{ route('biodata.riwayat.store', $mahasiswa->id) }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            const msg = document.getElementById('riwayatMessage');
                            if (data.success) {
                                msg.innerHTML =
                                    `<div class="bg-green-100 text-green-700 p-2 rounded">${data.message}</div>`;
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                let errors = '';
                                if (data.errors) {
                                    for (let key in data.errors) {
                                        errors += data.errors[key].join(', ') + '<br>';
                                    }
                                }
                                msg.innerHTML =
                                    `<div class="bg-red-100 text-red-700 p-2 rounded">${errors || 'Gagal menambahkan riwayat!'}</div>`;
                            }
                        })
                        .catch(error => {
                            document.getElementById('riwayatMessage').innerHTML =
                                `<div class="bg-red-100 text-red-700 p-2 rounded">Terjadi kesalahan!</div>`;
                            console.error(error);
                        });
                });
            }
        });
    </script>
@endpush
