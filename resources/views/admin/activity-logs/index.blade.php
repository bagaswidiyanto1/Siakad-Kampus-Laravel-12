<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Activity Log</h1>
                <p class="text-gray-600 mt-1 text-sm">Riwayat semua aktivitas pengguna dalam sistem</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.activity-logs.export', request()->query()) }}"
                    class="inline-flex items-center px-3 py-2 bg-green-600 rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33A3 3 0 0115.75 19.5M4.75 12H12" />
                    </svg>
                    Export CSV
                </a>
                <form action="{{ route('admin.activity-logs.clear') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus semua activity log?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-3 py-2 bg-red-600 rounded-md font-semibold text-xs text-white uppercase hover:bg-red-700">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Hapus Semua
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white shadow-sm sm:rounded-lg mb-4 p-4">
            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                    <select id="filter-event"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Aksi</option>
                        @foreach ($events as $event)
                            <option value="{{ $event }}">{{ ucfirst($event) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                    <select id="filter-subject"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Model</option>
                        @foreach ($subjectTypes as $type)
                            <option value="{{ $type }}">{{ class_basename($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" id="filter-start"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" id="filter-end"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                    <button type="button" id="btn-reset"
                        class="px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-800 uppercase hover:bg-gray-300">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="bg-white shadow-sm sm:rounded-lg p-3">
            <div class="overflow-x-auto">
                <table id="activity-logs-table" class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Model</th>
                            <th>Detail</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const table = $('#activity-logs-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.activity-logs.index') }}",
                        data: function(d) {
                            d.event = $('#filter-event').val();
                            d.subject_type = $('#filter-subject').val();
                            d.start_date = $('#filter-start').val();
                            d.end_date = $('#filter-end').val();
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
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'causer_name',
                            name: 'causer_name',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'event_badge',
                            name: 'event',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'subject_name',
                            name: 'subject_type',
                            orderable: false
                        },
                        {
                            data: 'detail',
                            name: 'detail',
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
                        [1, 'desc']
                    ],
                    drawCallback: function() {
                        window.initAjaxPlugins();
                    },
                    language: {
                        sEmptyTable: "Tidak ada activity log",
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

                // Event filter
                $('#btn-filter').on('click', function() {
                    table.ajax.reload();
                });

                // Reset filter
                $('#btn-reset').on('click', function() {
                    $('#filter-event').val('');
                    $('#filter-subject').val('');
                    $('#filter-start').val('');
                    $('#filter-end').val('');
                    table.ajax.reload();
                });

                // Optional: auto reload on select change
                $('#filter-event, #filter-subject').on('change', function() {
                    table.ajax.reload();
                });

                $('#filter-start, #filter-end').on('change', function() {
                    table.ajax.reload();
                });
            });
        </script>
    @endpush
</x-app-layout>
