<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIAKAD Kampus') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Alpine.js untuk interaktivitas -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-100">

    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Mobile backdrop: shown only when sidebar is open on small screens, tap to close -->
        <div x-show="sidebarOpen" x-cloak x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex flex-col min-h-screen transition-all duration-300 ease-in-out ml-0"
            :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">

            <!-- Topbar -->
            @include('layouts.topbar')

            <!-- Page Content -->
            <main class="flex-1 mt-16 p-4">
                @if (session('success'))
                    <div class="mb-4 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
                        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                        <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                        <svg class="h-5 w-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @isset($header)
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                @endisset

                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('click', function(e) {
            const input = e.target.closest('input[type="time"]');

            if (!input) {
                return;
            }

            if (typeof input.showPicker === 'function') {
                try {
                    input.showPicker();
                } catch (error) {
                    // Browser tidak mendukung atau picker tidak dapat dibuka
                }
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
