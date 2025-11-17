@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Guru</h1>
                <p class="text-gray-600 mt-2">Selamat datang, <strong>{{ $guru->nama_lengkap }}</strong></p>
                @if($guru->nip)
                    <p class="text-sm text-gray-500">NIP: {{ $guru->nip }}</p>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('absensi.scanner') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                    📱 Scan QR
                </a>
                <a href="{{ route('absensi.rekap-harian') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    📋 Rekap Harian
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Kelas yang Diampu</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $kelas->count() }}</p>
                    </div>
                    <div class="text-blue-500 text-2xl">🏫</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Absensi Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $absensiHariIni }}</p>
                        <p class="text-xs text-gray-500 mt-1">dari {{ $totalSiswa }} siswa</p>
                    </div>
                    <div class="text-green-500 text-2xl">✅</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $guru->status }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $guru->jenis_kelamin }}</p>
                    </div>
                    <div class="text-purple-500 text-2xl">👨‍🏫</div>
                </div>
            </div>
        </div>

        <!-- Kelas yang Diampu -->
        @if($kelas->count() > 0)
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Kelas yang Diampu</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($kelas as $kelasItem)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ $kelasItem->nama_kelas }}</h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    {{ $kelasItem->siswa->count() }} siswa
                                </p>
                                <a href="{{ route('rekap.detail-siswa', $kelasItem->id) }}?tanggal={{ today()->toDateString() }}"
                                    class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                    Lihat Detail →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <div class="text-yellow-500 text-4xl mb-3">🏫</div>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Belum Ada Kelas</h3>
                <p class="text-yellow-700">Anda belum ditugaskan sebagai wali kelas. Hubungi administrator.</p>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Aksi Cepat</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('absensi.scanner') }}"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200">
                        <div class="text-blue-500 text-2xl mr-4">📱</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Scan QR Absensi</h3>
                            <p class="text-sm text-gray-600">Scan QR code siswa untuk absensi</p>
                        </div>
                    </a>
                    <a href="{{ route('absensi.rekap-harian') }}"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition duration-200">
                        <div class="text-green-500 text-2xl mr-4">📊</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Rekap Harian</h3>
                            <p class="text-sm text-gray-600">Lihat rekap absensi hari ini</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection