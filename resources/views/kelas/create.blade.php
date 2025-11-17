@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tambah Kelas Baru</h1>
            <p class="text-gray-600 mt-2">Isi form berikut untuk menambahkan kelas baru</p>
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
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

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

    <!-- Form Tambah Kelas -->
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('kelas.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kelas -->
                <div class="md:col-span-2">
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_kelas" 
                           id="nama_kelas" 
                           value="{{ old('nama_kelas') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: X IPA 1, XII IPS 2"
                           required
                           autofocus>
                    <p class="mt-1 text-xs text-gray-500">Nama kelas harus unik dan tidak boleh duplikat</p>
                </div>

                <!-- Wali Kelas -->
                <div>
                    <label for="wali_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Wali Kelas
                    </label>
                    <input type="text" 
                           name="wali_kelas" 
                           id="wali_kelas" 
                           value="{{ old('wali_kelas') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Nama wali kelas">
                    <p class="mt-1 text-xs text-gray-500">Opsional - bisa diisi nanti</p>
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun Ajaran
                    </label>
                    <input type="text" 
                           name="tahun_ajaran" 
                           id="tahun_ajaran" 
                           value="{{ old('tahun_ajaran') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: 2024/2025">
                    <p class="mt-1 text-xs text-gray-500">Format: 2024/2025</p>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-800 font-medium">Informasi Kelas</p>
                        <ul class="text-xs text-blue-600 mt-1 list-disc list-inside space-y-1">
                            <li>Setelah kelas dibuat, Anda dapat menambahkan siswa ke dalam kelas</li>
                            <li>Setiap kelas akan memiliki QR Code untuk masing-masing siswa</li>
                            <li>Data kelas dapat diedit kapan saja</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('kelas.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Kelas::count() }}</div>
            <div class="text-sm text-gray-600 mt-1">Total Kelas</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ \App\Models\Siswa::count() }}</div>
            <div class="text-sm text-gray-600 mt-1">Total Siswa</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Kelas::has('siswa')->count() }}</div>
            <div class="text-sm text-gray-600 mt-1">Kelas Aktif</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-format tahun ajaran
    document.getElementById('tahun_ajaran').addEventListener('input', function(e) {
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