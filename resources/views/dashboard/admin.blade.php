@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header Khusus Admin -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Administrator</h1>
                <p class="text-gray-600 mt-2">Panel kontrol lengkap untuk mengelola sistem absensi</p>
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
            </div>
        </div>

        <!-- Quick Stats Khusus Admin -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Siswa -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSiswa }}</p>
                        <a href="{{ route('siswa.index') }}"
                            class="text-xs text-blue-500 hover:text-blue-700 mt-1 inline-block">
                            Kelola siswa →
                        </a>
                    </div>
                    <div class="text-blue-500 text-2xl">👨‍🎓</div>
                </div>
            </div>

            <!-- Total Kelas -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Kelas</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalKelas }}</p>
                        <a href="{{ route('kelas.index') }}"
                            class="text-xs text-green-500 hover:text-green-700 mt-1 inline-block">
                            Kelola kelas →
                        </a>
                    </div>
                    <div class="text-green-500 text-2xl">🏫</div>
                </div>
            </div>

            <!-- Total Guru -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Guru</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalGuru }}</p>
                        <a href="{{ route('guru.index') }}"
                            class="text-xs text-purple-500 hover:text-purple-700 mt-1 inline-block">
                            Kelola guru →
                        </a>
                    </div>
                    <div class="text-purple-500 text-2xl">👨‍🏫</div>
                </div>
            </div>

            <!-- QR Generated -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">QR Code Tergenerate</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $qrGenerated }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $totalSiswa > 0 ? round(($qrGenerated / $totalSiswa) * 100, 1) : 0 }}% dari total
                        </p>
                    </div>
                    <div class="text-yellow-500 text-2xl">📄</div>
                </div>
            </div>
        </div>

        <!-- Siswa Terbaru (Section Baru untuk Admin) -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">👥 Siswa Terbaru</h2>
                <a href="{{ route('siswa.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                    Lihat Semua →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Siswa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                                Kelamin</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($siswaTerbaru as $siswa)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $siswa->nama_lengkap }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $siswa->kelas->nama_kelas }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $siswa->jenis_kelamin }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $siswa->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    <p>Belum ada data siswa</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sisa kode dari dashboard sebelumnya (Absensi Hari Ini, Kelas Terbaik, Quick Actions, dll) -->
        <!-- ... (tetap sama seperti kode Anda sebelumnya) ... -->
    </div>
@endsection