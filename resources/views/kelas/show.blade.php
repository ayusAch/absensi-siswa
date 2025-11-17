@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Detail Kelas</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Informasi lengkap kelas {{ $kelas->nama_kelas }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('kelas.edit', $kelas->id) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Kelas
                    </a>
                    <a href="{{ route('kelas.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Informasi Kelas -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2
                        class="text-xl font-semibold mb-6 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                        Informasi Kelas
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Nama Kelas</label>
                                <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $kelas->nama_kelas }}
                                </p>
                            </div>

                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Wali Kelas</label>
                                <p class="mt-1 text-gray-800 dark:text-white">
                                    @if($kelas->wali_kelas)
                                        {{ $kelas->wali_kelas }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">Belum ditentukan</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Tahun
                                    Ajaran</label>
                                <p class="mt-1 text-gray-800 dark:text-white">
                                    @if($kelas->tahun_ajaran)
                                        {{ $kelas->tahun_ajaran }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">Belum diatur</span>
                                    @endif
                                </p>
                            </div>

                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Jumlah
                                    Siswa</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ $kelas->siswa->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik Cepat -->
                    <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div
                            class="bg-green-50 dark:bg-green-900 rounded-lg p-4 text-center border border-green-200 dark:border-green-700">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ \App\Models\Absensi::whereIn('siswa_id', $kelas->siswa->pluck('id'))->where('tanggal', now()->toDateString())->where('status', 'Hadir')->count() }}
                            </div>
                            <div class="text-sm text-green-800 dark:text-green-200">Hadir Hari Ini</div>
                        </div>
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900 rounded-lg p-4 text-center border border-yellow-200 dark:border-yellow-700">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ \App\Models\Absensi::whereIn('siswa_id', $kelas->siswa->pluck('id'))->where('tanggal', now()->toDateString())->whereIn('status', ['Izin', 'Sakit'])->count() }}
                            </div>
                            <div class="text-sm text-yellow-800 dark:text-yellow-200">Izin/Sakit</div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Siswa -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mt-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Daftar Siswa di Kelas</h2>
                    </div>

                    @if($kelas->siswa->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nama Siswa
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jenis Kelamin
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Barcode
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status Hari Ini
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($kelas->siswa as $key => $siswa)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $siswa->nama_lengkap }}</div>
                                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $siswa->alamat }}</div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                                {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' }}">
                                                                    {{ $siswa->jenis_kelamin }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @if($siswa->qr_code)
                                                                    <img src="{{ $siswa->qr_code }}" alt="QR Code"
                                                                        class="w-12 h-12 border border-gray-300 dark:border-gray-600 rounded-lg mx-auto">
                                                                @else
                                                                    <span class="text-gray-400 dark:text-gray-500 text-xs">No QR</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @php
                                                                    $absensiHariIni = \App\Models\Absensi::where('siswa_id', $siswa->id)
                                                                        ->where('tanggal', now()->toDateString())
                                                                        ->first();

                                                                    $statusColors = [
                                                                        'Hadir' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                                        'Izin' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                                        'Sakit' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                                        'Alpha' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                                    ];
                                                                    $status = $absensiHariIni ? $absensiHariIni->status : 'Alpha';
                                                                    $color = $statusColors[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
                                                                @endphp
                                                                <span
                                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                                    {{ $status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Belum ada siswa dalam kelas ini</p>
                            <a href="{{ route('siswa.create') }}?kelas_id={{ $kelas->id }}"
                                class="mt-4 inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                                Tambah Siswa Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Informasi Kelas -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2
                        class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                        Statistik
                    </h2>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Total Siswa:</span>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $kelas->siswa->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Laki-laki:</span>
                            <span class="font-semibold text-blue-600 dark:text-blue-400">
                                {{ $kelas->siswa->where('jenis_kelamin', 'Laki-laki')->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Perempuan:</span>
                            <span class="font-semibold text-pink-600 dark:text-pink-400">
                                {{ $kelas->siswa->where('jenis_kelamin', 'Perempuan')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection