@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header Dashboard -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard Administrator</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Statistik sistem absensi</p>
            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Terakhir update: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Siswa -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalSiswa }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Terdaftar</p>
                    </div>
                </div>
            </div>

            <!-- Total Kelas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Kelas</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalKelas }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Terdaftar</p>
                    </div>
                </div>
            </div>

            <!-- Total Guru -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Guru</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalGuru }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Aktif</p>
                    </div>
                </div>
            </div>

            <!-- QR Generated -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Barcode</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $qrGenerated }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $totalSiswa > 0 ? round(($qrGenerated / $totalSiswa) * 100, 1) : 0 }}% 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection