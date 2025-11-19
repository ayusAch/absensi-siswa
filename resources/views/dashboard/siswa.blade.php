@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header Khusus Siswa -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard Siswa</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Selamat datang,
                    {{ auth()->user()->siswa->nama_lengkap ?? 'Siswa' }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1" id="last-updated">Terakhir update:
                    {{ now()->format('H:i:s') }}
                </p>
            </div>
        </div>

        <!-- Statistik Kehadiran Bulan Ini -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b dark:border-gray-600 pb-2">
                Statistik Kehadiran Bulan Ini
                <span
                    class="text-sm font-normal text-gray-600 dark:text-gray-400">({{ \Carbon\Carbon::now()->translatedFormat('F Y') }})</span>
            </h2>

            @if(isset($statistikBulanan) && $totalHariAktif > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div
                        class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $statistikBulanan['hadir'] ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Hadir</div>
                        <div class="text-xs text-green-500 dark:text-green-400 mt-1">
                            {{ round((($statistikBulanan['hadir'] ?? 0) / $totalHariAktif) * 100, 1) }}%
                        </div>
                    </div>
                    <div
                        class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-100 dark:border-yellow-800">
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ $statistikBulanan['izin'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Izin</div>
                        <div class="text-xs text-yellow-500 dark:text-yellow-400 mt-1">
                            {{ round((($statistikBulanan['izin'] ?? 0) / $totalHariAktif) * 100, 1) }}%
                        </div>
                    </div>
                    <div
                        class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $statistikBulanan['sakit'] ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Sakit</div>
                        <div class="text-xs text-blue-500 dark:text-blue-400 mt-1">
                            {{ round((($statistikBulanan['sakit'] ?? 0) / $totalHariAktif) * 100, 1) }}%
                        </div>
                    </div>
                    <div
                        class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800">
                        <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $statistikBulanan['alpha'] ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Alpha</div>
                        <div class="text-xs text-red-500 dark:text-red-400 mt-1">
                            {{ round((($statistikBulanan['alpha'] ?? 0) / $totalHariAktif) * 100, 1) }}%
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                        <span>Persentase Kehadiran Bulan Ini</span>
                        <span>{{ round((($statistikBulanan['hadir'] ?? 0) / $totalHariAktif) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-500"
                            style="width: {{ (($statistikBulanan['hadir'] ?? 0) / $totalHariAktif) * 100 }}%">
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Belum ada data kehadiran</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Data kehadiran akan muncul setelah Anda melakukan
                        absensi</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Informasi Pribadi & Quick Actions -->
            <div class="lg:col-span-2">
                <!-- INFORMASI KELAS -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b dark:border-gray-600 pb-2">
                        Informasi Kelas
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Kelas</div>
                            <div class="text-2xl font-bold text-blue-800 dark:text-blue-200 mt-1">
                                {{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}
                            </div>
                        </div>

                        <div
                            class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800">
                            <div class="text-sm text-green-600 dark:text-green-400 font-medium">Wali Kelas</div>
                            <div class="text-lg font-semibold text-green-800 dark:text-green-200 mt-1">
                                {{ $siswa->kelas->wali_kelas ?? '-' }} <!-- GUNAKAN ACCESSOR -->
                            </div>
                        </div>

                        <div
                            class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-100 dark:border-purple-800">
                            <div class="text-sm text-purple-600 dark:text-purple-400 font-medium">Tahun Ajaran</div>
                            <div class="text-lg font-semibold text-purple-800 dark:text-purple-200 mt-1">
                                {{ $siswa->kelas->tahun_ajaran ?? '-' }}
                            </div>
                        </div>

                        <div
                            class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-100 dark:border-orange-800">
                            <div class="text-sm text-orange-600 dark:text-orange-400 font-medium">Status</div>
                            <div class="text-lg font-semibold text-orange-800 dark:text-orange-200 mt-1">
                                <span
                                    class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded-full text-sm">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Siswa -->
            <div class="space-y-6">
                <!-- Info Pribadi -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b dark:border-gray-600 pb-2">
                        ℹ️ Informasi Saya</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">NIS:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->siswa->nis ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Nama:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->siswa->nama_lengkap ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Email:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Jenis Kelamin:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->siswa->jenis_kelamin ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tanggal Lahir:</span>
                            <span
                                class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->siswa->tanggal_lahir ? \Carbon\Carbon::parse(auth()->user()->siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
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
    </script>
@endsection