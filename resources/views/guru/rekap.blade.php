@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Rekap Absensi</h1>
                <p class="text-gray-600 mt-2">
                    Guru: <strong>{{ $guru->nama_lengkap }}</strong> |
                    Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</strong>
                </p>
                @if($guru->nip)
                    <p class="text-sm text-gray-500">NIP: {{ $guru->nip }}</p>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('absensi.scanner') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                    📱 Scan QR
                </a>
                <a href="{{ route('guru.dashboard') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                    ← Dashboard
                </a>
            </div>
        </div>

        <!-- Filter Tanggal -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <form action="{{ route('rekap.guru') }}" method="GET" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    Terapkan
                </button>
            </form>
        </div>

        <!-- Rekap Kelas -->
        @if(count($rekapHarian) > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Rekap Kelas yang Diampu</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                    Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Izin
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">%
                                    Hadir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($rekapHarian as $key => $r)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $r['kelas'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $r['total_siswa'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">{{ $r['Hadir'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">{{ $r['Izin'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">{{ $r['Sakit'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $r['Alpha'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs 
                                                    {{ $r['persen_hadir'] >= 80 ? 'bg-green-100 text-green-800' :
                                ($r['persen_hadir'] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $r['persen_hadir'] }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('rekap.detail-siswa', $key) }}?tanggal={{ $tanggal }}"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded text-sm transition duration-200">
                                                    Detail Siswa
                                                </a>
                                            </td>
                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistik Ringkasan -->
            @php
                $totalSiswa = array_sum(array_column($rekapHarian, 'total_siswa'));
                $totalHadir = array_sum(array_column($rekapHarian, 'Hadir'));
                $totalIzin = array_sum(array_column($rekapHarian, 'Izin'));
                $totalSakit = array_sum(array_column($rekapHarian, 'Sakit'));
                $totalAlpha = array_sum(array_column($rekapHarian, 'Alpha'));
                $totalPersen = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;
            @endphp

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-800 mb-3">📊 Statistik Keseluruhan</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-700">Total Siswa:</span>
                            <span class="font-semibold">{{ $totalSiswa }}</span>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>Hadir:</span>
                            <span class="font-semibold">{{ $totalHadir }}
                                ({{ round(($totalHadir / $totalSiswa) * 100, 1) }}%)</span>
                        </div>
                        <div class="flex justify-between text-yellow-600">
                            <span>Izin:</span>
                            <span class="font-semibold">{{ $totalIzin }} ({{ round(($totalIzin / $totalSiswa) * 100, 1) }}%)</span>
                        </div>
                        <div class="flex justify-between text-blue-600">
                            <span>Sakit:</span>
                            <span class="font-semibold">{{ $totalSakit }}
                                ({{ round(($totalSakit / $totalSiswa) * 100, 1) }}%)</span>
                        </div>
                        <div class="flex justify-between text-red-600">
                            <span>Alpha:</span>
                            <span class="font-semibold">{{ $totalAlpha }}
                                ({{ round(($totalAlpha / $totalSiswa) * 100, 1) }}%)</span>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800 mb-3">💡 Keterangan</h3>
                    <div class="space-y-2 text-sm text-green-700">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span>≥ 80%: Kehadiran Sangat Baik</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            <span>60-79%: Kehadiran Cukup</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            <span>&lt; 60%: Perlu Perhatian</span>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <div class="text-yellow-500 text-5xl mb-4">🏫</div>
                <h3 class="text-xl font-semibold text-yellow-800 mb-2">Tidak Ada Kelas yang Diampu</h3>
                <p class="text-yellow-700 mb-4">Anda belum ditugaskan sebagai wali kelas.</p>
                <a href="{{ route('guru.dashboard') }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg inline-block">
                    Kembali ke Dashboard
                </a>
            </div>
        @endif
    </div>
@endsection