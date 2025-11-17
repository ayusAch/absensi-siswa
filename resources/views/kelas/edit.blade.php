@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit Kelas</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Update data kelas {{ $kelas->nama_kelas }}</p>
                </div>
                <a href="{{ route('kelas.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Notifikasi -->
        @if ($errors->any())
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 dark:bg-red-900 dark:border-red-600 dark:text-red-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Terjadi kesalahan:</span>
                </div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Edit Kelas -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2
                        class="text-xl font-semibold mb-6 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                        Informasi Kelas
                    </h2>

                    <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Nama Kelas -->
                            <div>
                                <label for="nama_kelas"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Kelas <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_kelas" id="nama_kelas"
                                    value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="Contoh: X IPA 1, XII IPS 2" required autofocus>
                            </div>

                            <!-- Wali Kelas -->
                            <div>
                                <label for="wali_kelas"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Wali Kelas
                                </label>
                                <input type="text" name="wali_kelas" id="wali_kelas"
                                    value="{{ old('wali_kelas', $kelas->wali_kelas) }}"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="Nama lengkap wali kelas">
                            </div>

                            <!-- Tahun Ajaran -->
                            <div>
                                <label for="tahun_ajaran"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tahun Ajaran
                                </label>
                                <input type="text" name="tahun_ajaran" id="tahun_ajaran"
                                    value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="Contoh: 2024/2025">
                            </div>
                        </div>

                        <!-- Informasi Siswa -->
                        @if($kelas->siswa_count > 0)
                            <div
                                class="mt-6 p-4 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-700">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 mt-0.5 flex-shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                            Kelas ini memiliki{{ $kelas->siswa_count }} siswa
                                        </p>
                                        <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                                            Perubahan data kelas akan mempengaruhi semua siswa dalam kelas ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Tombol Aksi -->
                        <div
                            class="mt-8 flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panel Informasi -->
            <div class="lg:col-span-1">
                <div class="space-y-6">

                    <!-- Informasi Penting -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <h2
                            class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                            Informasi
                        </h2>

                        <div class="space-y-4">
                            <div
                                class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
                                <h3 class="font-semibold text-blue-800 dark:text-blue-200 text-sm mb-2">
                                    Perubahan Data
                                </h3>
                                <ul class="text-blue-700 dark:text-blue-300 text-sm space-y-2">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 dark:text-blue-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Perubahan nama kelas akan terlihat di semua siswa
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 dark:text-blue-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Data absensi tetap tersimpan
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 dark:text-blue-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        QR Code siswa tidak terpengaruh
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto-format tahun ajaran
        document.getElementById('tahun_ajaran').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 4) {
                let firstPart = value.substring(0, 4);
                let secondPart = value.substring(4, 8);
                if (secondPart) {
                    e.target.value = firstPart + '/' + secondPart;
                } else {
                    e.target.value = firstPart;
                }
            }
        });

        // Focus on nama_kelas field
        document.getElementById('nama_kelas').focus();
    </script>
@endsection