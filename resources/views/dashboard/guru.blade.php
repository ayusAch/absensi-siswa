@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-7xl">
    <!-- Header Khusus Guru -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Guru</h1>
            <p class="text-gray-600 mt-2">Selamat datang, {{ auth()->user()->guru->nama_lengkap ?? 'Guru' }}</p>
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
            <a href="{{ route('absensi.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Absensi Manual
            </a>
        </div>
    </div>

    <!-- Kelas yang Diampu -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">🏫 Kelas yang Saya Ampu</h2>
        
        @if($kelasDiampu->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kelasDiampu as $kelas)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $kelas->nama_kelas }}</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                            {{ $kelas->siswa_count }} siswa
                        </span>
                    </div>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Hadir Hari Ini:</span>
                            <span class="font-semibold text-green-600">{{ $kelas->hadir_hari_ini }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tidak Hadir:</span>
                            <span class="font-semibold text-red-600">{{ $kelas->tidak_hadir_hari_ini }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Persentase:</span>
                            <span class="font-semibold {{ $kelas->persentase_hadir >= 80 ? 'text-green-600' : ($kelas->persentase_hadir >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $kelas->persentase_hadir }}%
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('absensi.rekap-harian') }}?kelas_id={{ $kelas->id }}" 
                           class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded text-sm flex items-center justify-center">
                            Lihat Rekap Kelas
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <p class="mt-4 text-lg text-gray-500">Anda belum menjadi wali kelas</p>
                <p class="text-sm text-gray-400">Hubungi administrator untuk ditugaskan sebagai wali kelas</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Statistik Kehadiran Hari Ini -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">
                    📊 Statistik Kehadiran Hari Ini
                    <span class="text-sm font-normal text-gray-600">({{ \Carbon\Carbon::parse($tanggalHariIni)->translatedFormat('d F Y') }})</span>
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-3xl font-bold text-green-600">{{ $totalHadirHariIni }}</div>
                        <div class="text-sm text-gray-600">Hadir</div>
                        <div class="text-xs text-green-500 mt-1">
                            {{ $totalSiswaDiampu > 0 ? round(($totalHadirHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                        </div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-3xl font-bold text-yellow-600">{{ $totalIzinHariIni }}</div>
                        <div class="text-sm text-gray-600">Izin</div>
                        <div class="text-xs text-yellow-500 mt-1">
                            {{ $totalSiswaDiampu > 0 ? round(($totalIzinHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                        </div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600">{{ $totalSakitHariIni }}</div>
                        <div class="text-sm text-gray-600">Sakit</div>
                        <div class="text-xs text-blue-500 mt-1">
                            {{ $totalSiswaDiampu > 0 ? round(($totalSakitHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                        </div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-3xl font-bold text-red-600">{{ $totalAlphaHariIni }}</div>
                        <div class="text-sm text-gray-600">Alpha</div>
                        <div class="text-xs text-red-500 mt-1">
                            {{ $totalSiswaDiampu > 0 ? round(($totalAlphaHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Persentase Kehadiran</span>
                        <span>{{ $totalSiswaDiampu > 0 ? round(($totalHadirHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full"
                            style="width: {{ $totalSiswaDiampu > 0 ? ($totalHadirHariIni / $totalSiswaDiampu) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">⚡ Aksi Cepat</h2>
                <div class="space-y-3">
                    <a href="{{ route('absensi.scanner') }}"
                        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg flex items-center justify-center font-semibold transition duration-200">
                        📱 Scan QR Code
                    </a>
                    <a href="{{ route('absensi.create') }}"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg flex items-center justify-center transition duration-200">
                        📝 Absensi Manual
                    </a>
                    <a href="{{ route('absensi.rekap-harian') }}"
                        class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-lg flex items-center justify-center transition duration-200">
                        📊 Rekap Harian
                    </a>
                </div>
            </div>

            <!-- Info Guru -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">ℹ️ Informasi Saya</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-semibold">{{ auth()->user()->guru->nama_lengkap ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">NIP:</span>
                        <span class="font-semibold">{{ auth()->user()->guru->nip ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold">{{ auth()->user()->guru->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold {{ (auth()->user()->guru->status ?? '') == 'Aktif' ? 'text-green-600' : 'text-red-600' }}">
                            {{ auth()->user()->guru->status ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Absensi Terbaru -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">📋 Absensi Terbaru Kelas Saya</h2>
            <a href="{{ route('absensi.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
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
                                    'Sakit' => 'bg-blue-100 text-blue-800',
                                    'Alpha' => 'bg-red-100 text-red-800'
                                ];
                                $color = $statusColors[$absensi->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
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
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
</script>
@endsection