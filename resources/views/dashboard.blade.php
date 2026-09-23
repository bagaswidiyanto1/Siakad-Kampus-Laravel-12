<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">
                            Selamat datang, {{ Auth::user()->name }}!
                        </h3>
                        <div class="mt-2 bg-blue-50 border-l-4 border-blue-500 p-4">
                            <p class="text-sm text-blue-700">
                                Anda login sebagai
                                <strong>{{ Auth::user()->roles->first()->name ?? 'tidak ada role' }}</strong>
                                dengan username: <strong>{{ Auth::user()->username }}</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h4 class="text-sm font-medium text-blue-600">Role</h4>
                            <p class="text-2xl font-bold text-blue-800 mt-1">
                                {{ Auth::user()->roles->first()->name ?? 'tidak ada' }}
                            </p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <h4 class="text-sm font-medium text-green-600">Username</h4>
                            <p class="text-2xl font-bold text-green-800 mt-1">
                                {{ Auth::user()->username }}
                            </p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                            <h4 class="text-sm font-medium text-purple-600">Email</h4>
                            <p class="text-sm font-bold text-purple-800 mt-1">
                                {{ Auth::user()->email ?? 'Belum diisi' }}
                            </p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <h4 class="text-sm font-medium text-yellow-600">Status</h4>
                            <p class="text-2xl font-bold text-yellow-800 mt-1">Aktif</p>
                        </div>
                    </div>

                    <!-- Info Testing -->
                    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="font-semibold text-yellow-800">💡 Info Testing</h4>
                        <ul class="text-sm text-yellow-700 mt-2 space-y-1">
                            <li>• Anda bisa menggunakan tombol Quick Login di halaman login untuk berpindah role dengan
                                cepat.</li>
                            <li>• Password default untuk semua user: <strong
                                    class="font-mono bg-yellow-100 px-2 py-0.5 rounded">password</strong></li>
                            <li>• Username untuk login: NIM (mahasiswa), NIDN (dosen), atau username manual (staff)</li>
                            <li>• Sidebar bisa di-collapse dengan klik tombol ☰ di pojok kiri atas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
