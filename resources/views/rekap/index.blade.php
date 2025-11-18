@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Rekap Absensi</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Monitor dan analisis data kehadiran siswa</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Cari Laporan</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('rekap.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Jenis Laporan -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Laporan</label>
                        <select name="type" id="type"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="harian" {{ request('type', 'harian') == 'harian' ? 'selected' : '' }}>Harian</option>
                            <option value="bulanan" {{ request('type') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>

                    <!-- Tanggal / Bulan -->
                    <div id="date-field">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal', $tanggal) }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <div id="month-field" class="hidden">
                        <label for="bulan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                        <input type="month" name="bulan" id="bulan" value="{{ request('bulan', $bulan) }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- Filter Kelas -->
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kelas</label>
                        <select name="kelas_id" id="kelas_id"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Filter -->
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistik Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $statistik['total_siswa'] }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Hadir Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $statistik['hadir_hari_ini'] }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">{{ $statistik['persen_hadir'] }}%</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Izin & Sakit</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ $statistik['izin_hari_ini'] + $statistik['sakit_hari_ini'] }}
                        </p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Alpha</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $statistik['alpha_hari_ini'] }}</p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                            @if($statistik['total_siswa'] > 0)
                                {{ round(($statistik['alpha_hari_ini'] / $statistik['total_siswa']) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap Harian -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Rekap Harian - {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('rekap.export', array_merge(request()->query(), ['type' => 'harian'])) }}"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hadir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Izin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sakit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alpha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">% Hadir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse($rekapHarian as $key => $r)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $r['kelas'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $r['total_siswa'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-semibold">{{ $r['Hadir'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600 dark:text-yellow-400">{{ $r['Izin'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400">{{ $r['Sakit'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400">{{ $r['Alpha'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $r['persen_hadir'] >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                        ($r['persen_hadir'] >= 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                        {{ $r['persen_hadir'] }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('rekap.detail-siswa', $key) }}?tanggal={{ $tanggal }}"
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm transition duration-200 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    <div class="py-12">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="mt-4 text-lg">Tidak ada data absensi</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Data akan muncul setelah ada absensi yang tercatat</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rekap Bulanan (Conditional) -->
        @if(request('type') == 'bulanan')
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Rekap Bulanan - {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}
                    </h2>
                    <a href="{{ route('rekap.export', array_merge(request()->query(), ['type' => 'bulanan'])) }}"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hari Sekolah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Kehadiran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hadir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Izin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sakit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">% Hadir</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse($rekapBulanan as $key => $r)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $r['kelas'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $r['total_siswa'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $r['hari_sekolah'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $r['total_kehadiran'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-semibold">{{ $r['Hadir'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600 dark:text-yellow-400">{{ $r['Izin'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400">{{ $r['Sakit'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $r['persen_hadir'] >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            ($r['persen_hadir'] >= 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                            {{ $r['persen_hadir'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        <div class="py-12">
                                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <p class="mt-4 text-lg">Tidak ada data absensi bulanan</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Data akan muncul setelah ada absensi yang tercatat</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('type');
            const dateField = document.getElementById('date-field');
            const monthField = document.getElementById('month-field');

            function toggleFields() {
                const type = typeSelect.value;
                if (type === 'bulanan') {
                    dateField.classList.add('hidden');
                    monthField.classList.remove('hidden');
                } else {
                    dateField.classList.remove('hidden');
                    monthField.classList.add('hidden');
                }
            }

            // Initialize
            toggleFields();

            // Add event listener
            typeSelect.addEventListener('change', toggleFields);
        });
    </script>
@endpush