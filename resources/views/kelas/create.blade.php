@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Tambah Kelas Baru</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Lengkapi form untuk menambahkan kelas baru</p>
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
        @if(session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 dark:bg-green-900 dark:border-green-600 dark:text-green-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

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
            <!-- Form Tambah Kelas -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2
                        class="text-xl font-semibold mb-6 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                        Informasi Kelas
                    </h2>

                    <form action="{{ route('kelas.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Nama Kelas -->
                            <div>
                                <label for="nama_kelas"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Kelas <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="Contoh: X IPA 1, XII IPS 2" required autofocus>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Nama kelas tidak bisa sama</p>
                            </div>

                            <!-- Wali Kelas -->
                            <div>
                                <label for="wali_kelas_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Wali Kelas
                                </label>
                                <select name="wali_kelas_id" id="wali_kelas_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tahun Ajaran -->
                            <div>
                                <label for="tahun_ajaran"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tahun Ajaran
                                </label>
                                <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran') }}"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="Contoh: 2024/2025">
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Contoh: 2024/2025</p>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div
                            class="mt-8 flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panel Informasi -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Informasi Tambahan -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <h2
                            class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                            Informasi
                        </h2>

                        <div class="space-y-4">
                            <div
                                class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
                                <h3 class="font-semibold text-blue-800 dark:text-blue-200 text-sm mb-3">
                                    Tips
                                </h3>
                                <ul class="text-blue-700 dark:text-blue-300 text-sm space-y-2">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 dark:text-blue-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Pastikan nama kelas unik dan berbeda
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 dark:text-blue-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tahun ajaran mengikuti format 2024/2025
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 dark:text-blue-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Wali kelas dapat ditambahkan kemudian
                                    </li>
                                </ul>
                            </div>

                            <!-- Note -->
                            <div
                                class="p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                <h3 class="font-semibold text-yellow-800 dark:text-yellow-200 text-sm mb-2">
                                    Catatan
                                </h3>
                                <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                                    Setelah kelas dibuat, Anda dapat langsung menambahkan siswa ke dalam kelas tersebut.
                                </p>
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