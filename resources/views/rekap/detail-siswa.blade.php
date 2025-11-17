@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Kehadiran Siswa</h1>
                <p class="text-gray-600 mt-2">
                    Kelas: <strong>{{ $kelas->nama_kelas }}</strong> |
                    Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</strong>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('rekap.index') }}?tanggal={{ $tanggal }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                    ← Kembali ke Rekap
                </a>
                <a href="{{ route('absensi.scanner') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                    📱 Scan QR
                </a>
            </div>
        </div>

        <!-- Statistik Kelas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            @php
                $totalSiswa = count($siswaWithAbsensi);
                $hadir = collect($siswaWithAbsensi)->where('status', 'Hadir')->count();
                $izin = collect($siswaWithAbsensi)->where('status', 'Izin')->count();
                $sakit = collect($siswaWithAbsensi)->where('status', 'Sakit')->count();
                $alpha = collect($siswaWithAbsensi)->where('status', 'Alpha')->count();
                $persenHadir = $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0;
            @endphp

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSiswa }}</p>
                    </div>
                    <div class="text-blue-500 text-2xl">👨‍🎓</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Hadir</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $hadir }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ $persenHadir }}%</p>
                    </div>
                    <div class="text-green-500 text-2xl">✅</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Izin & Sakit</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $izin + $sakit }}</p>
                    </div>
                    <div class="text-yellow-500 text-2xl">⚠️</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Alpha</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $alpha }}</p>
                        <p class="text-xs text-red-600 mt-1">
                            {{ $totalSiswa > 0 ? round(($alpha / $totalSiswa) * 100, 1) : 0 }}%
                        </p>
                    </div>
                    <div class="text-red-500 text-2xl">❌</div>
                </div>
            </div>
        </div>

        <!-- Daftar Siswa -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Siswa - {{ $kelas->nama_kelas }}</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                                Absen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($siswaWithAbsensi as $item)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['siswa']->nama_lengkap }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $item['siswa']->nis ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'Hadir' => 'bg-green-100 text-green-800',
                                            'Izin' => 'bg-yellow-100 text-yellow-800',
                                            'Sakit' => 'bg-blue-100 text-blue-800',
                                            'Alpha' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusIcons = [
                                            'Hadir' => '✅',
                                            'Izin' => '📝',
                                            'Sakit' => '🏥',
                                            'Alpha' => '❌'
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$item['status']] }} flex items-center">
                                        <span class="mr-2">{{ $statusIcons[$item['status']] }}</span>
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($item['waktu_absen'])
                                        {{ \Carbon\Carbon::parse($item['waktu_absen'])->format('H:i:s') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $item['metode_absen'] ?? 'Manual' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($item['status'] === 'Alpha')
                                        <a href="{{ route('absensi.create') }}?siswa_id={{ $item['siswa']->id }}"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded text-sm">
                                            Input Absen
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">Sudah absen</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    <div class="py-8">
                                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                            </path>
                                        </svg>
                                        <p class="mt-4 text-lg">Tidak ada siswa di kelas ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Info -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="text-blue-500 text-xl mr-3">💡</div>
                <div>
                    <h3 class="font-semibold text-blue-800">Informasi Detail Kehadiran</h3>
                    <p class="text-blue-700 text-sm mt-1">
                        • Klik "Input Absen" untuk siswa yang statusnya Alpha<br>
                        • Status Hadir: Tercatat via QR Code atau input manual<br>
                        • Waktu absen menampilkan jam ketika siswa melakukan absensi
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection