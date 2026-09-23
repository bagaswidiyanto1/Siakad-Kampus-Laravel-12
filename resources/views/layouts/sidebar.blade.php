<!-- Sidebar -->
<aside
    class="fixed top-0 left-0 h-screen bg-white border-r border-gray-200 flex flex-col z-50 transition-all duration-300 ease-in-out overflow-y-auto"
    :class="sidebarOpen ? 'w-64' : 'w-20'">

    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-200 flex-shrink-0">
        <span class="font-bold text-xl tracking-wide text-blue-600" x-show="sidebarOpen">SIAKAD</span>
        <span class="font-bold text-xl text-blue-600 mx-auto" x-show="!sidebarOpen" x-cloak>S</span>
    </div>

    <!-- Menu Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <!-- DASHBOARD -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            <span class="ml-3" x-show="sidebarOpen" x-cloak>Dashboard</span>
        </a>

        <!-- MASTER DATA (Dropdown) -->
        @canany(['master.akademik.manage', 'master.biaya.manage', 'master.perpus.manage', 'master.pengajuan.manage'])
            <div x-data="{ masterOpen: false }">
                <button @click="sidebarOpen && (masterOpen = !masterOpen)"
                    class="w-full flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600 focus:outline-none">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Master Data</span>
                    <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-transform duration-200"
                        :class="masterOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="masterOpen && sidebarOpen" x-transition x-cloak class="mt-1 space-y-1 pl-8">
                    @can('master.akademik.manage')
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Fakultas</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Program
                            Studi</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Tahun
                            Akademik</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Kurikulum</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Mata
                            Kuliah</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Kelas</a>
                    @endcan
                    @can('master.biaya.manage')
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Komponen
                            Biaya</a>
                    @endcan
                    @can('master.perpus.manage')
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Kategori
                            Buku</a>
                    @endcan
                    @can('master.pengajuan.manage')
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Jenis
                            Pengajuan</a>
                    @endcan
                </div>
            </div>
        @endcanany

        <!-- BIODATA -->
        @can('biodata.view')
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Biodata</span>
            </a>
        @endcan

        <!-- KRS -->
        @can('krs.view')
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>KRS</span>
            </a>
        @endcan

        <!-- BIAYA KULIAH -->
        @can('biaya.view')
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Biaya Kuliah</span>
            </a>
        @endcan

        <!-- BAHAN & TUGAS -->
        @canany(['materi.view', 'materi.manage', 'tugas.submit'])
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Bahan & Tugas</span>
            </a>
        @endcanany

        <!-- JADWAL & PRESENSI -->
        @canany(['jadwal.view', 'jadwal.manage', 'presensi.input', 'presensi.self'])
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Jadwal & Presensi</span>
            </a>
        @endcanany

        <!-- PA ONLINE -->
        @canany(['bimbingan.create', 'bimbingan.approve'])
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l4.586-4.586z">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>PA Online</span>
            </a>
        @endcanany

        <!-- KUESIONER -->
        @canany(['kuesioner.manage', 'kuesioner.isi'])
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Kuesioner</span>
            </a>
        @endcanany

        <!-- NILAI -->
        @canany(['nilai.input', 'nilai.view'])
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Nilai</span>
            </a>
        @endcanany

        <!-- PENGAJUAN (Dropdown) -->
        @can('pengajuan.create')
            <div x-data="{ pengajuanOpen: false }">
                <button @click="sidebarOpen && (pengajuanOpen = !pengajuanOpen)"
                    class="w-full flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600 focus:outline-none">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Pengajuan</span>
                    <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-transform duration-200"
                        :class="pengajuanOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="pengajuanOpen && sidebarOpen" x-transition x-cloak class="mt-1 space-y-1 pl-8">
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Surat</a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Cuti</a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Lock
                        Jurnal</a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Proposal</a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Kompre</a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Wisuda</a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Tugas
                        Akhir</a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Kerja
                        Praktik</a>
                </div>
            </div>
        @endcan

        <!-- PERPUSTAKAAN -->
        @canany(['perpus.pinjam', 'perpus.kembali', 'perpus.manage'])
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Perpustakaan</span>
            </a>
        @endcanany

        <!-- SKPI -->
        @canany(['skpi.create', 'skpi.verify', 'skpi.terbitkan'])
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>SKPI</span>
            </a>
        @endcanany

        <!-- TRACER STUDY -->
        @canany(['tracer.fill', 'tracer.rekap'])
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Tracer Study</span>
            </a>
        @endcanany

        <!-- PANDUAN -->
        @can('panduan.view')
            <a href="#"
                class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span class="ml-3" x-show="sidebarOpen" x-cloak>Panduan</span>
            </a>
        @endcan

        <!-- ADMIN MENU (Dropdown) - Hanya untuk superadmin -->
        @canany(['user.manage', 'permission.manage'])
            <div class="border-t border-gray-200 my-4"></div>

            <div x-data="{ adminOpen: false }">
                <button @click="sidebarOpen && (adminOpen = !adminOpen)"
                    class="w-full flex items-center px-4 py-3 rounded-lg transition-colors text-gray-600 hover:bg-gray-100 hover:text-blue-600 focus:outline-none">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Administrasi</span>
                    <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-transform duration-200"
                        :class="adminOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="adminOpen && sidebarOpen" x-transition x-cloak class="mt-1 space-y-1 pl-8">
                    @can('user.manage')
                        <a href="{{ route('admin.users.index') }}"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Kelola
                            User</a>
                    @endcan
                    @can('permission.manage')
                        <a href="{{ route('admin.permissions.index') }}"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-blue-600 rounded-lg">Hak
                            Akses</a>
                    @endcan
                </div>
            </div>
        @endcanany

    </nav>
</aside>
