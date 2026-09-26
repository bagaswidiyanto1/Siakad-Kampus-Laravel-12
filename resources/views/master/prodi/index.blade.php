<x-app-layout>
    <x-slot name="title">Daftar Program Studi</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Program Studi') }}
            </h2>
            <a href="{{ route('master.prodi.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                + Tambah Prodi
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
                        <table id="prodi-table" class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Jenjang</th>
                                    <th>Fakultas</th>
                                    <th>Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#prodi-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('master.prodi.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            width: '50px'
                        },
                        {
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'nama',
                            name: 'nama',
                            className: 'font-medium text-gray-900'
                        },
                        {
                            data: 'jenjang_badge',
                            name: 'jenjang',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'fakultas_nama',
                            name: 'fakultas_nama',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status_badge',
                            name: 'is_active',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-right',
                            width: '160px'
                        },
                    ],
                    order: [
                        [2, 'asc']
                    ],
                    drawCallback: function() {
                        window.initAjaxPlugins();
                    },
                    language: {
                        sEmptyTable: "Belum ada data program studi",
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
            });
        </script>
    @endpush
</x-app-layout>
