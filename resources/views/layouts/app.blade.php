<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white">
    <div class="flex h-screen bg-white dark:bg-gray-900">
        <!-- Sidebar -->
        <div class="w-64 bg-white dark:bg-gray-800 shadow-lg fixed h-full">
            <div class="p-4 border-b dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Sistem Absensi</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    @auth
                        {{ Auth::user()->name }}
                        <span class="block text-xs text-blue-500 mt-1">
                            {{ Auth::user()->role_display }}
                        </span>
                    @endauth
                </p>
            </div>

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Main Menu
                </div>

                <!-- Dashboard -->
                <a href="{{ url('/dashboard') }}"
                    class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 {{ request()->is('dashboard') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <!-- Management Siswa - Hanya untuk Admin -->
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('siswa.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 {{ request()->is('siswa*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        Siswa
                    </a>
                @endif

                <!-- Management Kelas - Hanya untuk Admin -->
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('kelas.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 {{ request()->is('kelas*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Kelas
                    </a>
                @endif

                <!-- Management Guru - Hanya untuk Admin -->
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('guru.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 {{ request()->is('guru*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Guru
                    </a>
                @endif

                <div
                    class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-4">
                    Absensi
                </div>

                <!-- Scanner QR - Untuk Guru dan Admin -->
                @if(auth()->user()->isGuru() || auth()->user()->isAdmin())
                    <a href="{{ route('absensi.scanner') }}"
                        class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 {{ request()->is('absensi/scanner') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        Scan QR
                    </a>
                @endif

                <!-- Data Absensi - Untuk Guru dan Admin -->
                @if(auth()->user()->isGuru() || auth()->user()->isAdmin())
                    <a href="{{ route('absensi.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 {{ request()->is('absensi') && !request()->is('absensi/scanner') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Data Absensi
                    </a>
                @endif

                <div
                    class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-4">
                    Laporan
                </div>

                <!-- Rekap Absensi - Untuk Guru dan Admin -->
                @if(auth()->user()->isGuru() || auth()->user()->isAdmin())
                    <a href="{{ route('rekap.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 {{ request()->is('rekap*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Rekap Absensi
                    </a>
                @endif

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="mt-8 border-t dark:border-gray-700 pt-4">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 bg-white dark:bg-gray-900">
            <!-- Navigation Bar (Top) -->
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="p-6 bg-white dark:bg-gray-900 min-h-screen">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Function to apply theme
            function applyTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    themeToggleLightIcon.classList.remove('hidden');
                    themeToggleDarkIcon.classList.add('hidden');
                } else {
                    document.documentElement.classList.remove('dark');
                    themeToggleDarkIcon.classList.remove('hidden');
                    themeToggleLightIcon.classList.add('hidden');
                }
            }

            // Initialize theme
            if (localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                applyTheme('dark');
            } else {
                applyTheme('light');
            }

            // Toggle theme
            if (themeToggle) {
                themeToggle.addEventListener('click', function () {
                    if (document.documentElement.classList.contains('dark')) {
                        localStorage.setItem('color-theme', 'light');
                        applyTheme('light');
                    } else {
                        localStorage.setItem('color-theme', 'dark');
                        applyTheme('dark');
                    }
                });
            }

            // User Dropdown Functionality
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function () {
                    userDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function (event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <!-- Script tambahan untuk halaman tertentu -->
    @stack('scripts')
</body>

</html>