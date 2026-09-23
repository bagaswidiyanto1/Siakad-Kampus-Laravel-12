<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-500">Role Anda</h4>
                            <p class="text-2xl font-bold text-blue-600 mt-1">
                                {{ Auth::user()->roles->first()->name ?? 'tidak ada' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-500">Username</h4>
                            <p class="text-2xl font-bold text-green-600 mt-1">
                                {{ Auth::user()->username }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-500">Email</h4>
                            <p class="text-2xl font-bold text-purple-600 mt-1 text-sm">
                                {{ Auth::user()->email ?? 'Belum diisi' }}
                            </p>
                        </div>
                    </div>

                    <!-- Info tambahan untuk testing -->
                    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="font-semibold text-yellow-800">💡 Info Testing</h4>
                        <ul class="text-sm text-yellow-700 mt-2 space-y-1">
                            <li>• Anda bisa menggunakan tombol Quick Login di halaman login untuk berpindah role dengan
                                cepat.</li>
                            <li>• Password default untuk semua user: <strong
                                    class="font-mono bg-yellow-100 px-2 py-0.5 rounded">password</strong></li>
                            <li>• Username untuk login: NIM (mahasiswa), NIDN (dosen), atau username manual (staff)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
