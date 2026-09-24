<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Hak Akses (Permission)') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-4">

                <div class="mb-3">
                    <h3 class="text-lg font-semibold text-gray-800">Matriks Permission</h3>
                    <p class="text-sm text-gray-500">Centang untuk memberi akses. Tersimpan otomatis.</p>
                </div>

                <div class="overflow-x-auto">
                    <table id="permissions-table" class="w-full text-sm">
                        <thead class="bg-purple-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-900 uppercase">
                                    Permission</th>
                                @foreach ($roles as $role)
                                    <th
                                        class="px-3 py-3 text-center text-xs font-semibold text-purple-900 uppercase whitespace-nowrap">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-700">
                        <strong>Catatan:</strong> Permission untuk role <strong>Superadmin</strong>
                        tidak bisa diubah karena memiliki akses penuh.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const roleNames = @json($roles->pluck('name'));

            $(document).ready(function() {
                // Susun kolom: No, Permission, lalu 1 kolom per role
                const columns = [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '50px'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'font-mono text-xs text-gray-700'
                    },
                ];

                roleNames.forEach(function(role) {
                    columns.push({
                        data: 'role_' + role,
                        name: 'role_' + role,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                    });
                });

                $('#permissions-table').DataTable({
                    processing: true,
                    serverSide: true,

                    // Matikan pagination + info + length
                    paging: false,
                    info: false,
                    lengthChange: false,
                    ordering: false,

                    ajax: "{{ route('admin.permissions.index') }}",
                    columns: columns,

                    drawCallback: function() {
                        bindPermissionToggles();
                    },

                    language: {
                        sEmptyTable: "Tidak ada permission",
                        sProcessing: "Memproses...",
                        sZeroRecords: "Tidak ditemukan",
                        sSearch: "Cari:",
                    },
                });
            });

            // Bind toggle tiap redraw (checkbox lama digantikan oleh DataTables)
            function bindPermissionToggles() {
                document.querySelectorAll('.permission-toggle:not([data-bound])').forEach(function(checkbox) {
                    checkbox.setAttribute('data-bound', '1');

                    checkbox.addEventListener('change', function() {
                        const el = this;
                        const role = el.dataset.role;
                        const permission = el.dataset.permission;
                        const status = el.checked;

                        el.disabled = true;

                        fetch('{{ route('admin.permissions.toggle') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: JSON.stringify({
                                    role,
                                    permission,
                                    status
                                }),
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1800,
                                    });
                                } else {
                                    el.checked = !status;
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.message
                                    });
                                }
                            })
                            .catch(() => {
                                el.checked = !status;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan, coba lagi.'
                                });
                            })
                            .finally(() => {
                                if (role !== 'superadmin') el.disabled = false;
                            });
                    });
                });
            }
        </script>
    @endpush
</x-app-layout>
