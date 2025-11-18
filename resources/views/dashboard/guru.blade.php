@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header Khusus Guru -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard Guru</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Selamat datang,
                    {{ auth()->user()->guru->nama_lengkap ?? 'Guru' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1" id="last-updated">Terakhir update:
                    {{ now()->format('H:i:s') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('absensi.scanner') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                    Scan QR Code
                </a>
                <a href="{{ route('absensi.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Absensi Manual
                </a>
            </div>
        </div>

        <!-- Kelas yang Diampu -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b dark:border-gray-600 pb-2">🏫 Kelas
                yang Saya Ampu</h2>

            @if($kelasDiampu->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($kelasDiampu as $kelas)
                        <div
                            class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition duration-200 bg-white dark:bg-gray-700">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $kelas->nama_kelas }}</h3>
                                <span
                                    class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">
                                    {{ $kelas->siswa_count }} siswa
                                </span>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex justify-between">
                                    <span>Hadir Hari Ini:</span>
                                    <span
                                        class="font-semibold text-green-600 dark:text-green-400">{{ $kelas->hadir_hari_ini }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Tidak Hadir:</span>
                                    <span
                                        class="font-semibold text-red-600 dark:text-red-400">{{ $kelas->tidak_hadir_hari_ini }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Persentase:</span>
                                    <span
                                        class="font-semibold {{ $kelas->persentase_hadir >= 80 ? 'text-green-600 dark:text-green-400' : ($kelas->persentase_hadir >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ $kelas->persentase_hadir }}%
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 flex space-x-2">
                                <a href="{{ route('absensi.rekap-harian') }}?kelas_id={{ $kelas->id }}"
                                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded text-sm flex items-center justify-center transition duration-200">
                                    Rekap Harian
                                </a>
                                <a href="{{ route('rekap.index') }}?kelas_id={{ $kelas->id }}"
                                    class="flex-1 bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded text-sm flex items-center justify-center transition duration-200">
                                    Rekap Bulanan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Anda belum menjadi wali kelas</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Hubungi administrator untuk ditugaskan sebagai wali
                        kelas</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Statistik Kehadiran Hari Ini -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b dark:border-gray-600 pb-2">
                        📊 Statistik Kehadiran Hari Ini
                        <span
                            class="text-sm font-normal text-gray-600 dark:text-gray-400">({{ \Carbon\Carbon::parse($tanggalHariIni)->translatedFormat('d F Y') }})</span>
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div
                            class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalHadirHariIni }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Hadir</div>
                            <div class="text-xs text-green-500 dark:text-green-400 mt-1">
                                {{ $totalSiswaDiampu > 0 ? round(($totalHadirHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div
                            class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-100 dark:border-yellow-800">
                            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalIzinHariIni }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Izin</div>
                            <div class="text-xs text-yellow-500 dark:text-yellow-400 mt-1">
                                {{ $totalSiswaDiampu > 0 ? round(($totalIzinHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div
                            class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalSakitHariIni }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Sakit</div>
                            <div class="text-xs text-blue-500 dark:text-blue-400 mt-1">
                                {{ $totalSiswaDiampu > 0 ? round(($totalSakitHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div
                            class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800">
                            <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $totalAlphaHariIni }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Alpha</div>
                            <div class="text-xs text-red-500 dark:text-red-400 mt-1">
                                {{ $totalSiswaDiampu > 0 ? round(($totalAlphaHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @if($totalSiswaDiampu > 0)
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                                <span>Persentase Kehadiran</span>
                                <span>{{ round(($totalHadirHariIni / $totalSiswaDiampu) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ ($totalHadirHariIni / $totalSiswaDiampu) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b dark:border-gray-600 pb-2">
                        ⚡ Aksi Cepat</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('absensi.scanner') }}"
                            class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg flex items-center justify-center font-semibold transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                </path>
                            </svg>
                            Scan QR Code
                        </a>
                        <a href="{{ route('absensi.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg flex items-center justify-center transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Absensi Manual
                        </a>
                        <a href="{{ route('absensi.rekap-harian') }}"
                            class="bg-purple-500 hover:bg-purple-600 text-white py-3 px-4 rounded-lg flex items-center justify-center transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Rekap Harian
                        </a>
                        <a href="{{ route('rekap.index') }}"
                            class="bg-orange-500 hover:bg-orange-600 text-white py-3 px-4 rounded-lg flex items-center justify-center transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Rekap Bulanan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Guru -->
            <div class="space-y-6">
                <!-- Info Guru -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b dark:border-gray-600 pb-2">
                        ℹ️ Informasi Saya</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Nama:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->guru->nama_lengkap ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">NIP:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->guru->nip ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Email:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->guru->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status:</span>
                            <span
                                class="font-semibold {{ (auth()->user()->guru->status ?? '') == 'Aktif' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ auth()->user()->guru->status ?? '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total Kelas:</span>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $kelasDiampu->count() }}
                                kelas</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total Siswa:</span>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $totalSiswaDiampu }} siswa</span>
                        </div>
                    </div>
                </div>

                <!-- Statistik Cepat -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b dark:border-gray-600 pb-2">
                        📈 Statistik Cepat</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Kehadiran Hari Ini:</span>
                            <span class="font-semibold text-green-600 dark:text-green-400">
                                {{ $totalSiswaDiampu > 0 ? round(($totalHadirHariIni / $totalSiswaDiampu) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Siswa Hadir:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ $totalHadirHariIni }}/{{ $totalSiswaDiampu }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Perlu Perhatian:</span>
                            <span class="font-semibold text-red-600 dark:text-red-400">{{ $totalAlphaHariIni }} siswa</span>
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

        // Initialize tooltips jika menggunakan library tooltip
        document.addEventListener('DOMContentLoaded', function () {
            // Tambahkan efek hover pada card kelas
            const kelasCards = document.querySelectorAll('.border-gray-200');
            kelasCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection