<x-app-layout>
    <x-slot name="title">Daftar Fakultas</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Fakultas') }}
            </h2>
            <a href="{{ route('master.fakultas.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                + Tambah Fakultas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Success/Error Messages -->
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

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table id="fakultas-table" class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
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
                $('#fakultas-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('master.fakultas.index') }}",
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
                            width: '140px'
                        },
                    ],
                    order: [
                        [2, 'asc']
                    ],
                    drawCallback: function() {
                        window.initAjaxPlugins();
                    },
                    language: {
                        sEmptyTable: "Belum ada data fakultas",
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
