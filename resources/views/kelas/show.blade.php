@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-6xl">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Kelas</h1>
                <p class="text-gray-600 mt-2">Informasi lengkap kelas {{ $kelas->nama_kelas }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('kelas.edit', $kelas->id) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Kelas
                </a>
                <a href="{{ route('kelas.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informasi Kelas -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Kelas</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nama Kelas</label>
                                <p class="mt-1 text-lg font-semibold text-gray-800">{{ $kelas->nama_kelas }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Wali Kelas</label>
                                <p class="mt-1 text-gray-800">
                                    @if($kelas->wali_kelas)
                                        {{ $kelas->wali_kelas }}
                                    @else
                                        <span class="text-gray-400">Belum ditentukan</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Tahun Ajaran</label>
                                <p class="mt-1 text-gray-800">
                                    @if($kelas->tahun_ajaran)
                                        {{ $kelas->tahun_ajaran }}
                                    @else
                                        <span class="text-gray-400">Belum diatur</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Jumlah Siswa</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600">{{ $kelas->siswa->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik Cepat -->
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $kelas->siswa->where('jenis_kelamin', 'Laki-laki')->count() }}</div>
                            <div class="text-sm text-blue-800">Laki-laki</div>
                        </div>
                        <div class="bg-pink-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-pink-600">
                                {{ $kelas->siswa->where('jenis_kelamin', 'Perempuan')->count() }}</div>
                            <div class="text-sm text-pink-800">Perempuan</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">
                                {{ \App\Models\Absensi::whereIn('siswa_id', $kelas->siswa->pluck('id'))->where('tanggal', now()->toDateString())->where('status', 'Hadir')->count() }}
                            </div>
                            <div class="text-sm text-green-800">Hadir Hari Ini</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ \App\Models\Absensi::whereIn('siswa_id', $kelas->siswa->pluck('id'))->where('tanggal', now()->toDateString())->whereIn('status', ['Izin', 'Sakit'])->count() }}
                            </div>
                            <div class="text-sm text-yellow-800">Izin/Sakit</div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Siswa -->
                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Daftar Siswa</h2>
                        <a href="{{ route('siswa.create') }}?kelas_id={{ $kelas->id }}"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Tambah Siswa
                        </a>
                    </div>

                    @if($kelas->siswa->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Siswa</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis Kelamin</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            QR Code</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status Hari Ini</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($kelas->siswa as $key => $siswa)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $key + 1 }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                                <div class="text-xs text-gray-500">{{ $siswa->alamat }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                {{ $siswa->jenis_kelamin }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($siswa->qr_code)
                                                    <img src="{{ $siswa->qr_code }}" alt="QR Code"
                                                        class="w-12 h-12 border rounded-lg mx-auto">
                                                @else
                                                    <span class="text-gray-400 text-xs">No QR</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $absensiHariIni = \App\Models\Absensi::where('siswa_id', $siswa->id)
                                                        ->where('tanggal', now()->toDateString())
                                                        ->first();

                                                    $statusColors = [
                                                        'Hadir' => 'bg-green-100 text-green-800',
                                                        'Izin' => 'bg-yellow-100 text-yellow-800',
                                                        'Sakit' => 'bg-blue-100 text-blue-800',
                                                        'Alpha' => 'bg-red-100 text-red-800'
                                                    ];
                                                    $status = $absensiHariIni ? $absensiHariIni->status : 'Alpha';
                                                    $color = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('siswa.show', $siswa->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2 py-1 rounded text-xs">
                                                        Detail
                                                    </a>
                                                    <a href="{{ route('siswa.edit', $siswa->id) }}"
                                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 px-2 py-1 rounded text-xs">
                                                        Edit
                                                    </a>
                                                    @if($siswa->qr_code)
                                                        <a href="{{ route('siswa.download.qr', $siswa->id) }}"
                                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-2 py-1 rounded text-xs">
                                                            QR
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <p class="mt-4 text-lg text-gray-500">Belum ada siswa dalam kelas ini</p>
                            <a href="{{ route('siswa.create') }}?kelas_id={{ $kelas->id }}"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm">
                                Tambah Siswa Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar - Quick Actions -->
            <div class="lg:col-span-1">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Aksi Cepat</h2>
                    <div class="space-y-3">
                        <a href="{{ route('absensi.create') }}?kelas_id={{ $kelas->id }}"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg flex items-center justify-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Absensi Manual
                        </a>

                        <a href="{{ route('absensi.scanner') }}"
                            class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg flex items-center justify-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                </path>
                            </svg>
                            Scan QR Code
                        </a>

                        <a href="{{ route('absensi.rekap-harian') }}?kelas_id={{ $kelas->id }}"
                            class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-lg flex items-center justify-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Rekap Harian
                        </a>
                    </div>
                </div>

                <!-- Informasi Kelas -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Informasi</h2>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Total Siswa:</span>
                            <span class="font-semibold">{{ $kelas->siswa->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Laki-laki:</span>
                            <span
                                class="font-semibold text-blue-600">{{ $kelas->siswa->where('jenis_kelamin', 'Laki-laki')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Perempuan:</span>
                            <span
                                class="font-semibold text-pink-600">{{ $kelas->siswa->where('jenis_kelamin', 'Perempuan')->count() }}</span>
                        </div>
                        <div class="pt-3 border-t">
                            <a href="{{ route('siswa.create') }}?kelas_id={{ $kelas->id }}"
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg flex items-center justify-center text-sm mt-2">
                                + Kelola Siswa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection