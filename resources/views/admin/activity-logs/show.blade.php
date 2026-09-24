<x-app-layout>
    <div class="py-12 px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Detail Activity Log</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap aktivitas pengguna</p>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}"
                class="px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                Kembali
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Tanggal & Waktu</p>
                        <p class="font-semibold">{{ $activity->created_at->format('d F Y H:i:s') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Pengguna</p>
                        <p class="font-semibold">{{ $activity->causer?->name ?? 'System' }}</p>
                        @if ($activity->causer)
                            <p class="text-sm text-gray-500">({{ $activity->causer->username ?? '-' }})</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Aksi</p>
                        <p>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $activity->event === 'created'
                                    ? 'bg-green-100 text-green-800'
                                    : ($activity->event === 'updated'
                                        ? 'bg-blue-100 text-blue-800'
                                        : ($activity->event === 'deleted'
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($activity->event) }}
                            </span>
                        </p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Model</p>
                        <p class="font-semibold">{{ class_basename($activity->subject_type) }}</p>
                        <p class="text-sm text-gray-500">ID: {{ $activity->subject_id ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                        <p class="text-sm text-gray-500">Deskripsi</p>
                        <p class="font-semibold">{{ $activity->description ?? '-' }}</p>
                    </div>
                    @if ($activity->properties && count($activity->properties) > 0)
                        <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                            <p class="text-sm text-gray-500">Data Perubahan</p>
                            <pre class="mt-2 p-3 bg-white border rounded text-sm overflow-auto max-h-96">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
