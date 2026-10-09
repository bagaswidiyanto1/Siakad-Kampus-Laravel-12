<x-app-layout>
    <x-slot name="title">Daftar Dosen</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Dosen') }}
            </h2>
            <a href="{{ route('master.dosen.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                + Tambah Dosen
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                            {{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">{{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="dosen-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIDN</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gelar</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Spesialisasi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">

                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                const table = $('#dosen-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('master.dosen.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            width: '50px'
                        },
                        {
                            data: 'nidn',
                            name: 'nidn'
                        },
                        {
                            data: 'nama_dosen',
                            name: 'nama_dosen',
                            orderable: false,
                            searchable: false,
                            className: 'font-medium text-gray-900'
                        },
                        {
                            data: 'gelar_lengkap',
                            name: 'gelar_depan',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'spesialisasi_text',
                            name: 'spesialisasi'
                        },
                        {
                            data: 'status_badge',
                            name: 'status',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-right',
                            width: '280px'
                        },
                    ],
                    order: [
                        [2, 'asc']
                    ],
                    drawCallback: function() {
                        window.initAjaxPlugins();
                    },
                    language: {
                        sEmptyTable: "Belum ada data dosen",
                        sProcessing: "Memproses...",
                        sZeroRecords: "Tidak ditemukan",
                        sSearch: "Cari:",
                        sLengthMenu: "Tampilkan _MENU_ entri",
                        sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        sInfoEmpty: "Menampilkan 0 entri",
                        sInfoFiltered: "(disaring dari _MAX_ entri)",
                        oPaginate: {
                            sFirst: "Pertama",
                            sPrevious: "Sebelumnya",
                            sNext: "Selanjutnya",
                            sLast: "Terakhir"
                        }
                    },
                });

                // Handler tombol Reset Password (event delegation)
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('.btn-reset-password');
                    if (!btn || btn.dataset.bound === '1') return;
                    btn.dataset.bound = '1';

                    e.preventDefault();

                    const url = btn.dataset.url;
                    const name = btn.dataset.name || 'dosen ini';

                    Swal.fire({
                        title: 'Reset Password?',
                        text: 'Password ' + name + ' akan direset menjadi "password".',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7c3aed',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, reset',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (!result.isConfirmed) return;

                        Swal.fire({
                            title: 'Memproses...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading(),
                        });

                        axios.post(url)
                            .then((response) => {
                                Swal.close();
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.data.message ||
                                        'Password berhasil direset',
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                            })
                            .catch((error) => {
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: error.response?.data?.message ||
                                        'Terjadi kesalahan.',
                                });
                            });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
