@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-600 mt-2">Selamat datang di Sistem Absensi QR Code</p>
                <p class="text-sm text-gray-500 mt-1" id="last-updated">Terakhir update: {{ now()->format('H:i:s') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('absensi.scanner') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                    Scan QR Code
                </a>
                <a href="{{ route('rekap.index') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Lihat Rekap
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Siswa -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $jumlahSiswa }}</p>
                        <p class="text-xs text-gray-500 mt-1">Terdaftar dalam sistem</p>
                    </div>
                    <div class="text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Kelas -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Kelas</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $jumlahKelas }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $quickStats['kelas_aktif'] }} kelas aktif</p>
                    </div>
                    <div class="text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- QR Code Generated -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">QR Code Tergenerate</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $quickStats['qr_code_generated'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $jumlahSiswa > 0 ? round(($quickStats['qr_code_generated'] / $jumlahSiswa) * 100, 1) : 0 }}%
                            dari total siswa
                        </p>
                    </div>
                    <div class="text-purple-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Siswa Baru Bulan Ini -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Siswa Baru</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $quickStats['siswa_baru_bulan_ini'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Bulan {{ now()->translatedFormat('F') }}</p>
                    </div>
                    <div class="text-yellow-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Absensi Hari Ini -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">
                        📊 Statistik Kehadiran Hari Ini
                        <span
                            class="text-sm font-normal text-gray-600">({{ \Carbon\Carbon::parse($tanggalHariIni)->translatedFormat('l, d F Y') }})</span>
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $rekapHadir }}</div>
                            <div class="text-sm text-gray-600">Hadir</div>
                            <div class="text-xs text-green-500 mt-1">
                                {{ $jumlahSiswa > 0 ? round(($rekapHadir / $jumlahSiswa) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-600">{{ $rekapIzin }}</div>
                            <div class="text-sm text-gray-600">Izin</div>
                            <div class="text-xs text-yellow-500 mt-1">
                                {{ $jumlahSiswa > 0 ? round(($rekapIzin / $jumlahSiswa) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $rekapSakit }}</div>
                            <div class="text-sm text-gray-600">Sakit</div>
                            <div class="text-xs text-blue-500 mt-1">
                                {{ $jumlahSiswa > 0 ? round(($rekapSakit / $jumlahSiswa) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-red-600">{{ $rekapAlpha }}</div>
                            <div class="text-sm text-gray-600">Alpha</div>
                            <div class="text-xs text-red-500 mt-1">
                                {{ $jumlahSiswa > 0 ? round(($rekapAlpha / $jumlahSiswa) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Persentase Kehadiran</span>
                            <span>{{ $jumlahSiswa > 0 ? round(($rekapHadir / $jumlahSiswa) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                style="width: {{ $jumlahSiswa > 0 ? ($rekapHadir / $jumlahSiswa) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Simple Chart Placeholder -->
                    <div class="bg-gray-50 rounded-lg p-4 mt-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-sm font-semibold text-gray-700">Trend 7 Hari Terakhir</h3>
                            <span class="text-xs text-gray-500">Siswa Hadir</span>
                        </div>
                        <div class="flex items-end justify-between h-20">
                            @foreach($chartData['labels'] as $index => $label)
                                <div class="flex flex-col items-center">
                                    <div class="text-xs text-gray-500 mb-1">{{ $label }}</div>
                                    <div class="w-8 bg-blue-500 rounded-t transition-all duration-300 hover:bg-blue-600"
                                        style="height: {{ $chartData['total'][$index] > 0 ? ($chartData['hadir'][$index] / $chartData['total'][$index]) * 60 : 0 }}px;"
                                        title="{{ $chartData['hadir'][$index] }}/{{ $chartData['total'][$index] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kelas Terbaik & Quick Actions -->
            <div class="space-y-6">
                <!-- Top Classes -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">🏆 Kelas Terbaik Hari Ini</h2>
                    <div class="space-y-4">
                        @forelse($kelasTerbaik as $kelas)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $kelas['nama_kelas'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $kelas['wali_kelas'] ?? 'Wali Kelas' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-green-600">{{ $kelas['persentase'] }}%</div>
                                    <div class="text-xs text-gray-500">{{ $kelas['hadir'] }}/{{ $kelas['total_siswa'] }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-2 text-sm">Belum ada data kehadiran hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">⚡ Aksi Cepat</h2>
                    <div class="space-y-3">
                        <a href="{{ route('absensi.scanner') }}"
                            class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg flex items-center justify-center font-semibold transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                </path>
                            </svg>
                            Scan QR Code
                        </a>
                        <a href="{{ route('absensi.create') }}"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg flex items-center justify-center transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Absensi Manual
                        </a>
                        <a href="{{ route('siswa.create') }}"
                            class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-lg flex items-center justify-center transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            Tambah Siswa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Monthly Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Absensi Terbaru -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">📋 Absensi Terbaru</h2>
                    <a href="{{ route('absensi.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                        Lihat Semua →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Siswa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kelas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($absensiTerbaru as $absensi)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $absensi->siswa->nama_lengkap }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        {{ $absensi->kelas->nama_kelas }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'Hadir' => 'bg-green-100 text-green-800',
                                                'Izin' => 'bg-yellow-100 text-yellow-800',
                                                'Sakit' => 'bg-blue-100 text-blue-800'
                                            ];
                                            $color = $statusColors[$absensi->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                            {{ $absensi->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($absensi->created_at)->format('H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="mt-2">Belum ada absensi terbaru</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistik Bulanan -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">📈 Statistik Bulan
                    {{ now()->translatedFormat('F') }}</h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Hari Sekolah</span>
                        </div>
                        <span
                            class="font-bold text-blue-600">{{ $statistikBulanan['hari_sampai_sekarang'] }}/{{ $statistikBulanan['total_hari_sekolah'] }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-700">Total Hadir</span>
                        </div>
                        <span class="font-bold text-green-600">{{ $statistikBulanan['hadir_bulan_ini'] }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-700">Total Izin</span>
                        </div>
                        <span class="font-bold text-yellow-600">{{ $statistikBulanan['izin_bulan_ini'] }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Total Sakit</span>
                        </div>
                        <span class="font-bold text-blue-600">{{ $statistikBulanan['sakit_bulan_ini'] }}</span>
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-600 mb-2">Rata-rata Kehadiran Bulan Ini</div>
                        <div class="flex items-center">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full"
                                    style="width: {{ $statistikBulanan['total_kehadiran'] > 0 ? ($statistikBulanan['hadir_bulan_ini'] / $statistikBulanan['total_kehadiran']) * 100 : 0 }}%">
                                </div>
                            </div>
                            <span class="ml-3 text-sm font-semibold text-gray-700">
                                {{ $statistikBulanan['total_kehadiran'] > 0 ? round(($statistikBulanan['hadir_bulan_ini'] / $statistikBulanan['total_kehadiran']) * 100, 1) : 0 }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto-update last updated time
        function updateLastUpdated() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('last-updated').textContent = 'Terakhir update: ' + timeString;
        }

        // Update every 30 seconds
        setInterval(updateLastUpdated, 30000);

        // Optional: Auto-refresh data (uncomment if needed)
        /*
        function refreshDashboardData() {
            fetch('{{ route("dashboard.live-data") }}')
            .then(response => response.json())
            .then(data => {
                // Update specific elements with new data
                console.log('Data updated:', data);
            })
            .catch(error => console.error('Error updating data:', error));
        }

        // Refresh data every 60 seconds
        setInterval(refreshDashboardData, 60000);
        */
    </script>
@endsection