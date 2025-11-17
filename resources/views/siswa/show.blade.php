@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Detail Siswa</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Informasi lengkap siswa</p>
                </div>
                <a href="{{ route('siswa.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informasi Siswa -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2
                    class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                    Informasi
                </h2>

                <div class="space-y-6">
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Nama Lengkap</label>
                            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $siswa->nama_lengkap }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Kelas</label>
                            <p class="mt-1 text-gray-800 dark:text-white">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $siswa->kelas->nama_kelas }}
                                </span>
                            </p>
                        </div>

                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Jenis Kelamin</label>
                            <p class="mt-1 text-gray-800 dark:text-white">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium 
                                        {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' }}">
                                    {{ $siswa->jenis_kelamin }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Alamat</label>
                        <p class="mt-1 text-gray-800 dark:text-white">{{ $siswa->alamat ?? '-' }}</p>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-8 flex space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('siswa.edit', $siswa->id) }}"
                        class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg flex items-center justify-center transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Data
                    </a>

                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="flex-1"
                        onsubmit="return confirm('Apakah yakin ingin menghapus siswa ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg flex items-center justify-center transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2
                    class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                    Barcode
                </h2>

                @if($siswa->qr_code)
                    <div class="text-center">
                        <!-- QR Code Display -->
                        <div
                            class="inline-block border-4 border-gray-200 dark:border-gray-600 rounded-lg p-6 mb-6 bg-white dark:bg-gray-700">
                            <img src="{{ $siswa->qr_code }}" alt="QR Code {{ $siswa->nama_lengkap }}" class="w-64 h-64 mx-auto">
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Scan Barcode untuk informasi siswa</p>
                        <p class="font-mono text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-600 p-3 rounded">
                            SISWA:{{ $siswa->id }}
                        </p>

                        <!-- Tombol Aksi QR Code -->
                        <div class="mt-8 space-y-3">
                            <a href="{{ route('siswa.download.qr', $siswa->id) }}"
                                class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg flex items-center justify-center font-semibold transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Download Barcode
                            </a>

                            <a href="{{ route('siswa.regenerate.qr', $siswa->id) }}"
                                onclick="return confirm('Apakah yakin ingin generate ulang QR Code? QR Code lama akan diganti.')"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg flex items-center justify-center transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Regenerate
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Jika belum ada QR Code -->
                    <div class="text-center py-12">
                        <div
                            class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 text-lg">Barcode belum tersedia</p>
                        <a href="{{ route('siswa.regenerate.qr', $siswa->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg inline-flex items-center transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Generate Barcode
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection