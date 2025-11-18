@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Detail Absensi</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Informasi lengkap data absensi</p>
                </div>
                <a href="{{ route('absensi.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Absensi</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Siswa Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                            Data Siswa
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Nama Siswa:</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $absensi->siswa->nama_lengkap }}</span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">NIS:</span>
                                <span class="text-gray-900 dark:text-white">
                                    @if($absensi->siswa->nis)
                                        {{ $absensi->siswa->nis }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Kelas:</span>
                                <span class="text-gray-900 dark:text-white">{{ $absensi->kelas->nama_kelas }}</span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin:</span>
                                <span class="text-gray-900 dark:text-white">{{ $absensi->siswa->jenis_kelamin }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Absensi Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                            Data Absensi
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Tanggal:</span>
                                <span class="text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                                @php
                                    $statusColors = [
                                        'Hadir' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'Izin' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'Sakit' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'Alpha' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$absensi->status] }}">
                                    {{ $absensi->status }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Waktu Absen:</span>
                                <span class="text-gray-900 dark:text-white">
                                    @if($absensi->waktu_absen)
                                        {{ \Carbon\Carbon::parse($absensi->waktu_absen)->format('H:i:s') }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Metode Absen:</span>
                                <span class="text-gray-900 dark:text-white">{{ $absensi->metode_absen ?? 'Manual' }}</span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Keterangan:</span>
                                <span class="text-gray-900 dark:text-white">
                                    @if($absensi->keterangan)
                                        {{ $absensi->keterangan }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection