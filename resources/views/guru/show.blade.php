@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Detail Guru</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Informasi lengkap {{ $guru->nama_lengkap }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('guru.edit', $guru->id) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Guru
                    </a>
                    <a href="{{ route('guru.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18">
                            </path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informasi Guru -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Pribadi</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Nama
                                        Lengkap</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">
                                        {{ $guru->nama_lengkap }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">NIP</label>
                                    <p class="mt-1 text-gray-800 dark:text-gray-200">
                                        @if($guru->nip)
                                            {{ $guru->nip }}
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Email</label>
                                    <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $guru->email }}</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Jenis
                                        Kelamin</label>
                                    <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $guru->jenis_kelamin }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal
                                        Lahir</label>
                                    <p class="mt-1 text-gray-800 dark:text-gray-200">
                                        @if($guru->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d F Y') }}
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                                    <span
                                        class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $guru->status == 'Aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $guru->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Alamat</label>
                            <p class="text-gray-800 dark:text-gray-200">
                                @if($guru->alamat)
                                    {{ $guru->alamat }}
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </p>
                        </div>

                        <!-- Keterangan -->
                        @if($guru->keterangan)
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <label
                                    class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Keterangan</label>
                                <p class="text-gray-800 dark:text-gray-200">{{ $guru->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info Kontak -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Kontak</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">No.
                                    Telepon</label>
                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                    @if($guru->no_telepon)
                                        {{ $guru->no_telepon }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kelas yang Diampu -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Kelas yang Diampu</h2>
                    </div>
                    <div class="p-6">
                        @if($guru->kelas && $guru->kelas->count() > 0)
                            <div class="space-y-3">
                                @foreach($guru->kelas as $kelas)
                                    <div
                                        class="flex justify-between items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                                        <span
                                            class="text-sm font-medium text-blue-800 dark:text-blue-300">{{ $kelas->nama_kelas }}</span>
                                        <span
                                            class="text-xs text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded-full">
                                            {{ $kelas->siswa_count ?? 0 }} siswa
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Belum menjadi wali kelas</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Tambahan</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Dibuat</span>
                                <span class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ $guru->created_at->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Diperbarui</span>
                                <span class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ $guru->updated_at->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection