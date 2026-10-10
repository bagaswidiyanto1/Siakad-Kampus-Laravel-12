<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Mahasiswa') }}
            </h2>

            <a href="{{ route('biodata.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                + Tambah Mahasiswa
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

                    <!-- Filter -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <input type="text" id="filter-search" placeholder="Cari NIM/Nama..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <select id="filter-fakultas"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Semua Fakultas</option>
                                @foreach ($fakultas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select id="filter-prodi"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Semua Prodi</option>
                                @foreach ($prodi as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select id="filter-status"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Semua Status</option>
                                @foreach ($statusList as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" id="btn-reset"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm">
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Tabel -->
                    <div class="overflow-x-auto">
                        <table id="mahasiswa-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIM</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prodi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Angkatan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- data diisi DataTables via AJAX --}}
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
                const table = $('#mahasiswa-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('biodata.index') }}",
                        data: function(d) {
                            d.fakultas_id = $('#filter-fakultas').val();
                            d.prodi_id = $('#filter-prodi').val();
                            d.status = $('#filter-status').val();
                            // Search dikirim dari input filter-search
                            d.search_custom = $('#filter-search').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            width: '50px'
                        },
                        {
                            data: 'nim',
                            name: 'nim'
                        },
                        {
                            data: 'nama_mahasiswa',
                            name: 'nama_mahasiswa',
                            orderable: false,
                            searchable: false,
                            className: 'font-medium text-gray-900'
                        },
                        {
                            data: 'prodi_nama',
                            name: 'prodi_nama',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'angkatan_text',
                            name: 'angkatan'
                        },
                        {
                            data: 'status_badge',
                            name: 'status_mahasiswa',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            width: '100px'
                        },
                    ],
                    order: [
                        [1, 'asc']
                    ],
                    // Matikan search box bawaan DataTables (kita pakai input filter-search sendiri)
                    searching: false,
                    drawCallback: function() {
                        window.initAjaxPlugins();
                    },
                    language: {
                        sEmptyTable: "Tidak ada data mahasiswa",
                        sProcessing: "Memproses...",
                        sZeroRecords: "Tidak ditemukan",
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

                // Filter dropdown: langsung reload
                $('#filter-fakultas, #filter-prodi, #filter-status').on('change', function() {
                    table.ajax.reload();
                });

                // Input search: pakai debounce supaya tidak spam request
                let debounceTimer;
                $('#filter-search').on('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        table.ajax.reload();
                    }, 400);
                });

                // Tombol Filter
                $('#btn-filter').on('click', function() {
                    table.ajax.reload();
                });

                // Reset
                $('#btn-reset').on('click', function() {
                    $('#filter-search').val('');
                    $('#filter-fakultas').val('');
                    $('#filter-prodi').val('');
                    $('#filter-status').val('');
                    table.ajax.reload();
                });
            });
        </script>
    @endpush
</x-app-layout>
