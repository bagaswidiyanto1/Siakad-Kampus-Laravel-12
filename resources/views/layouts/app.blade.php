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

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: true }">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="flex flex-col min-h-screen transition-all duration-300 ease-in-out"
        :class="sidebarOpen ? 'ml-64' : 'ml-20'">

        <!-- Topbar -->
        @include('layouts.topbar')

        <!-- Page Content -->
        <main class="flex-1 mt-16 p-4">
            @isset($header)
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            @endisset

            {{ $slot }}
        </main>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
</body>

</html>
