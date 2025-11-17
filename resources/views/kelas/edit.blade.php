@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Kelas</h1>
            <p class="text-gray-600 mt-2">Perbarui data kelas {{ $kelas->nama_kelas }}</p>
        </div>
        <a href="{{ route('kelas.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Notifikasi -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

    <!-- Form Edit Kelas -->
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kelas -->
                <div class="md:col-span-2">
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_kelas" 
                           id="nama_kelas" 
                           value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: X IPA 1, XII IPS 2"
                           required
                           autofocus>
                </div>

                <!-- Wali Kelas -->
                <div>
                    <label for="wali_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Wali Kelas
                    </label>
                    <input type="text" 
                           name="wali_kelas" 
                           id="wali_kelas" 
                           value="{{ old('wali_kelas', $kelas->wali_kelas) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Nama wali kelas">
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun Ajaran
                    </label>
                    <input type="text" 
                           name="tahun_ajaran" 
                           id="tahun_ajaran" 
                           value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: 2024/2025">
                </div>
            </div>

            <!-- Informasi Siswa -->
            @if($kelas->siswa_count > 0)
            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-green-800 font-medium">Kelas ini memiliki {{ $kelas->siswa_count }} siswa</p>
                        <p class="text-xs text-green-600 mt-1">
                            Perubahan data kelas akan mempengaruhi semua siswa dalam kelas ini.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tombol Aksi -->
            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('kelas.show', $kelas->id) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Batal
                </a>
                <button type="submit" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Update Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection